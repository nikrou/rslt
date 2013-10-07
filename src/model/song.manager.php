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

class songManager extends objectManager
{
    public static $fields = array('title', 'publication_date', 'author', 'compositor', 'adaptator',
    'singer', 'editor', 'original_title', 'url');

    public static $require_fields = array('title', 'publication_date', 'author', 'singer');

    public function __construct($core) {
        parent::__construct($core, 'song', self::$require_fields, self::$fields);
    }

    public function getEditors() {
        return $this->getElements('editor');
    }

    public function getSingers() {
        return $this->getElements('singer');
    }

    protected function getElements($field) {
        $strReq =  'SELECT distinct('.$field.')';
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
      
        if (!empty($limit)) {
			$strReq .= $this->con->limit($limit);
        }

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;
    }
}
