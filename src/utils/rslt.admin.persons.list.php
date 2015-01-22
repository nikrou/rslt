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

class adminPersonsList extends adminGenericList
{
    public static $anchor = 'persons';
    private $p_url;

    public function setPluginUrl($p_url) {
        $this->p_url = $p_url;
    }

    public function display($persons, $nb_per_page, $enclose_block) {
        $pager = new rsltPager($persons, $this->rs_count, $nb_per_page, 10);
        $pager->setAnchor(self::$anchor);

        $html_block =
			'<div class="table-outer">'.
            '<table class="persons clear" id="persons-list">'.
            '<thead>'.
            '<tr>'.
            '<th>&nbsp;</th>'.
            '<th>'. __('Name').'</th>'.
            '<th>'.__('Firstname').'</th>'.
            '<th>'.__('Lastname').'</th>'.
            '<th>'.__('Role').'</th>'.
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
        $role = '';

        $res =
            '<tr>'.
            '<td>'.
            form::checkbox(array('persons[]'), $this->rs->id, '', '', '').
            '</td>'.
            '<td class="maximal">'.
            '<a href="'.sprintf($this->p_url, $this->rs->id).'">'.
            html::escapeHTML(text::cutString($this->rs->title, 50)).
            '</a>'.
            '</td>'.
            '<td class="nowrap">'.$this->rs->first_name.'</td>'.
            '<td class="nowrap">'.$this->rs->last_name.'</td>'.
            '<td class="nowrap">'.$role.'</td>'.
            '</tr>';

        return $res;
    }
}
