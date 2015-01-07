<?php
// +-----------------------------------------------------------------------+
// | RSLT - a plugin for dotclear                                          |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2013-2015 Nicolas Roudaire        http://www.nikrou.net  |
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
$rslt_prefix['albums'] = $core->blog->settings->rslt->prefix_albums;
$rslt_prefix['album'] = $core->blog->settings->rslt->prefix_album;
$rslt_prefix['song'] = $core->blog->settings->rslt->prefix_song;
$rslt_directory['albums'] = $core->blog->settings->rslt->directory_albums;
$rslt_directory['bios'] = $core->blog->settings->rslt->directory_bios;
$rslt_directory['supports'] = $core->blog->settings->rslt->directory_supports;

$Pages = array('settings');

// default controller
$controller_name = 'controllerSettings.php';

if (!empty($_REQUEST['page']) && in_array($_REQUEST['page'], $Pages)) {
    $controller_name = sprintf('controller%s.php', ucfirst($_REQUEST['page']));
}

include(dirname(__FILE__).'/src/controller/'.$controller_name);


// $Actions = array('add', 'edit');
// if (is_callable('tweakUrls::tweakBlogURL')) {
//     $Actions[] = 'cleanurls';
// }
// $Objects = array('album', 'song', 'people');

// $rslt_person_service = sprintf('%s&object=person', $p_url);


// if (!empty($_POST['saveconfig'])) {
//     $controller_name = 'controllerConfig.php';
// } elseif (!empty($_REQUEST['object']) && $_REQUEST['object']=='person' && !empty($_GET['q'])) {
//     $controller_name = sprintf('controller%s.php', ucfirst($_REQUEST['object']));
// } elseif (!empty($_POST['action']) && ($_POST['action']=='load') && !empty($_POST['file'])) {
//     $controller_name = 'controllerLoad.php';
// } elseif (!empty($_REQUEST['object']) && in_array($_REQUEST['object'], $Objects)) {
//     if (!empty($_REQUEST['action'])) {
//         $action = $_REQUEST['action'];
//     }
//     $controller_name = sprintf('controller%s.php', ucfirst($_REQUEST['object']));
// }
