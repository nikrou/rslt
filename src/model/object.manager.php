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

class objectManager
{
  public function __construct($core, $object_name, $fields) {
    $this->core = $core;
    $this->blog = $core->blog;
    $this->con = $this->blog->con;
    $this->table = $this->blog->prefix.'rslt_'.$object_name;

    $this->object_name = $object_name;
    $this->fields = $fields;
  }

  public function getById($id) {
    $strReq =  'SELECT '.implode(',', $this->fields);
    $strReq .= ' FROM '.$this->table;
    $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' AND id='.$this->con->escape($id);

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;
  }

  public function getList() {
    $strReq =  'SELECT id, '.implode(',', $this->fields);
    $strReq .= ' FROM '.$this->table;
    $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;
  }

  public function add($object) {
    foreach ($this->fields as $field) {
      if (empty($object[$field])) {
	throw new Exception(sprintf(__('You must provide %s field', $field)));
      }
    }

    $cur = $this->con->openCursor($this->table);
    $cur->blog_id = (string) $this->blog->id;

    foreach ($this->fields as $field) {
      $cur->$field = $object[$field];
    }

    $strReq = 'SELECT MAX(id) FROM '.$this->table;
    $rs = $this->con->select($strReq);
    $cur->id = (int) $rs->f(0) + 1;

    $cur->insert();
    $this->blog->triggerBlog();
  }

  public function update($object) {
    foreach ($this->fields as $field) {
      if (empty($object[$field])) {
	throw new Exception(sprintf(__('You must provide %s field', $field)));
      }
    }

    $cur = $this->con->openCursor($this->table);
    $cur->blog_id = (string) $this->blog->id;
    foreach ($this->fields as $field) {
      $cur->$field = $object[$field];
    }

    $cur->update('WHERE id = '.(int) $object['id']." AND blog_id = '".$this->con->escape($this->blog->id)."'");
    $this->blog->triggerBlog();
  }

  public function delete(array $ids=array()) {
    if (empty($ids)) {
      return false;
    }

    $cur = $this->con->openCursor($this->table);
    $strReq = 'DELETE FROM '.$this->table;
    if (count($ids)==1) {
      $strReq .= ' WHERE id = '.$ids[0];
    } else {
      $strReq .= ' WHERE id IN ('.implode(',', $ids).')';
    }
    $this->con->execute($strReq);
  }
}