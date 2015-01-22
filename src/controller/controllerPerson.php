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

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$page_url = $p_url.'&object=person';

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_GET['q'])) {
    header('Content-type: application/json');
    $person_manager = new personManager($core);
    $persons = $person_manager->searchByTitle($_GET['q']);
    $response = array();
    while ($persons->fetch()) {
        $response[] = array('id' => $persons->id, 'title' => $persons->title);
    }
    echo json_encode($response);
    exit();
} elseif (!empty($_REQUEST['action']) && ($_REQUEST['action']=='edit')) {
    $action = $_REQUEST['action'];
    $page_title = __('Edit person');

    $person = array(
        'title' => '', 'first_name' => '',
        'url' => '', 'last_name' => '',
    );

    $person_manager = new personManager($core);
    if (!empty($_GET['id'])) {
        $rs = $person_manager->findById($_GET['id']);
        if (!$rs->isEmpty()) {
            $person['id'] = (int) $_GET['id'];
            $person['title'] = $rs->title;
            $person['first_name'] = $rs->first_name;
            $person['last_name'] = $rs->last_name;
            $person['url'] = $rs->url;
            $_SESSION['person_id'] = (int) $_GET['id'];
            $_SESSION['person_title'] = (string) $rs->title;
        } else {
            dcPage::addErrorNotice('That person does not exist.');
            http::redirect($page_url);
        }
    }
    if (!empty($_POST['save_person'])) {
        $cur = $person_manager->openCursor();
        $cur->title = (string) $_SESSION['person_title'];
        if (isset($_POST['person_url'])) {
            $cur->url = (string) $_POST['person_url'];
        }
        $cur->first_name = (string) $_POST['person_first_name'];
        $cur->last_name = (string) $_POST['person_last_name'];

        try {
            $person_id = $person_manager->update($_SESSION['person_id'], $cur);
            $message = __('The person has been updated.');

            $_SESSION['rslt_message'] = $message;
            http::redirect($page_url.'&action=edit&id='.(int) $_POST['id']);
        } catch (Exception $e) {
            $core->error->add($e->getMessage());
        }
    }

    include(dirname(__FILE__).'/../views/form_person.tpl');
} else {
    $form_filter_title = __('Show filters and display options');
    $show_filters = false;
    $filters_params = array();

    $page = !empty($_GET['page']) ? (integer) $_GET['page'] : 1;
    $nb_per_page =  10;

    if (!empty($_GET['nb']) && (integer) $_GET['nb'] > 0) {
        $nb_per_page = (integer) $_GET['nb'];
    }
    $limit = array((($page-1)*$nb_per_page), $nb_per_page);

    $persons_action_combo = array();
    $sortby = $order = null;
    $sortby_combo = array(
        '' => '',
        __('Name') => 'title',
        __('Firstname') => 'first_name',
        __('Lastname') => 'lastname'
    );
    $order_combo = array(__('Descending') => 'DESC', __('Ascending') => 'ASC');
    $q = null;

    $person_manager = new personManager($core);

    if (!empty($_GET['q'])) {
        $aq = $_GET['q'];
        $filters_params['like']['q'] = $q;
        $show_filters = true;
    }

    try {
        $persons_counter = $person_manager->getCountList($filters_params);
        $persons_list = new adminPersonsList($core, $person_manager->getList($filters_params, $limit), $persons_counter);
        $persons_list->setPluginUrl("$p_url&amp;object=person&amp;action=edit&amp;id=%d");
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }

    include(dirname(__FILE__).'/../views/persons.tpl');
}
