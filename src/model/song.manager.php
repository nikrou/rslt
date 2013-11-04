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
    'singer', 'editor', 'other_editor', 'original_title', 'url');

    public static $require_fields = array('title', 'publication_date', 'author', 'singer');

    public function __construct($core) {
        parent::__construct($core, 'song', self::$require_fields, self::$fields);

        $this->table_album_song = $this->blog->prefix.'rslt_album_song';
        $this->table_album = $this->blog->prefix.'rslt_album';
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

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;
    }

    public function getList(array $params=array(), array $limit=array()) {
        $strReq =  'SELECT _s.id, _s.'.implode(',_s.', $this->object_fields);
        $strReq .= ' , _a.id AS album_id, _a.title AS album_title';
        $strReq .= ' FROM '.$this->table.' AS _s';
        $strReq .= ' LEFT JOIN '.$this->table_album_song.' AS _as';
        $strReq .= ' ON _as.song_id = _s.id';
        $strReq .= ' LEFT JOIN '.$this->table_album.' AS _a';
        $strReq .= ' ON _as.album_id = _a.id';        
        $strReq .= ' WHERE _s.blog_id = \''.$this->con->escape($this->blog->id).'\'';

        // apply filters
        if (!empty($params['equal'])) {
            foreach ($params['equal'] as $field => $value) {
                if (in_array($field, $this->object_fields)) {
                    $strReq .= sprintf(' AND _s.%s = \'%s\'', $field, $this->con->escape($value));
                }
            }
        }

        if (!empty($params['like'])) {
            foreach ($params['like'] as $field => $value) {                
                if ($field=='q') {
                    $strReq .= sprintf(' AND _s.title like \'%s\'', 
                    $this->con->escape(str_replace(array('*', '?'), array('%', '_'), $value))
                    );
                } elseif (in_array($field, $this->object_fields)) {
                    $strReq .= sprintf(' AND _s.%s like \'%%%s%%\'', $field, $this->con->escape($value));
                }
            }
        }

        // apply order        
        if (!empty($params['sortby']) && in_array($params['sortby'], $this->object_fields)) {
            $sortby_field = $params['sortby'];
        } else {
            $sortby_field = 'updated_at';
        }
        if (!empty($params['orderby']) && in_array($params['orderby'], array('DESC', 'ASC'))) {
            $orderby = $params['orderby'];
        } else {
            $orderby = 'DESC';
        }

        $strReq .= sprintf(' ORDER BY _s.%s %s', $sortby_field, $orderby); 
      
        if (!empty($limit)) {
			$strReq .= $this->con->limit($limit);
        }

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;
    }

}
