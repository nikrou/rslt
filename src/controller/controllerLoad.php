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

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$page_title = 'Load csv file';
$page_url = $p_url.'&page=load';

if (!empty($_POST['file']) && !empty($_POST['object'])) {
    $filename = sprintf('%s/%s.csv', __DIR__.'/../../data', $_POST['file']);
    if (file_exists($filename)) {
        $song_manager = new songManager($core);
        $album_manager = new albumManager($core);
        $album_song = new albumSong($core);
        $reference_song = new referenceSong($core);
        $person_manager = new personManager($core);
        $meta_manager = new metaManager($core);

        if (($fh = fopen($filename, 'r')) !== false) {
            if ($_POST['object']=='song') {
                // "Année de publication";"Titre";"Auteur";"Compositeur";"Adaptateur";"Interprète";"Editeur";"Titre original"
                while (($data = fgetcsv($fh, 1000, ';', '"')) !== false) {
                    if (empty($data) || !is_array($data)) {
                        continue;
                    }
                    $publication_date = isset($data[0])?trim($data[0]):'';
                    $title = isset($data[1])?trim($data[1]):'';

                    $meta_fields = array('author','compositor','adaptator','singer','editor');
                    $meta = array();
                    $fields = array();
                    $fields['author'] = isset($data[2])?trim($data[2]):'';
                    $fields['compositor'] = isset($data[3])?trim($data[3]):'';
                    $fields['adaptator'] = isset($data[4])?trim($data[4]):'';
                    $fields['singer'] = isset($data[5])?trim($data[5]):'';
                    $fields['editor'] = isset($data[6])?trim($data[6]):'';
                    $original_title = isset($data[7])?trim($data[7]):'';
                    if (empty($title) || empty($publication_date)) {
                        continue;
                    }

                    $song = $song_manager->findByTitleAndPublicationDate($title, $publication_date);
                    if (!$song->isEmpty()) {
                        continue;
                    }
                    $song = $song_manager->openCursor();
                    $song->publication_date = $publication_date;
                    $song->title = $title;
                    foreach ($meta_fields as $field) {
                        $persons = array();
                        $raw_persons = explode(',', $fields[$field]);
                        foreach ($raw_persons as $raw_person) {
                            $person = $person_manager->searchByName($raw_person);
                            if ($person->isEmpty()) {
                                $person = $person_manager->openCursor();
                                $person->title = $raw_person;
                                $person_id = $person_manager->add($person);
                            } else {
                                $person_id = $person->id;
                            }
                            $persons[] = array('id' => $person_id, 'title' => $person->title);
                        }
                        $meta[$field] = $persons;

                    }
                    $song->meta = $meta;
                    $song_id = $song_manager->add($song);
                    foreach ($meta_fields as $field) {
                        $meta_manager->add($song_id, $meta[$field], "song:$field");
                    }
                }
                fclose($fh);
            } elseif ($_POST['object']=='album') {
                // "Année de publication";"Titre";"Interprète"
                while (($data = fgetcsv($fh, 1000, ';', '"')) !== false) {
                    if (empty($data)) {
                        continue;
                    }
                    $publication_date = trim($data[0]);
                    $title = trim($data[1]);
                    $singer = trim($data[2]);

                    if (empty($publication_date) || empty($title) || empty($singer)) {
                        continue;
                    }

                    $album = $album_manager->findByTitleAndPublicationDate($title, $publication_date);
                    if (!$album->isEmpty()) {
                        continue;
                    }

                    $cur = $album_manager->openCursor();
                    $cur->title = $title;
                    $cur->publication_date = $publication_date;

                    $persons = array();
                    $person = $person_manager->searchByName($singer);
                    if ($person->isEmpty()) {
                        $person = $person_manager->openCursor();
                        $person->title = $singer;
                        $person_id = $person_manager->add($person);
                    } else {
                        $person_id = $person->id;
                    }
                    $persons[] = array('id' => $person_id, 'title' => $person->title);
                    $cur->meta = array('singer' => $persons);
                    $album_id = $album_manager->add($cur);
                    $meta_manager->add($album_id, $persons, 'album:singer');
                }
                fclose($fh);
            } elseif ($_POST['object']=='album_song') {
                while (($data = fgetcsv($fh, 1000, ';', '"')) !== false) {
                    if (empty($data)) {
                        continue;
                    }
                }
                fclose($fh);
            }

            $_SESSION['rslt_message'] = __('The data have been loaded.');
            http::redirect($p_url);
        }
    }
}
