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
$song = array('title' => '', 'author' => '', 'publication_date' => '');

$song_manager = new songManager($core);

if (($action=='remove') && !empty($_POST['songs']) && $_POST['object']=='song') {
    $song_manager->delete($_POST['songs']);
    $_SESSION['rslt_message'] = __('The song has been successfully removed.', 
    'The songs have been successfully removed.', count($_POST['songs']));
    $_SESSION['rslt_default_tab'] = 'songs';
    http::redirect($p_url);
}

if (($action=='edit') && !empty($_GET['id'])) {
  $rs = $song_manager->findById($_GET['id']);
  if (!$rs->isEmpty()) {
    $song['title'] = $rs->title;
    $song['author'] = $rs->author;
    $song['publication_date'] = $rs->publication_date;
    $_SESSION['song_id'] = $_GET['id'];
  }
}

if (!empty($_POST['save_song'])) {
  $song['title'] = $_POST['song_title'];
  $song['author'] = $_POST['song_auhor'];
  $song['publication_date'] = $_POST['song_publication_date'];

  if ($action=='edit') {
    $method = 'update';
    $message = __('Song has been successfully updated.');
    $song['id'] = $_SESSION['song_id'];
    unset($_SESSION['song_id']);
  } else {
    $method = 'add';
    $message = __('Song has been successfully added.');
  }
  try {
    $song_manager->$method($song);
    $_SESSION['rslt_message'] = $message;
    $_SESSION['rslt_default_tab'] = 'songs';
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

include(dirname(__FILE__).'/../views/form_song.tpl');
