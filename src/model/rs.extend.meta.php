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

class rsExtendMeta
{
    public static function getJson($rs, $field) {
        $s = '';

        if ($rs->exists('meta') && !empty($field)) {
            $meta = json_decode($rs->meta, true);
            if (!empty($meta[$field])) {
                $elements = $meta[$field];
                $data = array();
                foreach ($elements as $element) {
                    $data[] = sprintf('{ "id":%d, "name":"%s" }', $element['id'], $element['name']);
                }
                $s = '['.implode(',', $data).']';
            }
        }

        return $s;
    }

    public static function getIds($rs, $field) {
        $s = '';
        if ($rs->exists('meta') && !empty($field)) {
            $meta = json_decode($rs->meta, true);
            if (!empty($meta[$field])) {
                $elements = $meta[$field];
                $ids = array();
                foreach ($elements as $element) {
                    $ids[] = '~~'.$element['id'].'~~';
                }
                $s = implode(',', $ids);
            }
        }

        return $s;
    }


    public static function getSingers($rs) {
        $s = '';

        if ($rs->exists('meta')) {
            $meta = json_decode($rs->meta, true);
            if (!empty($meta['singer'])) {
                $elements = $meta['singer'];
                $data = array();
                foreach ($elements as $element) {
                    $data[] = $element['name'];
                }
                $s = implode(',', $data);
            }
        }

        return $s;
   }
}