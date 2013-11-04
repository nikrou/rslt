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

class rsltTpl
{
    // albums
    public static function Albums($attr, $content) {
        $res = '';
        
        $res .= "<?php\n";
        $res .= '$_ctx->albums = $_ctx->album_manager->getList();';
        $res .= 'while ($_ctx->albums->fetch()):?>';
        $res .= $content;
        $res .= '<?php endwhile; $_ctx->albums = null;?>';
        
        return $res;
    }

    public static function AlbumTitle($attr) {
        $f = $GLOBALS['core']->tpl->getFilters($attr);
        
        return '<?php echo '.sprintf($f, '$_ctx->albums->title').';?>';
    }

   public static function AlbumURL($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);

       return '<?php echo '.sprintf($f,'$core->blog->url.$core->url->getBase("album").'.
       '"/".rawurlencode($_ctx->albums->url)').';?>';
   }

   // album
   public static function AlbumPageTitle($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->album->title').';?>';
   }

   public static function AlbumPageSinger($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->album->singer').';?>';
   }   

   public static function AlbumPagePublicationDate($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->album->publication_date').';?>';
   }   

   public static function AlbumSongs($attr, $content) {
        $res = '';
        
        $res .= "<?php\n";
        $res .= '$_ctx->songs = $_ctx->album_manager->getSongs($_ctx->album->id);';
        $res .= 'while ($_ctx->songs->fetch()):?>';
        $res .= $content;
        $res .= '<?php endwhile; $_ctx->songs = null;?>';
        
        return $res;
   }

   public static function AlbumSongsHeader($attr, $content) {
       return
           "<?php if (\$_ctx->songs->isStart()):?>".
           $content.
           "<?php endif; ?>";
   }

   public static function AlbumSongsFooter($attr, $content) {
       return
           "<?php if (\$_ctx->songs->isEnd()):?>".
           $content.
           "<?php endif; ?>";
   }

   public static function SongTitle($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->songs->title').';?>';
   }   

   public static function SongAuthor($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->songs->author').';?>';
   }   

   public static function SongData($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);

       return '<?php echo '.sprintf($f, 'Authors::getSongData($_ctx->songs)').';?>';
   }

   // Authors
   /*
   ** Authors in current album
   */
   public static function AlbumSongsAuthors($attr, $content) {
       $res = '';
       $res .= "<?php\n";
       $res .= '$_ctx->song_authors = $_ctx->album_manager->getSongAuthors($_ctx->album->id);'."\n";
       $res .= 'foreach ($_ctx->song_authors as $_ctx->song_author_id => $_ctx->song_author):?>';
       $res .= $content;
       $res .= '<?php endforeach;unset($_ctx->song_authors,$_ctx->song_author_id,$_ctx->song_author);?>';
       
       return $res;
   }

   public static function AuthorId($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->song_author_id').';?>';
   }

   public static function AuthorURL($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->song_author[\'url\']').';?>';
   }

   public static function AuthorDisplayName($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->song_author[\'display\']').';?>';
   }

   // metadata in entries
   public static function MetaAuthors($attr, $content) {
       $res = '';
       $res .= "<?php\n";
       $res .= '$post_authors = json_decode($core->meta->getMetaStr($_ctx->posts->post_meta, "rslt"));';
       $res .= 'foreach ($post_authors as $_ctx->post_author_id):?>';
       $res .= $content;
       $res .= '<?php endforeach;unset($_ctx->post_author_id);?>';

       return $res;
   }

   public static function MetaAuthorId($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->meta->meta_id').';?>';
   }

   public static function MetaAuthorURL($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, '$_ctx->meta->meta_id').';?>';
   }

   public static function MetaAuthorDisplayName($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);
       
       return '<?php echo '.sprintf($f, 'Authors::getAuthorFromUrl($_ctx->meta->meta_id)').';?>';
   }

   public static function MetaAuthorsData($attr) {
       $f = $GLOBALS['core']->tpl->getFilters($attr);

       return '<?php echo '.sprintf($f, 'Authors::getDataFromMeta($_ctx->posts->post_meta)').';?>';
   }
}
