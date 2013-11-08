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

if (!defined('DC_CONTEXT_ADMIN')) { return; }

$core->addBehavior('adminDashboardFavorites',array('rsltDashboard','adminDashboardFavorites'));

$_menu['Blog']->addItem('RSLT',
'plugin.php?p=rslt',
'index.php?pf=rslt/imgs/icon.png',
preg_match('/plugin.php\?p=rslt/', $_SERVER['REQUEST_URI']),
$core->auth->check('admin,contentadmin', $core->blog->id)
);

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
