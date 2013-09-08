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
$Tabs = array();
$Tabs['settings'] = array('label' => __('Settings'), 'class' => '');
$Tabs['about'] = array('label' => __('About'), 'class' => '');

$core->blog->settings->addNameSpace('rslt');
$rslt_active = $core->blog->settings->rslt->active;
$is_super_admin = $core->auth->isSuperAdmin();

if ($rslt_active) {
  $default_tab = 'authors';

  $Tabs['settings']['class'] = 'pull-right';
  $Tabs['about']['class'] = 'pull-right';

  $Tabs['authors'] = array('label' =>  __('Authors'), 'class' => '');
  $Tabs['albums'] = array('label' =>  __('Albums'), 'class' => '');
  $Tabs['songs'] = array('label' => __('Songs'), 'class' => '');
}

if (!empty($_POST['saveconfig'])) {
  try {
    $rslt_active = (empty($_POST['rslt_active']))?false:true;
    $core->blog->settings->rslt->put('active', $rslt_active, 'boolean');

    $_SESSION['rslt_message'] = __('Configuration successfully updated.');
    http::redirect($p_url);
  } catch(Exception $e) {
    $core->error->add($e->getMessage());
  }
}

/* authors */
try {
  $author_manager = new authorManager($core);
  $authors = $author_manager->getList();
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

/* albums */
try {
  $album_manager = new albumManager($core);
  $albums = $album_manager->getList();
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

/* songs */
try {
  $song_manager = new songManager($core);
  $songs = $song_manager->getList();
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

$Tabs[$default_tab]['class'] .= ' part-tabs-active';

include(dirname(__FILE__).'/../views/index.tpl');
