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

/* filters */
$active_filters = false;
$song_manager = new songManager($core);

$author_id = $compositor_id = $adaptator_id = $singer_id = $editor_id = $publication_date = null;
$sortby = $order = null;
$filters_params = array();

$authors_combo = array_merge(array('' => ''), array_flip($Authors));
$compositors_combo = array_merge(array('' => ''), array_flip($Authors));
$adaptators_combo = array_merge(array('' => ''), array_flip($Authors));
$editors_combo = rsltAdminCombo::makeCombo($song_manager->getEditors(), 'editor');
$singers_combo = rsltAdminCombo::makeCombo($song_manager->getSingers(), 'singer');
$sortby_combo = array();
$order_combo = array();

if (!empty($_GET['editor_id']) && !empty($editors_combo[$_GET['editor_id']])) {
    $editor_id = $_GET['editor_id'];
    $filters_params['editor'] = $editor_id;
    $active_filters = true;
}

if (!empty($_GET['singer_id']) && !empty($singers_combo[$_GET['singer_id']])) {
    $singer_id = $_GET['singer_id'];
    $filters_params['singer'] = $singer_id;
    $active_filters = true;
}

if (!empty($_GET['author_id']) && !empty($Authors[$_GET['author_id']])) {
    $author_id = $_GET['author_id'];
    $filters_params['author'] = $Authors[$author_id];
    $active_filters = true;
}

if (!empty($_GET['compositor_id']) && !empty($Authors[$_GET['compositor_id']])) {
    $compositor_id = $_GET['compositor_id'];
    $filters_params['compositor'] = $Authors[$compositor_id];
    $active_filters = true;
}

if (!empty($_GET['adaptator_id']) && !empty($Authors[$_GET['adaptator_id']])) {
    $adaptator_id = $_GET['adaptator_id'];
    $filters_params['adaptator'] = $Authors[$adaptator_id];
    $active_filters = true;
}

try {
    $songs_counter = $song_manager->getCountList($filters_params);
    $songs_list = new adminSongsList($core, $song_manager->getList($filters_params, $limit), $songs_counter);
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
