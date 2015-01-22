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

class metaManager
{
    public function __construct($core) {
        $this->core = $core;
        $this->blog = $core->blog;
        $this->con = $this->blog->con;
        $this->table = $this->blog->prefix.'rslt_meta';
        $this->table_person = $this->blog->prefix.'rslt_person';
    }

    public function add($id, array $persons, $type) {
        $strReq = 'DELETE FROM '.$this->table;
        $strReq .= ' WHERE ref_id = '.(int) $id;
        $strReq .= ' AND meta_type = \''.$this->con->escape($type).'\'';
        $this->con->execute($strReq);

        $cur = $this->con->openCursor($this->table);
        try {
            foreach ($persons as $person) {
                $cur->ref_id = $id;
                $cur->person_id = $person['id'];
                $cur->meta_type = $type;
                $cur->insert();
            }
        } catch (Exception $e) {
            $this->con->unlock();
            throw $e;
        }
    }

    public function getListFor($prefix) {
        $strReq =  'SELECT ref_id, meta_type, person_id, p.id, p.title';
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' LEFT JOIN '.$this->table_person.' AS p ON person_id = p.id';
        $strReq .= ' WHERE meta_type like \''.$prefix.'%\'';
        $strReq .= ' ORDER BY ref_id';

        $rs = $this->con->select($strReq);

        return $rs;
    }
}