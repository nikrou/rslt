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

$self_ns = $core->blog->settings->addNamespace('rslt');
if (!$self_ns->active) {
    return;
}

// public urls
$prefix_albums = $core->blog->settings->rslt->prefix_albums;
$prefix_album = $core->blog->settings->rslt->prefix_album;
$prefix_song = $core->blog->settings->rslt->prefix_song;

// behaviors
$core->addBehavior('publicBeforeDocument', array('rsltBehaviors', 'addTplPath'));
$core->addBehavior('publicHeadContent', array('rsltBehaviors', 'publicHeadContent'));

$_ctx->album_manager = new albumManager($core);

// template tags
// albums
$core->tpl->addBlock('Albums', array('rsltTpl', 'Albums'));
$core->tpl->addValue('AlbumTitle', array('rsltTpl', 'AlbumTitle'));
$core->tpl->addValue('AlbumURL', array('rsltTpl', 'AlbumURL'));

// album
$core->tpl->addValue('AlbumPageTitle', array('rsltTpl', 'AlbumPageTitle'));
$core->tpl->addValue('AlbumPageSinger', array('rsltTpl', 'AlbumPageSinger'));
$core->tpl->addValue('AlbumPagePublicationDate', array('rsltTpl', 'AlbumPagePublicationDate'));
$core->tpl->addBlock('AlbumPageIfMediaSrc', array('rsltTpl', 'AlbumPageIfMediaSrc'));
$core->tpl->addValue('AlbumPageMediaSrc', array('rsltTpl', 'AlbumPageMediaSrc'));
$core->tpl->addValue('AlbumPageBioExpress', array('rsltTpl', 'AlbumPageBioExpress'));

$core->tpl->addBlock('AlbumSongs', array('rsltTpl', 'AlbumSongs'));
$core->tpl->addBlock('AlbumSongsHeader', array('rsltTpl', 'AlbumSongsHeader'));
$core->tpl->addBlock('AlbumSongsFooter', array('rsltTpl', 'AlbumSongsFooter'));
$core->tpl->addValue('SongTitle', array('rsltTpl', 'SongTitle'));
$core->tpl->addValue('SongAuthor', array('rsltTpl', 'SongAuthor'));
$core->tpl->addValue('SongData', array('rsltTpl', 'SongData'));

$core->tpl->addBlock('AlbumSongsAuthors', array('rsltTpl', 'AlbumSongsAuthors'));
$core->tpl->addValue('AuthorId', array('rsltTpl', 'AuthorId'));
$core->tpl->addValue('AuthorURL', array('rsltTpl', 'AuthorURL'));
$core->tpl->addValue('AuthorDisplayName', array('rsltTpl', 'AuthorDisplayName'));

// post authors
$core->tpl->addBlock('MetaAuthors', array('rsltTpl', 'MetaAuthors'));
$core->tpl->addValue('MetaAuthorId', array('rsltTpl', 'MetaAuthorId'));
$core->tpl->addValue('MetaAuthorURL', array('rsltTpl', 'MetaAuthorURL'));
$core->tpl->addValue('MetaAuthorDisplayName', array('rsltTpl', 'MetaAuthorDisplayName'));
$core->tpl->addValue('MetaAuthorsData', array('rsltTpl', 'MetaAuthorsData'));
