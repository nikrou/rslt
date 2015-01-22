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

$page_url = $p_url.'&page=synchronize';

$song_manager = new songManager($core);
$album_manager = new albumManager($core);
$person_manager = new personManager($core);
$meta_manager = new metaManager($core);

$rs = $meta_manager->getListFor('album');
$meta = array();
$ref_id = $rs->ref_id;
while ($rs->fetch()) {
    if ($rs->ref_id == $ref_id) {
        $meta['singer'][] = array('id' => $rs->id, 'title' => $rs->title);
    } else {
        $album_manager->updateMeta($ref_id, $meta);

        $meta = array();
        $ref_id = $rs->ref_id;
        $meta['singer'][] = array('id' => $rs->id, 'title' => $rs->title);
    }
}

$rs = $meta_manager->getListFor('song');
$meta = array();
$ref_id = $rs->ref_id;
while ($rs->fetch()) {
    $meta_type = str_replace('song:', '', $rs->meta_type);
    if ($rs->ref_id == $ref_id) {
        $meta[$meta_type][] = array('id' => $rs->id, 'title' => $rs->title);
    } else {
        $song_manager->updateMeta($ref_id, $meta);

        $meta = array();
        $ref_id = $rs->ref_id;
        $meta[$meta_type][] = array('id' => $rs->id, 'title' => $rs->title);
    }
}
