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

if (!empty($_POST['saveconfig'])) {
    try {
        $rslt_active = (empty($_POST['rslt_active']))?false:true;
        $core->blog->settings->rslt->put('active', $rslt_active, 'boolean');
        
        if (!empty($_POST['rslt_prefix_albums'])) {
            $rslt_prefix_albums = trim($_POST['rslt_prefix_albums']);
            $core->blog->settings->rslt->put('prefix_albums', $rslt_prefix_albums, 'string');
        }
        if (!empty($_POST['rslt_prefix_album'])) {
            $rslt_prefix_album = trim($_POST['rslt_prefix_album']);
            $core->blog->settings->rslt->put('prefix_album', $rslt_prefix_album, 'string');
        }
        if (!empty($_POST['rslt_prefix_song'])) {
            $rslt_prefix_song = trim($_POST['rslt_prefix_song']);
            $core->blog->settings->rslt->put('prefix_song', $rslt_prefix_song, 'string');
        }

        if (!empty($_POST['rslt_directory_albums'])) {
            $rslt_directory_albums = trim($_POST['rslt_directory_albums']);
            $core->blog->settings->rslt->put('directory_albums', $rslt_directory_albums, 'string');
        }
        if (!empty($_POST['rslt_directory_bios'])) {
            $rslt_directory_bios = trim($_POST['rslt_directory_bios']);
            $core->blog->settings->rslt->put('directory_bios', $rslt_directory_bios, 'string');
        }
        if (!empty($_POST['rslt_directory_supports'])) {
            $rslt_directory_supports = trim($_POST['rslt_directory_supports']);
            $core->blog->settings->rslt->put('directory_supports', $rslt_directory_supports, 'string');
        }
        
        $_SESSION['rslt_message'] = __('The configuration has been updated.');
        $_SESSION['rslt_default_tab'] = 'settings';
        http::redirect($p_url);
    } catch(Exception $e) {
        $_SESSION['rslt_message'] = $e->getMessage();
        $_SESSION['rslt_default_tab'] = 'settings';
        http::redirect($p_url);
    }
}
