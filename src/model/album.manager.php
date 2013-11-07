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

class albumManager extends objectManager
{
    public static $fields = array('title', 'singer', 'publication_date', 'url', 'media_id', 'bio_express');

    public static $required_fields = array('title', 'singer', 'publication_date');

    public function __construct($core) {        
        parent::__construct($core, 'album', self::$required_fields, self::$fields);

        $this->table_song = $this->blog->prefix.'rslt_song';
        $this->table_album_song = $this->blog->prefix.'rslt_album_song';
        $this->table_reference_song = $this->blog->prefix.'rslt_reference_song';
    }

    public function getSongs($album_id) {
        $strReq =  'SELECT id, url, rank, '.implode(',', songManager::$fields);
        $strReq .= ' FROM '.$this->table_song.' as _s';
        $strReq .= ' LEFT JOIN '.$this->table_album_song.' as _as';
        $strReq .= ' ON _s.id = _as.song_id';
        $strReq .= ' LEFT JOIN '.$this->table_reference_song.' as _rs';
        $strReq .= ' ON _s.id = _rs.song_id';
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';      
        $strReq .= ' AND album_id = '.$this->con->escape($album_id);
        $strReq .= ' GROUP BY id, rank';
        $strReq .= ' ORDER BY rank asc';

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;
    }

    public function getPublicationDate() {
        return $this->getElements('publication_date');
    }

    // return array
    public function getSongAuthors($album_id) {
        $authors = array();

        $strReq =  'SELECT _rs.author_id';
        $strReq .= ' FROM '.$this->table_song.' as _s';
        $strReq .= ' LEFT JOIN '.$this->table_album_song.' as _as';
        $strReq .= ' ON _s.id = _as.song_id';
        $strReq .= ' LEFT JOIN '.$this->table_reference_song.' as _rs';
        $strReq .= ' ON _s.id = _rs.song_id';
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
        $strReq .= ' AND album_id = '.$this->con->escape($album_id);
 
        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
        while ($rs->fetch()) {
            $author_name = Authors::getName($rs->author_id);
            $authors[$rs->author_id] = array(
                'display' => $author_name,
                'url' => Authors::getAuthorURL($author_name)
            );
        }

        return $authors;
    }

    public function getList(array $params=array(), array $limit=array()) {
        $strReq =  'SELECT id, url, '.implode(',', $this->object_fields);
        $strReq .= ' ,count(_as.song_id) as count_songs';
        $strReq .= ' FROM '.$this->table.' as _a';
        $strReq .= ' LEFT JOIN '.$this->table_album_song.' as _as';
        $strReq .= ' ON _as.album_id = _a.id';
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
                if ($field=='q') {
                    $strReq .= sprintf(' AND title like \'%s\'', 
                    $this->con->escape(str_replace(array('*', '?'), array('%', '_'), $value))
                    );
                } elseif (in_array($field, $this->object_fields)) {
                    $strReq .= sprintf(' AND %s like \'%%%s%%\'', $field, $this->con->escape($value));
                }
            }
        }

        $strReq .= ' GROUP BY _a.id';

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

        $strReq .= sprintf(' ORDER BY _a.%s %s', $sortby_field, $orderby); 
      
        if (!empty($limit)) {
			$strReq .= $this->con->limit($limit);
        }

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;
    }

    public function findByTitle($title) {
        $strReq =  'SELECT id, '.implode(',', $this->object_fields);
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
        $strReq .= sprintf(' AND title ilike \'%s%%\'', $this->con->escape($title));
 
        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;
    }

    protected function getElements($field) {
        $strReq =  'SELECT distinct('.$field.')';
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';

        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;
    }
}