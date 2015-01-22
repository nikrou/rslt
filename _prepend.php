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

if (!defined('DC_RC_PATH')) { return; }

$__autoload['objectManager'] = dirname(__FILE__).'/src/model/object.manager.php';
$__autoload['songManager'] = dirname(__FILE__).'/src/model/song.manager.php';
$__autoload['albumManager'] = dirname(__FILE__).'/src/model/album.manager.php';
$__autoload['personManager'] = dirname(__FILE__).'/src/model/person.manager.php';
$__autoload['metaManager'] = dirname(__FILE__).'/src/model/meta.manager.php';
$__autoload['albumSong'] = dirname(__FILE__).'/src/model/album.song.php';
$__autoload['referenceSong'] = dirname(__FILE__).'/src/model/reference.song.php';
$__autoload['rsltUrlHandlers'] = dirname(__FILE__).'/src/utils/rslt.url.handlers.php';
$__autoload['rsltBehaviors'] = dirname(__FILE__).'/src/utils/rslt.behaviors.php';
$__autoload['rsltAdminBehaviors'] = dirname(__FILE__).'/src/utils/rslt.admin.behaviors.php';
$__autoload['rsltTpl'] = dirname(__FILE__).'/src/utils/rslt.tpl.php';
$__autoload['adminSongsList'] = dirname(__FILE__).'/src/utils/rslt.admin.songs.list.php';
$__autoload['adminAlbumsList'] = dirname(__FILE__).'/src/utils/rslt.admin.albums.list.php';
$__autoload['adminPersonsList'] = dirname(__FILE__).'/src/utils/rslt.admin.persons.list.php';
$__autoload['rsltPager'] = dirname(__FILE__).'/src/utils/rslt.pager.php';
$__autoload['rsltDashboard'] = dirname(__FILE__).'/src/utils/rslt.dashboard.php';
$__autoload['rsltAdminCombo'] = dirname(__FILE__).'/src/utils/rslt.admin.combo.php';

$__autoload['rsExtendMeta'] = dirname(__FILE__).'/src/model/rs.extend.meta.php';

$core->blog->settings->addNamespace('rslt');
$prefix_albums = $core->blog->settings->rslt->prefix_albums;
$prefix_album = $core->blog->settings->rslt->prefix_album;
$prefix_song = $core->blog->settings->rslt->prefix_song;

// public url needed for admin preview
$core->url->register('albums', $prefix_albums, '^'.preg_quote($prefix_albums).'$', array('rsltUrlHandlers', 'albums'));
$core->url->register('album', $prefix_album, '^'.preg_quote($prefix_album).'/(.+)$', array('rsltUrlHandlers', 'album'));
$core->url->register('song', $prefix_song, '^'.preg_quote($prefix_song).'/(.+)$', array('rsltUrlHandlers', 'song'));
