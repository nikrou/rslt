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

class referenceSong
{
    public function __construct($core) {
        $this->core = $core;
        $this->blog = $core->blog;
        $this->con = $this->blog->con;
        $this->table = $this->blog->prefix.'rslt_reference_song';
	}

    public function add($author_id, $song_id, $role) {
        $author_id = (int) $author_id;
        $song_id = (int) $song_id;

        $cur = $this->con->openCursor($this->table);
		$cur->author_id = $author_id;
		$cur->song_id = $song_id;
        $cur->role = $role;

        try {
            $cur->insert();
        } catch (Exception $e) {
            // row exists ?
        } 
		$this->core->blog->triggerBlog();
    }
}