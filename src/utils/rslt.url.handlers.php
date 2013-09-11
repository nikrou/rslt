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

class rsltUrlHandlers extends dcUrlHandlers
{
    public static function albums($args) {
        self::serveDocument('albums.html');
    }

    public static function album($args) {
        global $core, $_ctx;
        
        if (empty($args)) {
            throw new Exception('Page not found', 404);
        }

        $album_manager = new albumManager($core);
        $_ctx->album = $album_manager->findByURL($args);

        if ($_ctx->album->isEmpty()) {
            throw new Exception("Page not found", 404);
        }
        
        self::serveDocument('album.html');
    }

    public static function song($args) {
        self::serveDocument('song.html');
    }    
}