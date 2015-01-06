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

$version = $core->plugins->moduleInfo('rslt', 'version');
if (version_compare($core->getVersion('rslt'), $version,'>=')) {
    return;
}

$settings = $core->blog->settings;
$settings->addNamespace('rslt');

$settings->rslt->put('active', false, 'boolean', 'RSLT plugin activated ?', false);
$settings->rslt->put('prefix_albums', 'albums', 'string', 'RSLT albums prefix', false);
$settings->rslt->put('prefix_album', 'album', 'string', 'RSLT album prefix', false);
$settings->rslt->put('prefix_song', 'song', 'string', 'RSLT song prefix', false);
$settings->rslt->put('directory_albums', 'albums', 'string', 'Directory form media albums', false);
$settings->rslt->put('directory_bios', 'bios', 'string', 'Directory form media bios', false);
$settings->rslt->put('directory_supports', 'supports', 'string', 'Directory form media supports', false);

$s = new dbStruct($core->con, $core->prefix);

$s->rslt_album
    ->id ('bigint',	0, false)
    ->blog_id ('varchar', 32, false)
    ->title('varchar', 255, true, null)
    ->meta_singer('text', 0, true, null)
    ->url('varchar', 255, true, null)
    ->publication_date('bigint', 0, false)
    ->media_id('bigint', 0, true)
    ->bio_express('text', 0, true, null)
    ->created_at('timestamp', 0, false, 'now()')
    ->updated_at('timestamp', 0, false, 'now()')
    ->unique('uk_album_url','url')
    ->primary('pk_rslt_album', 'id');

$s->rslt_reference_song
    ->author_id('bigint', 0, false)
    ->song_id('bigint', 0, false)
    ->role('varchar',255,false)
    ->primary('pk_rslt_author_song_role', 'author_id', 'song_id', 'role');

$s->rslt_song
    ->id ('bigint',	0, false)
    ->blog_id ('varchar', 32, false)
    ->title('varchar', 255, true, null)
    ->original_title('varchar', 255, true, null)
    ->meta_author('text', 0, true, null)
    ->meta_compositor('text', 0, true, null)
    ->meta_adaptator('text', 0, true, null)
    ->meta_singer('text', 0, true, null)
    ->meta_editor('text', 0, true, null)
    ->publication_date('bigint', 0, false)
    ->url('varchar', 255, true, null)
    ->created_at('timestamp', 0, false, 'now()')
    ->updated_at('timestamp', 0, false, 'now()')
    ->unique('uk_song_year','title','publication_date')
    ->unique('uk_song_url','url')
    ->primary('pk_rslt_song', 'id');

$s->rslt_album_song
    ->album_id('bigint', 0, false)
    ->song_id('bigint', 0, false)
    ->rank('bigint', 0, true)
    ->primary('pk_rslt_album_song', 'album_id', 'song_id');

$s->rslt_support
    ->id ('bigint',	0, false)
    ->blog_id ('varchar', 32, false)
    ->support_type('varchar', 255, true, null)
    ->country('varchar', 255, true, null)
    ->productor('varchar', 255, true, null)
    ->distributor('varchar', 255, true, null)
    ->support_reference('varchar', 255, true, null)
    ->publication_date('bigint', 0, false)
    ->excerpt('varchar', 255, true, null)
    ->primary('pk_rslt_support', 'id');

$s->rslt_meta
    ->meta_id('varchar', 255, false)
    ->meta_type('varchar', 64, false)
    ->person_id('bigint', 0, false)
    ->primary('pk_rslt_meta','meta_id','meta_type','person_id');

$s->rslt_person
    ->id('bigint', 0, false)
    ->blog_id ('varchar', 32, false)
    ->name('varchar', 255, true, null)
    ->url('varchar', 255, true, null)
    ->created_at('timestamp', 0, false, 'now()')
    ->updated_at('timestamp', 0, false, 'now()')
	->primary('pk_rslt_person', 'id');

// index
$s->rslt_album->index('idx_album_title',	'btree', 'title');
$s->rslt_album->index('idx_album_url', 'btree', 'url');
$s->rslt_song->index('idx_song_url', 'btree', 'url');

// foreign keys
$s->rslt_album->reference('fk_album_blog','blog_id','blog','blog_id','cascade','cascade');
$s->rslt_song->reference('fk_song_blog','blog_id','blog','blog_id','cascade','cascade');
$s->rslt_support->reference('fk_support_blog','blog_id','blog','blog_id','cascade','cascade');
$s->rslt_reference_song->reference('fk_reference_song','song_id','rslt_song','id','cascade','cascade');

$s->rslt_meta->reference('fk_meta_singer','person_id','rslt_person','id','cascade','cascade');


$si = new dbStruct($core->con, $core->prefix);
$changes = $si->synchronize($s);

$core->setVersion('rslt', $version);
return true;
