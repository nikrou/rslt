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

$page_url = $p_url.'&object=album';
$rslt_person_service = $p_url.'&object=person';

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_GET['term'])) {
    header('Content-type: application/json');
    $album_manager = new albumManager($core);
    $albums = $album_manager->findByTitle($_GET['term']);
    $response = array();
    while ($albums->fetch()) {
        $response[] = array('label' => $albums->title, 'id' => $albums->id);
    }
    echo json_encode($response);
    exit();
} elseif (!empty($_REQUEST['action']) && (in_array($_REQUEST['action'], array('edit', 'add')))) {
    $action = $_REQUEST['action'];
    $page_title = __('New album');
    $album = array(
        'title' => '', 'singer' => '',
        'url' => '', 'publication_date' => '',
        'media_id' => '', 'bio_express' => ''
    );

    $album_manager = new albumManager($core);
    if (!empty($_GET['id'])) {
        $page_title = __('Edit album');

        $rs = $album_manager->findById($_GET['id']);
        if (!$rs->isEmpty()) {
            $album['id'] = (int) $_GET['id'];
            $album['title'] = $rs->title;
            $album['publication_date'] = $rs->publication_date;
            $album['url'] = $rs->url;
            $album['media_id'] = $rs->media_id;
            $album['bio_express'] = $rs->bio_express;

            $singers = array();
            $singer_ids = array();
            foreach ($rs->getSinger() as $singer) {
                $singers[] = sprintf('{ id:%d, name:"%s" }', $singer['id'], $singer['name']);
                $singer_ids[] = '~~'.$singer['id'].'~~';
            }
            if (!empty($singers)) {
                $singers_string = '['.implode(',', $singers).']';
            }
            $album['singer'] = implode(',', $singer_ids);

            if (!empty($album['media_id'])) {
                $media = new dcMedia($core);
                if (($file = $media->getFile($album['media_id']))!=null) {
                    $album['media_icon'] = $file->media_icon;
                }
            }
            $_SESSION['album_id'] = (int) $_GET['id'];
            $songs = $album_manager->getSongs($_GET['id']);
        } else {
            dcPage::addErrorNotice('That album does not exist.');
            http::redirect($page_url);
        }
    }
    if (!empty($_POST['save_album'])) {
        $cur = $album_manager->openCursor();
        $cur->title = (string) $_POST['album_title'];
        $cur->publication_date = (string) $_POST['album_publication_date'];
        if (isset($_POST['album_url'])) {
            $cur->url = (string) $_POST['album_url'];
        }
        $cur->media_id = (int) $_POST['album_media_id'];
        $cur->bio_express = (string) $_POST['album_bio_express'];

        if (!empty($_POST['album_singer'])) {
            $meta = array();
            $raw_persons = explode(',', $_POST['album_singer']);

            $person_manager = new personManager($core);
            $persons = array();
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
            $cur->meta = array('singer' => $persons);
        }

        try {
            if ($action=='edit') {
                $album_manager->update($_SESSION['album_id'], $cur);
                $message = __('The album has been updated.');
                unset($_SESSION['album_id']);
            } else {
                $cur = $album_manager->add($cur);
                $message = __('The album has been added.');
            }

            $_SESSION['rslt_message'] = $message;
            http::redirect($page_url.'&action=edit&id='.(int) $_POST['id']);
        } catch (Exception $e) {
            $core->error->add($e->getMessage());
        }
    }



    include(dirname(__FILE__).'/../views/form_album.tpl');
} else {
    $album_manager = new albumManager($core);

    $form_filter_title = __('Show filters and display options');
    $show_filters = false;
    $filters_params = array();
    $publication_date_combo = rsltAdminCombo::makeCombo($album_manager->getPublicationDate(), 'publication_date');
    $sortby_album = $order_album = null;
    $sortby_albums_combo = array(
        '' => '',
        __('Title') => 'title',
        __('Publication date') => 'publication_date',
        __('Singer') => 'singer'
    );
    $order_combo = array(__('Descending') => 'DESC', __('Ascending') => 'ASC');
    $aq = $publication_date_id = null;

    $page_albums = !empty($_GET['page_albums']) ? (integer) $_GET['page_albums'] : 1;
    $nb_per_page_albums =  10;

    if (!empty($_GET['nba']) && (integer) $_GET['nba'] > 0) {
        $nb_per_page_albums = (integer) $_GET['nba'];
    }
    $limit_albums = array((($page_albums-1)*$nb_per_page_albums), $nb_per_page_albums);

    $albums_action_combo = array();
    $albums_action_combo[''] = null;
    $albums_action_combo[__('Delete')] = 'delete';
    if (is_callable('tweakUrls::tweakBlogURL')) {
        $albums_action_combo[__('Clean URLs')] = 'cleanurls';
    }

    if (!empty($_GET['aq'])) {
        $aq = $_GET['aq'];
        $filters_params['like']['q'] = $aq;
        $show_filters = true;
    }

    if (!empty($_GET['publication_date_id']) && !empty($publication_date_combo[$_GET['publication_date_id']])) {
        $publication_date_id = $_GET['publication_date_id'];
        $filters_params['equal']['publication_date'] = $publication_date_id;
        $show_filters = true;
    }

    if (!empty($_GET['sortby_album'])) {
        $sortby_album = $_GET['sortby_album'];
        $filters_params['sortby'] = $sortby_album;
        $show_filters = true;
    }

    if (!empty($_GET['order_album'])) {
        $order_album = $_GET['order_album'];
        $filters_params['orderby'] = $order_album;
        $show_filters = true;
    }

    try {
        $albums_counter = $album_manager->getCountList($filters_params);
        $albums_list = new adminAlbumsList($core, $album_manager->getList($filters_params, $limit_albums), $albums_counter);
        $albums_list->setPluginUrl("$p_url&amp;object=album&amp;action=edit&amp;id=%d");
        $albums_list->setPersonUrl("$p_url&amp;object=person&amp;action=edit&amp;id=%d");
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }

    include(dirname(__FILE__).'/../views/albums.tpl');

}

