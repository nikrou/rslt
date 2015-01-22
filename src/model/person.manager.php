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

class personManager extends objectManager
{
    public static $fields = array('title', 'url', 'first_name', 'last_name');
    public static $require_fields = array('title');

    public function __construct($core) {
        parent::__construct($core, 'person', self::$require_fields, self::$fields);
    }

    public function searchByTitle($q) {
        $strReq =  'SELECT id, url, '.implode(',', $this->object_fields);
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
        $strReq .= ' AND title like \'%'.$this->con->escape($q).'%\'';

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();

        return $rs;
    }
}