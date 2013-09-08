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

$Actions = array('add', 'edit');
$Objects = array('album', 'author', 'song');


if ((!empty($_REQUEST['action']) && in_array($_REQUEST['action'], $Actions))
    || !empty($_POST['do_remove'])
    && !empty($_REQUEST['object']) && in_array($_REQUEST['object'], $Objects)) {

  if (!empty($_POST['do_remove'])) {
    $action = 'remove';
  } else {
    $action = $_REQUEST['action'];
  }

  $controller_name = sprintf('controller%s.php', ucfirst($_REQUEST['object']));

  include(dirname(__FILE__).'/src/controller/'.$controller_name);
} else {
  include(dirname(__FILE__).'/src/controller/config.php');
}
