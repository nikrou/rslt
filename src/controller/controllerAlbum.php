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
$album = array('title' => '',
	       );

$album_manager = new albumManager($core);

if (($action=='remove') && !empty($_POST['albums'])
    && $_POST['object']=='album') {

  $album_manager->delete($_POST['albums']);
  $_SESSION['rslt_message'] = __('Album(s) successfully deleted.');
  $_SESSION['rslt_default_tab'] = 'albums';
  http::redirect($p_url);
}

if (($action=='edit') && !empty($_GET['id'])) {
  $rs = $album_manager->findById($_GET['id']);
  if (!$rs->isEmpty()) {
    $album['title'] = $rs->title;
    $_SESSION['album_id'] = $_GET['id'];
  }
}

if (!empty($_POST['save_album'])
    && !empty($_POST['album_title'])) {

  $album['title'] = $_POST['album_title'];

  if ($action=='edit') {
    $method = 'update';
    $message = __('Album has been successfully updated.');
    $album['id'] = $_SESSION['album_id'];
    unset($_SESSION['album_id']);
  } else {
    $method = 'add';
    $message = __('Album has been successfully added.');
  }
  try {
    $album_manager->$method($album);
    $_SESSION['rslt_message'] = $message;
    $_SESSION['rslt_default_tab'] = 'albums';
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

include(dirname(__FILE__).'/../views/form_album.tpl');
