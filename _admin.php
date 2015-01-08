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

if (!defined('DC_CONTEXT_ADMIN')) { return; }

$core->blog->settings->addNameSpace('rslt');

if ($core->blog->settings->rslt->active) {
    // add metadata
    $core->addBehavior('adminPostHeaders', array('rsltAdminBehaviors', 'adminPostHeaders'));
    if ($core->hasBehavior('adminPostFormItems')) {
        $core->addBehavior('adminPostFormItems', array('rsltAdminBehaviors', 'adminPostFormItems'));
    } else {
        // may be deprecated
        $core->addBehavior('adminPostFormSidebar', array('rsltAdminBehaviors', 'adminPostFormSidebar'));
    }
    $core->addBehavior('adminAfterPostUpdate', array('rsltAdminBehaviors', 'adminAfterPostUpdate'));
    $core->addBehavior('adminAfterPostCreate', array('rsltAdminBehaviors', 'adminAfterPostCreate'));
    $core->addBehavior('adminPageHTMLHead', array('rsltAdminBehaviors', 'adminPageHTMLHead'));

    $core->addBehavior('adminDashboardFavorites',array('rsltDashboard','adminDashboardFavorites'));

    $show_in_menu = true;
} else {
    $show_in_menu = false;
}

$_menu['RSLT'] = new dcMenu('rslt-menu', 'RSLT');
$_menu['RSLT']->addItem(
    __('News'),
    'plugin.php?p=rslt&object=news',
    'images/menu/themes.png',
    preg_match('/plugin.php\?p=rslt&object=news/', $_SERVER['REQUEST_URI']),
    $show_in_menu && $core->auth->check('admin,contentadmin', $core->blog->id)
);
$_menu['RSLT']->addItem(
    __('Albums'),
    'plugin.php?p=rslt&object=album',
    'images/menu/themes.png',
    preg_match('/plugin.php\?p=rslt&object=album/', $_SERVER['REQUEST_URI']),
    $show_in_menu && $core->auth->check('admin,contentadmin', $core->blog->id)
);
$_menu['RSLT']->addItem(
    __('Songs'),
    'plugin.php?p=rslt&object=song',
    'images/menu/themes.png',
    preg_match('/plugin.php\?p=rslt&object=song/', $_SERVER['REQUEST_URI']),
    $show_in_menu && $core->auth->check('admin,contentadmin', $core->blog->id)
);
$_menu['RSLT']->addItem(
    __('Persons'),
    'plugin.php?p=rslt&object=person',
    'images/menu/users.png',
    preg_match('/plugin.php\?p=rslt&object=person/', $_SERVER['REQUEST_URI']),
    $show_in_menu && $core->auth->check('admin,contentadmin', $core->blog->id)
);
$_menu['RSLT']->addItem(
    __('Settings'),
    'plugin.php?p=rslt&page=settings',
    'images/menu/blog-pref.png',
    preg_match('/plugin.php\?p=rslt&page=settings/', $_SERVER['REQUEST_URI']),
    $core->auth->check('admin,contentadmin', $core->blog->id)
);
