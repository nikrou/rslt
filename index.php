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

if (!empty($_SESSION['rslt_message'])) {
    $message = $_SESSION['rslt_message'];
    unset($_SESSION['rslt_message']);
}

$is_super_admin = $core->auth->isSuperAdmin();
$core->blog->settings->addNameSpace('rslt');
$rslt_active = $core->blog->settings->rslt->active;
$rslt_albums_prefix = $core->blog->settings->rslt->albums_prefix;
$rslt_album_prefix = $core->blog->settings->rslt->album_prefix;
$rslt_song_prefix = $core->blog->settings->rslt->song_prefix;

$Authors = array(1 => 'Gildas Arzel', 'Erick Benzi', 'Jacques Veneruso');

$Actions = array('add', 'edit');
$Objects = array('album', 'song');

// default controller
$controller_name = 'controllerConfig.php';

if ((!empty($_REQUEST['action']) && in_array($_REQUEST['action'], $Actions))
|| !empty($_POST['do_remove'])
&& !empty($_REQUEST['object']) && in_array($_REQUEST['object'], $Objects)) {

    if (!empty($_POST['do_remove'])) {
        $action = 'remove';
    } else {
        $action = $_REQUEST['action'];
    }
    $controller_name = sprintf('controller%s.php', ucfirst($_REQUEST['object']));
} elseif (!empty($_POST['action']) && ($_POST['action']=='load') && !empty($_POST['file'])) {
    $controller_name = 'controllerLoad.php';
}

include(dirname(__FILE__).'/src/controller/'.$controller_name);
