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

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$page_title = 'Load csv file';

if (!empty($_POST['file'])) {
    $filename = sprintf('%s/%s.csv', __DIR__.'/../../data', $_POST['file']);
    if (file_exists($filename)) {
        $song_manager = new songManager($core);
        $album_manager = new albumManager($core);
        $album_song = new albumSong($core);
        $author_song = new authorSong($core);

        if (($fh = fopen($filename, 'r')) !== false) {
            $songs = array();
            // year, title, author, singer, album
            while (($data = fgetcsv($fh, 1000, ';', '"')) !== false) {
                if (!empty($data[4])) {
                    $album_title = $data[4];
                } else {
                    $album_title = '';
                }

                $song = $song_manager->replaceByTitle(array('publication_date' => $data[0], 'title' => $data[1], 
                'author' => $data[2], 'singer' => $data[3]));

                // find known authors
                if ((strpos($data[2], ',')!==false) && preg_match_all('`('.implode('|', $Authors).')`', $data[2], $matches)) {
                    foreach ($matches[0] as $author_title) {
                        $author_song->add(array_search($author_title, $Authors), $song->id);
                    }
                }

                if (!empty($album_title)) {
                    $album = $album_manager->replaceByTitle(array('title' => $album_title, 'singer' => $data[3], 
                    'publication_date' => $data[0]));
                    $album_song->add($album->id, $song->id);
                }
                
                $songs[] = $data;
            }
            fclose($fh);
            
            $_SESSION['rslt_message'] = __('Data successfully loaded.');
            $_SESSION['rslt_default_tab'] = 'maintenance';
            http::redirect($p_url);
        }
    }
}

$default_tab = 'mainatenance';

include(dirname(__FILE__).'/../views/load.tpl');
