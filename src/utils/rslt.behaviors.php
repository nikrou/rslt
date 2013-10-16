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

class rsltBehaviors
{
    public static function addTplPath($core) {
        $core->tpl->setPath($core->tpl->getPath(), __DIR__.'/../../default-templates');
    }

    public static function publicHeadContent($core) {
        if (in_array($core->url->type, array('album'))) {
            $plugin_root = html::stripHostURL($core->blog->getQmarkURL().'pf=rslt');

            $res = sprintf('<script type="text/javascript" src="%s"></script>',
            $plugin_root.'/js/jquery.rslt.js'
            );

            echo $res;
        }
    }
}