/*

if (!empty($_REQUEST['id']) || (!empty($_REQUEST['action']) && (in_array($_REQUEST['action'], array('edit', 'add'))))) {
    if (!empty($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
    }
    $page_title = __('New album');
    $album = array(
        'title' => '', 'singer' => '',
        'url' => '', 'publication_date' => '',
        'media_id' => '', 'bio_express' => ''
    );

    $songs = null;
    $album_manager = new albumManager($core);

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_GET['term'])) {
        header('Content-type: application/json');
        $albums = $album_manager->findByTitle($_GET['term']);
        $response = array();
        while ($albums->fetch()) {
            $response[] = array('label' => $albums->title, 'id' => $albums->id);
        }
        echo json_encode($response);
        exit();
    }

    if (!empty($_POST['cleanurls']) && !empty($_POST['albums'])) {
    }

    if (!empty($_POST['remove']) && !empty($_POST['songs']) && !empty($_POST['album_id'])) {
        $album_song_manager = new albumSong($core);
        try {
            $album_song_manager->removeFromAlbum($_POST['album_id'], $_POST['songs']);
            $_SESSION['rslt_message'] = __('The song has been removed from album.',
                                           'The songs have been removed from album', count($_POST['songs'])
            );
            http::redirect($page_url);
        } catch (Exception $e) {
            $core->error->add($e->getMessage());
        }

        $_SESSION['rslt_default_tab'] = 'albums';
        http::redirect($p_url);
    }

    if (!empty($_POST['save_order']) && !empty($_POST['position']) && !empty($_POST['album_id'])) {
        $album_song_manager = new albumSong($core);
        try {
            $album_song_manager->updateRanks($_POST['album_id'], $_POST['position']);
            $_SESSION['rslt_message'] = __('The order of songs in album has been saved.');
            http::redirect($page_url);
        } catch (Exception $e) {
            $core->error->add($e->getMessage());
        }
    }

    if (($action=='delete') && !empty($_POST['albums']) && $_POST['object']=='album') {
        $album_manager->delete($_POST['albums']);
        $_SESSION['rslt_message'] = __('The album has been deleted.',
                                       'The albums have been deleted.', count($_POST['albums']));
        http::redirect($page_url);
    }

    if (($action=='edit') && !empty($_GET['id'])) {
        $page_title = __('Edit album');

        $rs = $album_manager->findById($_GET['id']);
        if (!$rs->isEmpty()) {
            $album['id'] = (int) $_GET['id'];
            $album['title'] = $rs->title;
            $album['publication_date'] = $rs->publication_date;
            $album['url'] = $rs->url;
            $album['media_id'] = $rs->media_id;
            $album['bio_express'] = $rs->bio_express;

            if (!empty($album['media_id'])) {
                $media = new dcMedia($core);
                if (($file = $media->getFile($album['media_id']))!=null) {
                    $album['media_icon'] = $file->media_icon;
                }
            }
            $_SESSION['album_id'] = $_GET['id'];
        }

        $songs = $album_manager->getSongs($_GET['id']);
    }

    if (!empty($_POST['save_album'])) {
        $cur = $album_manager->openCursor();
        $cur->title = (string) $_POST['album_title'];
        $cur->publication_date = (string) $_POST['album_publication_date'];
        if (isset($_POST['album_url'])) {
            $cur->url = (string) $_POST['album_url'];
        }
        $cur->media_id = (int) $_POST['album_media_id'];
        $cur->bio_express = (string) $_POST['album_bio_express'];

        try {
            if ($action=='edit') {
                $album_manager->update($_SESSION['album_id'], $cur);
                $message = __('The album has been updated.');
                unset($_SESSION['album_id']);
            } else {
                $cur = $album_manager->add($cur);
                $message = __('The album has been added.');
            }

            $meta_manager = new metaManager($core);

            if (!empty($_POST['album_singer'])) {
                $raw_singers = explode(',', $_POST['album_singer']);

                $person_ids = array();
                foreach ($raw_singers as $raw_singer) {
                    if (preg_match('/^~~(\d+)~~$/', $raw_singer, $matches)) {
                        $person_ids[] = $matches[1];
                    } else {
                        $person_ids[] = $person_manager->add($raw_person);
                    }
                }

                foreach ($person_ids as $person_id) {
                    $meta_id = $meta_manager->add($cur->id, $person_id, 'singer');
                }
            }

            $_SESSION['rslt_message'] = $message;
            http::redirect($page_url);
        } catch (Exception $e) {
            $core->error->add($e->getMessage());
        }
    }

    include(dirname(__FILE__).'/../views/form_album.tpl');
} else {
    $album_manager = new albumManager($core);

    $form_filter_title = __('Show filters and display options');
    $show_filters = false;
    $filters_params = array();
    $publication_date_combo = rsltAdminCombo::makeCombo($album_manager->getPublicationDate(), 'publication_date');
    $sortby_album = $order_album = null;
    $sortby_albums_combo = array(
        '' => '',
        __('Title') => 'title',
        __('Publication date') => 'publication_date',
        __('Singer') => 'singer'
    );
    $order_combo = array(__('Descending') => 'DESC', __('Ascending') => 'ASC');
    $aq = $publication_date_id = null;

    $page_albums = !empty($_GET['page_albums']) ? (integer) $_GET['page_albums'] : 1;
    $nb_per_page_albums =  10;

    if (!empty($_GET['nba']) && (integer) $_GET['nba'] > 0) {
        $nb_per_page_albums = (integer) $_GET['nba'];
    }
    $limit_albums = array((($page_albums-1)*$nb_per_page_albums), $nb_per_page_albums);

    $albums_action_combo = array();
    $albums_action_combo[''] = null;
    $albums_action_combo[__('Delete')] = 'delete';
    if (is_callable('tweakUrls::tweakBlogURL')) {
        $albums_action_combo[__('Clean URLs')] = 'cleanurls';
    }

    if (!empty($_GET['aq'])) {
        $aq = $_GET['aq'];
        $filters_params['like']['q'] = $aq;
        $show_filters = true;
    }

    if (!empty($_GET['publication_date_id']) && !empty($publication_date_combo[$_GET['publication_date_id']])) {
        $publication_date_id = $_GET['publication_date_id'];
        $filters_params['equal']['publication_date'] = $publication_date_id;
        $show_filters = true;
    }

    if (!empty($_GET['sortby_album'])) {
        $sortby_album = $_GET['sortby_album'];
        $filters_params['sortby'] = $sortby_album;
        $show_filters = true;
    }

    if (!empty($_GET['order_album'])) {
        $order_album = $_GET['order_album'];
        $filters_params['orderby'] = $order_album;
        $show_filters = true;
    }

    try {
        $albums_counter = $album_manager->getCountList($filters_params);
        $albums_list = new adminAlbumsList($core, $album_manager->getList($filters_params, $limit_albums), $albums_counter);
        $albums_list->setPluginUrl("$p_url&amp;object=album&amp;action=edit&amp;id=%d");
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }

    include(dirname(__FILE__).'/../views/albums.tpl');
}
*/