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
    public $object_fields = array();
    public $object_required_fields = array();

    public function __construct($core, $object_name, $required_fields, $fields) {
        $this->core = $core;
        $this->blog = $core->blog;
        $this->con = $this->blog->con;
        $this->table = $this->blog->prefix.'rslt_'.$object_name;

        $this->object_name = $object_name;
        $this->object_required = $required_fields;
        $this->object_fields = $fields;
    }

    public function openCursor() {
        return $this->con->openCursor($this->table);
    }
  
    public function add($cur) {
        foreach ($this->object_required_fields as $field) {
            if ($cur->$field = '') {
                throw new Exception(sprintf(__('You must provide %s field', $field)));
            }
        }
        
        $cur->blog_id = (string) $this->blog->id;
        if ($cur->url == '') {
            $cur->url = text::tidyURL((string) $cur->title, false);
        }

        try {
            $rs = $this->con->select('SELECT MAX(id) FROM '.$this->table);
            $cur->id = (int) $rs->f(0) + 1;
            $cur->insert();
            $this->con->unlock();
        } catch (Exception $e) {
            $this->con->unlock();
			throw $e;
        }
        $this->blog->triggerBlog();

        return $cur;
    }

    public function update($id, $cur) {
        if ($cur->url == '') {
            $cur->url = text::tidyURL((string) $cur->title, false);
        }

        $cur->update('WHERE id = '.(int) $id." AND blog_id = '".$this->con->escape($this->blog->id)."'");
        $this->blog->triggerBlog();

        return $cur;
    }

    // replace by finding existing object with same title
    public function replaceByTitle($object) {
        $rs = $this->findByTitle($object['title']);
        $cur = $this->openCursor();
        
        if (!$rs->isEmpty()) {
            foreach ($object as $field => $value) {
                $cur->$field = $value;
            }
            $this->update($cur->id, $cur);
        } else {
            foreach ($object as $field => $value) {
                $cur->$field = $value;
            }
            $this->add($cur);
        }

        return $cur;
    }

    public function replaceByTitleAndPublicationDate($object) {
        $rs = $this->findByTitleAndPublicationDate($object['title'], $object['publication_date']);
        $cur = $this->openCursor();

        if (!$rs->isEmpty()) {
            foreach ($object as $field => $value) {
                $cur->$field = $value;
            }
            $cur->url = $rs->url;
            $this->update($rs->id, $cur);

            return $rs;
        } else {
            foreach ($object as $field => $value) {
                $cur->$field = $value;
            }
            
            $rs = $this->findByURL(text::tidyURL((string) $object['title'], false));
            if (!$rs->isEmpty()) {
                $cur->url = $rs->publication_date . '-' .$rs->url;
            }

            $this->add($cur);

            return $cur;
        }
    }

    public function delete(array $ids=array()) {
        if (empty($ids)) {
            return false;
        }

        $cur = $this->openCursor();
        $strReq = 'DELETE FROM '.$this->table;
        if (count($ids)==1) {
            $strReq .= ' WHERE id = '.$ids[0];
        } else {
            $strReq .= ' WHERE id IN ('.implode(',', $ids).')';
        }
        $this->con->execute($strReq);
    }

    public function findById($id) {
        $strReq =  'SELECT id, url, '.implode(',', $this->object_fields);
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
        $strReq .= ' AND id='.$this->con->escape($id);

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();

        return $rs;
    }

    public function findByTitle($title) {
        $strReq =  'SELECT id, url, '.implode(',', $this->object_fields);
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
        $strReq .= ' AND title = \''.$this->con->escape($title).'\'';

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;     
    }

    public function findByTitleAndPublicationDate($title, $publication_date) {
        $strReq =  'SELECT id, url, '.implode(',', $this->object_fields);
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
        $strReq .= ' AND title = \''.$this->con->escape($title).'\'';
        $strReq .= ' AND publication_date = '.(int) $this->con->escape($publication_date);
      
        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;     
    }

    public function findByURL($url) {
        $strReq =  'SELECT id, url, '.implode(',', $this->object_fields);
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';

        $strReq .= ' AND url = \''.$this->con->escape($url).'\'';
      
        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;     
    }

    public function getList(array $params=array(), array $limit=array()) {
        $strReq =  'SELECT id, url, '.implode(',', $this->object_fields);
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';

        // apply filters
        if (!empty($params['equal'])) {
            foreach ($params['equal'] as $field => $value) {
                if (in_array($field, $this->object_fields)) {
                    $strReq .= sprintf(' AND %s = \'%s\'', $field, $this->con->escape($value));
                }
            }
        }

        if (!empty($params['like'])) {
            foreach ($params['like'] as $field => $value) {
                if (in_array($field, $this->object_fields)) {
                    $strReq .= sprintf(' AND %s like \'%%%s%%\'', $field, $this->con->escape($value));
                }
            }
        }

        // apply order
        $strReq .= ' ORDER BY updated_at ASC';
      
        if (!empty($limit)) {
			$strReq .= $this->con->limit($limit);
        }

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;
    }

   public function getcountList(array $params=array()) {
        $strReq =  'SELECT count(1)';
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';

        // apply filters
        if (!empty($params['equal'])) {
            foreach ($params['equal'] as $field => $value) {
                if (in_array($field, $this->object_fields)) {
                    $strReq .= sprintf(' AND %s = \'%s\'', $field, $this->con->escape($value));
                }
            }
        }

        if (!empty($params['like'])) {
            foreach ($params['like'] as $field => $value) {
                if (in_array($field, $this->object_fields)) {
                    $strReq .= sprintf(' AND %s like \'%%%s%%\'', $field, $this->con->escape($value));
                }
            }
        }

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
  
        return $rs->f(0);
    }
}