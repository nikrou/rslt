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

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_GET['q'])) {
    header('Content-type: application/json');
    $person_manager = new personManager($core);
    $persons = $person_manager->searchByName($_GET['q']);
    $response = array();
    while ($persons->fetch()) {
        $response[] = array('id' => $persons->id, 'name' => $persons->name);
    }
    echo json_encode($response);
    exit();
} else {
    $person_manager = new personManager($core);
    $person_list = $person_manager->getList();

    include(dirname(__FILE__).'/../views/persons.tpl');
}
