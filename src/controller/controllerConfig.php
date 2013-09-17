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

$default_tab = 'settings';

if (!empty($_POST['saveconfig'])) {
  try {
    $rslt_active = (empty($_POST['rslt_active']))?false:true;
    $core->blog->settings->rslt->put('active', $rslt_active, 'boolean');

    if (!empty($_POST['rslt_albums_prefix'])) {
        $rslt_albums_prefix = trim($_POST['rslt_albums_prefix']);
        $core->blog->settings->rslt->put('albums_prefix', $rslt_albums_prefix, 'string');
    } 
    if (!empty($_POST['rslt_album_prefix'])) {
        $rslt_album_prefix = trim($_POST['rslt_album_prefix']);
        $core->blog->settings->rslt->put('album_prefix', $rslt_album_prefix, 'string');
    } 
    if (!empty($_POST['rslt_song_prefix'])) {
        $rslt_song_prefix = trim($_POST['rslt_song_prefix']);
        $core->blog->settings->rslt->put('song_prefix', $rslt_song_prefix, 'string');
    } 

    $_SESSION['rslt_message'] = __('Configuration successfully updated.');
    $_SESSION['rslt_default_tab'] = 'settings';
    http::redirect($p_url);
  } catch(Exception $e) {
    $core->error->add($e->getMessage());
  }
}

/* pagination */
$page = !empty($_GET['page']) ? (integer) $_GET['page'] : 1;
$nb_per_page =  10;

if (!empty($_GET['nb']) && (integer) $_GET['nb'] > 0) {
  $nb_per_page = (integer) $_GET['nb'];
}
$limit = array((($page-1)*$nb_per_page), $nb_per_page);

/* albums */
$albums_action_combo = array();
$albums_action_combo[''] = null;
$albums_action_combo[__('Delete')] = 'delete';

try {
  $album_manager = new albumManager($core);
  $albums_counter = $album_manager->getCountList();
  $albums_list = new adminAlbumsList($core, $album_manager->getList($limit), $albums_counter);
  $albums_list->setPluginUrl("$p_url&amp;object=album&amp;action=edit&amp;id=%d");
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

/* songs */
$songs_action_combo = array();
$songs_action_combo[''] = null;
$songs_action_combo[__('Delete')] = 'delete';

try {
  $song_manager = new songManager($core);
  $songs_counter = $song_manager->getCountList();
  $songs_list = new adminSongsList($core, $song_manager->getList($limit), $songs_counter);
  $songs_list->setPluginUrl("$p_url&amp;object=song&amp;action=edit&amp;id=%d");
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

if ($rslt_active) {
  if (!empty($_SESSION['rslt_default_tab'])) {
    $default_tab = $_SESSION['rslt_default_tab'];
    unset($_SESSION['rslt_default_tab']);
  } else {
    $default_tab = 'authors';
  }
} else {
  $default_tab = 'settings';
}

include(dirname(__FILE__).'/../views/index.tpl');
