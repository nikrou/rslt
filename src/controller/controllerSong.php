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

$page_title = __('New song');
$song = array('title' => '', 'author' => '', 'compositor' => '', 'adaptator' => '',
'singer' => '', 'publication_date' => '', 'editor' => '', 'url' => '');

$song_manager = new songManager($core);

if (!empty($_POST['songs']) && ($_POST['object']=='song') && ($_POST['action']=='associate_to_album') && !empty($_POST['album_id'])) {
    $album_id = (int) $_POST['album_id'];
    $album_song_manager = new albumSong($core);

	try {
        foreach ($_POST['songs'] as $song_id) {
            $album_song_manager->add($album_id, $song_id);
        }
        $_SESSION['rslt_message'] = __('The song has been successfully added to album.',
        'The songs have been successfully added to album.', count($_POST['songs']));
        $_SESSION['rslt_default_tab'] = 'songs';
        http::redirect($p_url);
    } catch (Exception $e) {
		$core->error->add($e->getMessage());
    }
}

if (($action=='delete') && !empty($_POST['songs']) && $_POST['object']=='song') {
    $song_manager->delete($_POST['songs']);
    $_SESSION['rslt_message'] = __('The song has been successfully deleted.', 
    'The songs have been successfully deleted.', count($_POST['songs']));
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
    $cur->publication_date = (int) $_POST['song_publication_date'];
    $cur->url = (string) $_POST['song_url'];
 
    try {
        if ($action=='edit') {
            $song_manager->update($_SESSION['song_id'], $cur);
            $message = __('Song has been successfully updated.');
            unset($_SESSION['song_id']);
        } else {
            $song_manager->add($cur);
            $message = __('Song has been successfully added.');
        }
        $_SESSION['rslt_message'] = $message;
        $_SESSION['rslt_default_tab'] = 'songs';
        http::redirect($p_url);
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }
}

include(dirname(__FILE__).'/../views/form_song.tpl');
