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

class Authors
{
    protected static $list = array(1 => 'Gildas Arzel', 'Erick Benzi', 'Jacques Veneruso');

    public static function getAll() {
        return self::$list;
    }

    public static function getAuthorId($name) {
        return array_search($name, self::$list);
    }

   public static function getName($id) {
        return self::$list[$id];
    }

    public static function getAuthorURL($name) {
        return text::tidyURL($name, false);
    }

    public static function getAuthorFromURL($author_url) {
        foreach (self::$list as $author) {
            if (text::tidyURL($author)==$author_url) {
                return $author;
            }
        }

        return '';
    }

    public static function getSongData($rs_song) {
        $subject = $rs_song->author .' '. $rs_song->compositor .' '. $rs_song->adaptator;
        $pattern = '`('.implode('|', self::$list).')`';
        $res = '';

        if (preg_match_all($pattern, $subject, $matches)) {
            $res = implode(' ', array_map(array('self', 'getAuthorURL'), array_unique($matches[1])));
        }

        return $res;
    }
}