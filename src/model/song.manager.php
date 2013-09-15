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
    public static $fields = array('title', 'publication_date', 'author', 'singer');

    public function __construct($core) {
        parent::__construct($core, 'song', self::$fields);
    }

    public function add($object) {
        foreach (self::$fields as $field) {
            if (empty($object[$field])) {
                throw new Exception(sprintf(__('You must provide %s field', $field)));
            }
        }
        
        $cur = $this->con->openCursor($this->table);
        $cur->blog_id = (string) $this->blog->id;

        foreach (self::$fields as $field) {
            if ($field=='publication_date') {
                $cur->$field = (int) $object[$field];
            } else {
                $cur->$field = $object[$field];
            }
        }
        
        $strReq = 'SELECT MAX(id) FROM '.$this->table;
        $rs = $this->con->select($strReq);
        $cur->id = (int) $rs->f(0) + 1;
        
        $cur->insert();
        $this->blog->triggerBlog();

        return $cur;
    }
}
