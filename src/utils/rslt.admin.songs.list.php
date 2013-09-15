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

    public function display($songs, $nb_per_page) {
        $pager = new rsltPager($songs, $this->rs_count, $nb_per_page, 10);
        $pager->setAnchor(self::$anchor);
        $pager->html_prev = $this->html_prev;
        $pager->html_next = $this->html_next;

        $html_block = 
            '<table class="songs clear" id="songs-list">'.
            '<thead>'.
            '<tr>'.
            '<th>&nbsp;</th>'.
            '<th>'. __('Title').'</th>'.
            '<th>'. __('Album').'</th>'.
            '<th>'.__('Author').'</th>'.
            '<th>'. __('Singer').'</th>'.
            '<th class="nowrap">'.__('Publication date').'</th>'.
            '</tr>'.
            '</thead>'.
            '<tbody>%s</tbody></table>';
        
        echo '<p class="pagination">'.__('Page(s)').' : '.$pager->getLinks().'</p>';

        $blocks = explode('%s',$html_block);
        
        echo $blocks[0];
        
        while ($this->rs->fetch()) {
            echo $this->postLine();
        }

        echo $blocks[1];

        echo '<p class="pagination">'.__('Page(s)').' : '.$pager->getLinks().'</p>';
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
            '<td class="nowrap">&nbsp;</td>'. //html::escapeHTML($songs->author)
            '<td class="nowrap">'.html::escapeHTML($this->rs->author).'</td>'.
            '<td class="nowrap">'.html::escapeHTML($this->rs->singer).'</td>'.
            '<td class="nowrap">'.$this->rs->publication_date.'</td>'.
            '</tr>';
        
        return $res;
    }
}