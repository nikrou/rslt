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

if (!defined('DC_CONTEXT_MODULE')) { return; }

$core->blog->settings->addNameSpace('rslt');
$is_super_admin = $core->auth->isSuperAdmin();
$rslt_active = $core->blog->settings->rslt->active;
$rslt_albums_prefix = $core->blog->settings->rslt->albums_prefix;
$rslt_album_prefix = $core->blog->settings->rslt->album_prefix;
$rslt_song_prefix = $core->blog->settings->rslt->song_prefix;

if (!empty($_POST['save'])) {
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
        
		dcPage::addSuccessNotice(__('Configuration successfully updated.'));
		http::redirect($list->getURL('module=rslt&conf=1'));
    } catch(Exception $e) {
        $core->error->add($e->getMessage());
    }
}

include(dirname(__FILE__).'/src/views/config.tpl');
