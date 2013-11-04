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

class rsltAdminBehaviors
{
    public static function adminAfterPostUpdate($cur, $post_id) {
		self::adminAfterPostSave($cur, $post_id, true);
	}
	
	public static function adminAfterPostCreate($cur, $post_id) {
		self::adminAfterPostSave($cur, $post_id, false);
	}

    private static function adminAfterPostSave($cur, $post_id, $update) {
		global $core;

        $authors = array();
        if (!empty($_POST['rslt_authors'])) {
            $authors = $_POST['rslt_authors'];
        }
        
        $core->meta->delPostMeta($post_id, 'rslt');
        foreach ($authors as $author) {
            $core->meta->setPostMeta($post_id, 'rslt', $author);
        }
    }

	public static function adminPostHeaders() {
		return dcPage::jsLoad('index.php?pf=rslt/js/post.js');
	}

    // may be deprecated
	public static function adminPostFormSidebar($post) {
        $rslt_checkboxes = self::adminPostFormCheckboxes($post);

        if ($rslt_checkboxes !== false) {
            echo $rslt_checkboxes;
        }
    }
    
	public static function adminPostFormItems($main_items, $sidebar_items, $post) {
        $sidebar_items['metas-box']['items']['rslt'] = self::adminPostFormCheckboxes($post);
    }

    private static function adminPostFormCheckboxes($post) {
        global $core;

        $can_publish = $core->auth->check('publish,contentadmin',$core->blog->id);
        if (!$core->auth->check('contentadmin', $core->blog->id)) {
			if ($post === null) {
				$can_publish = false;
			} else {
                $strReq = 'SELECT post_id ';
                $strReq .= ' FROM '.$core->con->prefix.'post';
                $strReq .= ' WHERE post_id = '.$post->post_id;
                $strReq .= ' AND user_id = '.$core->con->escape($core->auth->userID());

				$rs = $core->con->select($strReq);
				$can_publish = !$rs->isEmpty();
			}
		}

        if ($can_publish) {
            $meta_authors = array();
            if ($post != null && ($meta_str = $core->meta->getMetaStr($post->post_meta, 'rslt'))) {
                $meta_authors = array_map('trim', explode(',', $meta_str));
            }
            
            $rslt_checkboxes = '<h5 class="rslt">'.__('Authors').'</h5>';

            foreach (Authors::getAll() as $author) {
                $author_id = Authors::getAuthorURL($author);
                if (in_array($author_id, $meta_authors)) {
                    $checked = ' checked="checked"';
                } else {
                    $checked = '';
                }
                
                $rslt_checkboxes .= '<p class="rslt author"><label class="classic" for="'.$author_id.'">';
                $rslt_checkboxes .= '<input type="checkbox" id="'.$author_id.'" name="rslt_authors[]"';
                $rslt_checkboxes .= ' value="'.$author_id.'"'.$checked.'/>&nbsp;';
                $rslt_checkboxes .= html::escapeHTML($author).'</label></p>';
            }

            $rslt_checkboxes .= '<p class="rslt checkboxes-helpers"></p>';

            return $rslt_checkboxes;
        }
    }
}
