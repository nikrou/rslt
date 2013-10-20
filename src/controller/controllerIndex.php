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
$rslt_albums_service = sprintf('%s&object=album', $p_url);

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
        
        $_SESSION['rslt_message'] = __('The configuration has been updated.');
        $_SESSION['rslt_default_tab'] = 'settings';
        http::redirect($p_url);
    } catch(Exception $e) {
        $core->error->add($e->getMessage());
    }
}

/* albums */
/* pagination */
$album_manager = new albumManager($core);

$active_albums_filters = false;
$filters_params = array();
$publication_date_combo = rsltAdminCombo::makeCombo($album_manager->getPublicationDate(), 'publication_date');
$sortby_album = $order_album = null;
$sortby_albums_combo = array('' => '', __('Title') => 'title', __('Publication date') => 'publication_date',
__('Singer') => 'singer');
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

if (!empty($_GET['aq'])) {
    $aq = $_GET['aq'];
    $filters_params['like']['q'] = $aq;
    $active_albums_filters = true;
}

if (!empty($_GET['publication_date_id']) && !empty($publication_date_combo[$_GET['publication_date_id']])) {
    $publication_date_id = $_GET['publication_date_id'];
    $filters_params['equal']['publication_date'] = $publication_date_id;
    $active_albums_filters = true;
}

if (!empty($_GET['sortby_album'])) {
    $sortby_album = $_GET['sortby_album'];
    $filters_params['sortby'] = $sortby_album;
    $active_songs_filters = true;
}

if (!empty($_GET['order_album'])) {
    $order_album = $_GET['order_album'];
    $filters_params['orderby'] = $order_album;
    $active_songs_filters = true;
}

try {
    $albums_counter = $album_manager->getCountList($filters_params);
    $albums_list = new adminAlbumsList($core, $album_manager->getList($filters_params, $limit_albums), $albums_counter);
    $albums_list->setPluginUrl("$p_url&amp;object=album&amp;action=edit&amp;id=%d");
} catch (Exception $e) {
    $core->error->add($e->getMessage());
}

/* songs */
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

/* filters */
$active_songs_filters = false;
$song_manager = new songManager($core);
$author_id = $compositor_id = $adaptator_id = $singer_id = $editor_id = $publication_date = null;
$sortby_song = $order_song = null;
$filters_params = array();
$sq= '';

$authors_combo = array_merge(array('' => ''), array_flip(Authors::getAll()));
$compositors_combo = array_merge(array('' => ''), array_flip(Authors::getAll()));
$adaptators_combo = array_merge(array('' => ''), array_flip(Authors::getAll()));
$editors_combo = rsltAdminCombo::makeCombo($song_manager->getEditors(), 'editor');
$singers_combo = rsltAdminCombo::makeComboSinger($song_manager->getSingers(), 'singer');
$sortby_songs_combo = array('' => '', __('Title') => 'title', __('Singer') => 'singer', __('Publication date') => 'publication_date',
__('Author') => 'author');

if (!empty($_GET['sq'])) {
    $sq = $_GET['sq'];
    $filters_params['like']['q'] = $sq;
    $active_songs_filters = true;
}

if (!empty($_GET['editor_id']) && !empty($editors_combo[$_GET['editor_id']])) {
    $editor_id = $_GET['editor_id'];
    $filters_params['equal']['editor'] = $editor_id;
    $active_songs_filters = true;
}

if (!empty($_GET['singer_id']) && !empty($singers_combo[$_GET['singer_id']])) {
    $singer_id = $_GET['singer_id'];
    $filters_params['like']['singer'] = $singer_id;
    $active_songs_filters = true;
}

if (!empty($_GET['author_id']) && !empty(Authors::getName($_GET['author_id']))) {
    $author_id = $_GET['author_id'];
    $filters_params['like']['author'] = Authors::getName($author_id);
    $active_songs_filters = true;
}

if (!empty($_GET['compositor_id']) && !empty(Authors::getName($_GET['compositor_id']))) {
    $compositor_id = $_GET['compositor_id'];
    $filters_params['like']['compositor'] = Authors::getName($compositor_id);
    $active_songs_filters = true;
}

if (!empty($_GET['adaptator_id']) && !empty(Authors::getName($_GET['adaptator_id']))) {
    $adaptator_id = $_GET['adaptator_id'];
    $filters_params['like']['adaptator'] = Authors::getName($adaptator_id);
    $active_songs_filters = true;
}

if (!empty($_GET['sortby_song'])) {
    $sortby_song = $_GET['sortby_song'];
    $filters_params['sortby'] = $sortby_song;
    $active_songs_filters = true;
}

if (!empty($_GET['order_song'])) {
    $filters_params['orderby'] = $_GET['order_song'];
    $active_songs_filters = true;
}

try {
    $songs_counter = $song_manager->getCountList($filters_params);
    $songs_list = new adminSongsList($core, $song_manager->getList($filters_params, $limit_songs), $songs_counter);
    $songs_list->setPluginUrl("$p_url&amp;object=song&amp;action=edit&amp;id=%d");
    $songs_list->setAlbumUrl("$p_url&amp;object=album&amp;action=edit&amp;id=%d");
} catch (Exception $e) {
    $core->error->add($e->getMessage());
}

if ($rslt_active) {
    if (!empty($_SESSION['rslt_default_tab'])) {
        $default_tab = $_SESSION['rslt_default_tab'];
        unset($_SESSION['rslt_default_tab']);
    } else {
        $default_tab = 'settings';
    }
} else {
    $default_tab = 'settings';
}

include(dirname(__FILE__).'/../views/index.tpl');

