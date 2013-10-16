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
    public static $fields = array('title', 'singer', 'publication_date', 'url');

    public static $required_fields = array('title', 'singer', 'publication_date');

    public function __construct($core) {        
        parent::__construct($core, 'album', self::$required_fields, self::$fields);

        $this->table_song = $this->blog->prefix.'rslt_song';
        $this->table_album_song = $this->blog->prefix.'rslt_album_song';
        $this->table_reference_song = $this->blog->prefix.'rslt_reference_song';
    }

    public function getSongs($album_id) {
        $strReq =  'SELECT id, url, '.implode(',', songManager::$fields);
        $strReq .= ' FROM '.$this->table_song.' as _s';
        $strReq .= ' LEFT JOIN '.$this->table_album_song.' as _as';
        $strReq .= ' ON _s.id = _as.song_id';
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';      
        $strReq .= ' AND album_id = '.$this->con->escape($album_id);
 
        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;
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

    public function findByTitle($title) {
        $strReq =  'SELECT id, '.implode(',', $this->object_fields);
        $strReq .= ' FROM '.$this->table;
        $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
        $strReq .= sprintf(' AND title ilike \'%s%%\'', $this->con->escape($title));
 
        $rs = $this->con->select($strReq);
        $rs = $rs->toStatic();
      
        return $rs;
    }
}