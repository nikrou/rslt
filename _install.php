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

$version = $core->plugins->moduleInfo('rslt', 'version');
if (version_compare($core->getVersion('rslt'), $version,'>=')) {
  return;
}

$settings = $core->blog->settings;
$settings->addNamespace('rslt');

$settings->rslt->put('active', false, 'boolean', 'RSLT plugin activated ?', false);

$s = new dbStruct($core->con, $core->prefix);

$s->rslt_album
->id ('bigint',	0, false)
->blog_id ('varchar', 32, false)
->title('varchar', 255, true, null)
->publication_date('timestamp', 0, false, 'now()')
->created_at('timestamp', 0, false, 'now()')
->updated_at('timestamp', 0, false, 'now()')
->primary('pk_rslt_album', 'id');

$s->rslt_author
->id ('bigint',	0, false)
->blog_id ('varchar', 32, false)
->firstname('varchar', 255, true, null)
->lastname('varchar', 255, true, null)
->created_at('timestamp', 0, false, 'now()')
->updated_at('timestamp', 0, false, 'now()')
->primary('pk_rslt_author', 'id');

$s->rslt_author_album
->author_id('bigint', 0, false)
->album_id('bigint', 0, false)
->primary('pk_rslt_author_album', 'author_id', 'album_id');

$s->rslt_song
->id ('bigint',	0, false)
->blog_id ('varchar', 32, false)
->title('varchar', 255, true, null)
->created_at('timestamp', 0, false, 'now()')
->updated_at('timestamp', 0, false, 'now()')
->primary('pk_rslt_song', 'id');

$s->rslt_album_song
->album_id('bigint', 0, false)
->song_id('bigint', 0, false)
->primary('pk_rslt_album_song', 'album_id', 'song_id');

$si = new dbStruct($core->con, $core->prefix);
$changes = $si->synchronize($s);

$core->setVersion('rslt', $version);
return true;
