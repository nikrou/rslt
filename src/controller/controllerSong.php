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

if (!empty($_REQUEST['id']) || (!empty($_GET['action']) && (in_array($_GET['action'], array('edit', 'add'))))) {
    if (!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    $page_title = __('New song');
    $song = array('title' => '', 'author' => '', 'compositor' => '', 'adaptator' => '',
                  'singer' => '', 'publication_date' => '', 'editor' => '', 'other_editor' => '', 'url' => '');

    $song_manager = new songManager($core);

    if (!empty($_POST['songs']) && ($_POST['object']=='song')
        && ($_POST['action']=='associate_to_album') && !empty($_POST['album_id'])) {
        $album_id = (int) $_POST['album_id'];
        $album_song_manager = new albumSong($core);

        try {
            foreach ($_POST['songs'] as $song_id) {
                $album_song_manager->add($album_id, $song_id);
            }
            $_SESSION['rslt_message'] = __('The song has been associated to album.',
                                           'The songs have been associated to album.', count($_POST['songs']));
            $_SESSION['rslt_default_tab'] = 'songs';
            http::redirect($p_url);
        } catch (Exception $e) {
            $core->error->add($e->getMessage());
        }
    }

    if (($action=='delete') && !empty($_POST['songs']) && $_POST['object']=='song') {
        $song_manager->delete($_POST['songs']);
        $_SESSION['rslt_message'] = __('The song has been deleted.',
                                       'The songs have been deleted.', count($_POST['songs']));
        $_SESSION['rslt_default_tab'] = 'songs';
        http::redirect($p_url);
    }

    if (($action=='edit') && !empty($_GET['id'])) {
        $rs = $song_manager->findById($_GET['id']);
        if (!$rs->isEmpty()) {
            $song['title'] = $rs->title;
            $song['author'] = $rs->author;
            $song['compositor'] = $rs->compositor;
            $song['adaptator'] = $rs->adaptator;
            $song['singer'] = $rs->singer;
            $song['editor'] = $rs->editor;
            $song['other_editor'] = $rs->other_editor;
            $song['url'] = $rs->url;
            $song['publication_date'] = $rs->publication_date;
            $_SESSION['song_id'] = $_GET['id'];
        }
    }

    if (!empty($_POST['save_song'])) {
        $cur = $song_manager->openCursor();
        $cur->title = (string) $_POST['song_title'];
        $cur->author = (string) $_POST['song_author'];
        $cur->compositor = (string) $_POST['song_compositor'];
        $cur->adaptator = (string) $_POST['song_adaptator'];
        $cur->singer = (string) $_POST['song_singer'];
        $cur->editor = (string) $_POST['song_editor'];
        $cur->other_editor = (string) $_POST['song_other_editor'];
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
            $_SESSION['rslt_default_tab'] = 'songs';
            http::redirect($p_url);
        } catch (Exception $e) {
            $core->error->add($e->getMessage());
        }
    }

    include(dirname(__FILE__).'/../views/form_song.tpl');
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
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }

    include(dirname(__FILE__).'/../views/songs.tpl');
}