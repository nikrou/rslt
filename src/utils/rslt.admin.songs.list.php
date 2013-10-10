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

class adminSongsList extends adminGenericList
{
    public static $anchor = 'songs';
    private $p_url;

    public function setPluginUrl($p_url) {
        $this->p_url = $p_url;
    }

    public function display($songs, $nb_per_page, $enclose_block) {
        $pager = new rsltPager($songs, $this->rs_count, $nb_per_page, 10);
        $pager->setVarPage('page_songs');
        $pager->setAnchor(self::$anchor);

        $html_block = 
			'<div class="table-outer">'.
            '<table class="songs clear" id="songs-list">'.
            '<thead>'.
            '<tr>'.
            '<th>&nbsp;</th>'.
            '<th>'. __('Title').'</th>'.
            '<th>'. __('Album').'</th>'.
            '<th>'.__('Author').'</th>'.
            '<th>'.__('Compositor').'</th>'.
            '<th>'.__('Adaptator').'</th>'.
            '<th>'.__('Singer').'</th>'.
            '<th>'.__('Editor').'</th>'.
            '<th class="nowrap">'.__('Publication date').'</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>%s</tbody></table>'.
            '</div>';
        
        echo $pager->getLinks();

        if ($enclose_block) {
            $html_block = sprintf($enclose_block, $html_block);
        }

        $blocks = explode('%s',$html_block);
        
        echo $blocks[0];
        
        while ($this->rs->fetch()) {
            echo $this->postLine();
        }

        echo $blocks[1];

        echo $pager->getLinks();
    }

    private function postLine() {
        $res = 
            '<tr>'.
            '<td>'.
            form::checkbox(array('songs[]'), $this->rs->id, '', '', '').
            '</td>'.
            '<td class="maximal">'.
            '<a href="'.sprintf($this->p_url, $this->rs->id).'">'.
            html::escapeHTML(text::cutString($this->rs->title, 50)).
            '</a>'.
            '</td>'.
            '<td class="nowrap">&nbsp;</td>'.
            '<td class="nowrap">'.html::escapeHTML($this->rs->author).'</td>'.
            '<td class="nowrap">'.html::escapeHTML($this->rs->compositor).'</td>'.
            '<td class="nowrap">'.html::escapeHTML($this->rs->adaptator).'</td>'.
            '<td class="nowrap">'.html::escapeHTML(text::cutString($this->rs->singer,50)).'</td>'.
            '<td class="nowrap">'.html::escapeHTML($this->rs->editor).'</td>'.
            '<td class="nowrap">'.$this->rs->publication_date.'</td>'.
            '</tr>';
        
        return $res;
    }
}