<?php
// +-----------------------------------------------------------------------+
// | RSLT - a plugin for dotclear                                          |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2013-2015 Nicolas Roudaire        http://www.nikrou.net  |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License version 2 as     |
// | published by the Free Software Foundation                             |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,            |
// | MA 02110-1301 USA.                                                    |
// +-----------------------------------------------------------------------+

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$page_url = $p_url.'&object=song';
$rslt_albums_service = $p_url.'&object=album';
$rslt_person_service = $p_url.'&object=person';
$meta_fields = array('author','compositor','adaptator','singer','editor');

if (!empty($_REQUEST['action']) && (in_array($_REQUEST['action'], array('edit', 'add')))) {
    $action = $_REQUEST['action'];
    $page_title = __('New song');

    $json = array();
    $song = array(
        'title' => '', 'author' => '', 'compositor' => '', 'adaptator' => '',
        'singer' => '', 'publication_date' => '', 'editor' => '', 'url' => ''
    );

    $song_manager = new songManager($core);
    if (!empty($_GET['id'])) {
        $page_title = __('Edit song');

        $rs = $song_manager->findById($_GET['id']);
        if (!$rs->isEmpty()) {
            foreach ($meta_fields as $field) {
                $json[$field] = $rs->getJson($field);
                $song[$field] = $rs->getIds($field);
            }
            $song['id'] = (int) $_GET['id'];
            $song['title'] = $rs->title;
            $song['url'] = $rs->url;
            $song['publication_date'] = $rs->publication_date;
            $_SESSION['song_id'] = $_GET['id'];
        } else {
            dcPage::addErrorNotice('That song does not exist.');
            http::redirect($page_url);
        }
    }
    if (!empty($_POST['save_song'])) {
        $cur = $song_manager->openCursor();
        $meta = array();
        $cur->title = (string) $_POST['song_title'];
        foreach ($meta_fields as $field) {
            $persons = array();
            if (!empty($_POST['song_'.$field])) {
                $raw_persons = explode(',', $_POST['song_'.$field]);

                $person_manager = new personManager($core);
                foreach ($raw_persons as $raw_person) {
                    if (preg_match('/^~~(\d+)~~$/', $raw_person, $matches)) {
                        $person = $person_manager->findById($matches[1]);
                        if (!$person->isEmpty()) {
                            $persons[] = array('id'=> $matches[1], 'name' => $person->name);
                        }
                    } else {
                        $cur_person = $person_manager->openCursor();
                        $cur_person->name = $raw_person;
                        $person = $person_manager->add($cur_person);
                        if ($person) {
                            $persons[] = array('id'=> $person->id, 'name' => $person->name);
                        }
                    }
                }
            }
            $meta[$field] = $persons;
        }
        $cur->meta = $meta;
        $cur->publication_date = (int) $_POST['song_publication_date'];
        if (isset($_POST['song_url'])) {
            $cur->url = (string) $_POST['song_url'];
        }

        try {
            if ($action=='edit') {
                $song_manager->update($_SESSION['song_id'], $cur);
                $message = __('The song has been updated.');
                unset($_SESSION['song_id']);
            } else {
                $song_manager->add($cur);
                $message = __('The song has been added.');
            }
            $_SESSION['rslt_message'] = $message;
            http::redirect($page_url.'&action=edit&id='.(int) $_POST['id']);
        } catch (Exception $e) {
            $core->error->add($e->getMessage());
        }
    }

    include(dirname(__FILE__).'/../views/form_song.tpl');
} elseif (!empty($_REQUEST['action']) && ($_REQUEST['action']=='delete')
          && !empty($_POST['songs']) && $_POST['object']=='song') {
    $song_manager->delete($_POST['songs']);
    $_SESSION['rslt_message'] = __('The song has been deleted.',
                                   'The songs have been deleted.', count($_POST['songs']));
    http::redirect($page_url);
} elseif (!empty($_REQUEST['action']) && ($_REQUEST['action']=='associate_to_album')
          && !empty($_POST['songs']) && ($_POST['object']=='song') && !empty($_POST['album_id'])) {
    $album_id = (int) $_POST['album_id'];
    $album_song_manager = new albumSong($core);

    try {
        foreach ($_POST['songs'] as $song_id) {
            $album_song_manager->add($album_id, $song_id);
        }
        $_SESSION['rslt_message'] = __('The song has been associated to album.',
                                       'The songs have been associated to album.', count($_POST['songs']));
        http::redirect($page_url);
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }
} else {
    $song_manager = new songManager($core);

    $form_filter_title = __('Show filters and display options');
    $show_filters = false;

    /* pagination */
    $page_songs = !empty($_GET['page_songs']) ? (integer) $_GET['page_songs'] : 1;
    $nb_per_page_songs =  10;

    if (!empty($_GET['nbs']) && (integer) $_GET['nbs'] > 0) {
        $nb_per_page_songs = (integer) $_GET['nbs'];
    }
    $limit_songs = array((($page_songs-1)*$nb_per_page_songs), $nb_per_page_songs);

    $songs_action_combo = array();
    $songs_action_combo[''] = null;
    $songs_action_combo[__('Delete')] = 'delete';
    $songs_action_combo[__('Associate to album')] = 'associate_to_album';
    if (is_callable('tweakUrls::tweakBlogURL')) {
        $songs_action_combo[__('Clean URLs')] = 'cleanurls';
    }

    /* filters */
    $show_filters = false;
    $author_id = $compositor_id = $adaptator_id = $singer_id = $editor_id = $publication_date = null;
    $sortby_song = $order_song = null;
    $filters_params = array();
    $sq= '';

    $authors_combo = array();
    $compositors_combo = array();
    $adaptators_combo = array();
    $editors_combo = array();
    $singers_combo = array();
    // $authors_combo = array_merge(array('' => ''), array_flip(Authors::getAll()));
    // $compositors_combo = array_merge(array('' => ''), array_flip(Authors::getAll()));
    // $adaptators_combo = array_merge(array('' => ''), array_flip(Authors::getAll()));
    // $editors_combo = rsltAdminCombo::makeCombo($song_manager->getEditors(), 'editor');
    // $singers_combo = rsltAdminCombo::makeComboSinger($song_manager->getSingers(), 'singer');
    $order_combo = array(__('Descending') => 'DESC', __('Ascending') => 'ASC');
    $sortby_songs_combo = array(
        '' => '',
        __('Title') => 'title',
        __('Singer') => 'singer',
        __('Publication date') => 'publication_date',
        __('Author') => 'author'
    );

    if (!empty($_GET['sq'])) {
        $sq = $_GET['sq'];
        $filters_params['like']['q'] = $sq;
        $show_filters = true;
    }

    // if (!empty($_GET['editor_id']) && !empty($editors_combo[$_GET['editor_id']])) {
    //     $editor_id = $_GET['editor_id'];
    //     $filters_params['equal']['editor'] = $editor_id;
    //     $show_filters = true;
    // }

    // if (!empty($_GET['singer_id']) && !empty($singers_combo[$_GET['singer_id']])) {
    //     $singer_id = $_GET['singer_id'];
    //     $filters_params['like']['singer'] = $singer_id;
    //     $show_filters = true;
    // }

    // if (!empty($_GET['author_id']) && Authors::getName($_GET['author_id'])) {
    //     $author_id = $_GET['author_id'];
    //     $filters_params['like']['author'] = Authors::getName($author_id);
    //     $show_filters = true;
    // }

    // if (!empty($_GET['compositor_id']) && Authors::getName($_GET['compositor_id'])) {
    //     $compositor_id = $_GET['compositor_id'];
    //     $filters_params['like']['compositor'] = Authors::getName($compositor_id);
    //     $show_filters = true;
    // }

    // if (!empty($_GET['adaptator_id']) && Authors::getName($_GET['adaptator_id'])) {
    //     $adaptator_id = $_GET['adaptator_id'];
    //     $filters_params['like']['adaptator'] = Authors::getName($adaptator_id);
    //     $show_filters = true;
    // }

    if (!empty($_GET['sortby_song'])) {
        $sortby_song = $_GET['sortby_song'];
        $filters_params['sortby'] = $sortby_song;
        $show_filters = true;
    }

    if (!empty($_GET['order_song'])) {
        $filters_params['orderby'] = $_GET['order_song'];
        $show_filters = true;
    }

    try {
        $songs_counter = $song_manager->getCountList($filters_params);
        $songs_list = new adminSongsList($core, $song_manager->getList($filters_params, $limit_songs), $songs_counter);
        $songs_list->setPluginUrl("$p_url&amp;object=song&amp;action=edit&amp;id=%d");
        $songs_list->setAlbumUrl("$p_url&amp;object=album&amp;action=edit&amp;id=%d");
        $songs_list->setPersonUrl("$p_url&amp;object=person&amp;action=edit&amp;id=%d");
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }

    include(dirname(__FILE__).'/../views/songs.tpl');
}
