<?php
// +-----------------------------------------------------------------------+
// | RSLT - a plugin for dotclear                                          |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2013 Nicolas Roudaire             http://www.nikrou.net  |
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

$page_title = __('New album');
$album = array('title' => '', 'singer' => '', 'url' => '', 'publication_date' => '');

$action = 'edit';
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

if (!empty($_POST['remove']) && !empty($_POST['songs']) && !empty($_POST['album_id'])) {
    $album_song_manager = new albumSong($core);
    try {
        $album_song_manager->removeFromAlbum($_POST['album_id'], $_POST['songs']);
        $_SESSION['rslt_default_tab'] = 'albums';
        $_SESSION['rslt_message'] = __('The song has been removed from album.', 
        'The songs have been removed from album', count($_POST['songs'])
        ); 
        http::redirect($p_url);
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
        $_SESSION['rslt_default_tab'] = 'albums';
        http::redirect($p_url);
    } catch (Exception $e) {
		$core->error->add($e->getMessage());
    }
}

if (($action=='delete') && !empty($_POST['albums']) && $_POST['object']=='album') {
    $album_manager->delete($_POST['albums']);
    $_SESSION['rslt_message'] = __('The album has been successfully deleted.', 
    'The albums have been successfully deleted.', count($_POST['albums']));
    $_SESSION['rslt_default_tab'] = 'albums';
    http::redirect($p_url);
}

if (($action=='edit') && !empty($_GET['id'])) {
    $page_title = __('Edit album');

    $rs = $album_manager->findById($_GET['id']);
    if (!$rs->isEmpty()) {
        $album['id'] = (int) $_GET['id'];
        $album['title'] = $rs->title;
        $album['singer'] = $rs->singer;
        $album['publication_date'] = $rs->publication_date;
        $album['url'] = $rs->url;
        $_SESSION['album_id'] = $_GET['id'];
    }

    $songs = $album_manager->getSongs($_GET['id']);
}

if (!empty($_POST['save_album'])) {
    $cur = $album_manager->openCursor();
	$cur->title = (string) $_POST['album_title'];
	$cur->singer = (string) $_POST['album_singer'];
	$cur->publication_date = (string) $_POST['album_publication_date'];
	$cur->url = (string) $_POST['album_url'];

	try {
        if ($action=='edit') {
            $album_manager->update($_SESSION['album_id'], $cur);
            $message = __('Album has been successfully updated.');
            unset($_SESSION['album_id']);
        } else {
            $album_manager->add($cur);
            $message = __('Album has been successfully added.');
        }
		$_SESSION['rslt_message'] = $message;
		$_SESSION['rslt_default_tab'] = 'albums';
		http::redirect($p_url);
	} catch (Exception $e) {
		$core->error->add($e->getMessage());
	}
}

include(dirname(__FILE__).'/../views/form_album.tpl');
