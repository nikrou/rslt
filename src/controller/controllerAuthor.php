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

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$page_title = __('New author');
$author = array('firstname' => '',
		'lastname' => ''
		);

$author_manager = new authorManager($core);

if (($action=='remove') && !empty($_POST['authors'])
    && $_POST['object']=='author') {

  $author_manager->delete($_POST['authors']);
  $_SESSION['rslt_message'] = __('Author(s) successfully deleted.');
  $_SESSION['rslt_default_tab'] = 'authors';
  http::redirect($p_url);
}

if (($action=='edit') && !empty($_GET['id'])) {
  $rs = $author_manager->findById($_GET['id']);
  if (!$rs->isEmpty()) {
    $author['firstname'] = $rs->firstname;
    $author['lastname'] = $rs->lastname;
    $_SESSION['author_id'] = $_GET['id'];
  }
}

if (!empty($_POST['save_author'])
    && !empty($_POST['author_firstname'])
    && !empty($_POST['author_lastname'])) {

  $author['firstname'] = $_POST['author_firstname'];
  $author['lastname'] = $_POST['author_lastname'];

  if ($action=='edit') {
    $method = 'update';
    $message = __('Author has been successfully updated.');
    $author['id'] = $_SESSION['author_id'];
    unset($_SESSION['author_id']);
  } else {
    $method = 'add';
    $message = __('Author has been successfully added.');
  }
  try {
    $author_manager->$method($author);
    $_SESSION['rslt_message'] = $message;
    $_SESSION['rslt_default_tab'] = 'authors';
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

include(dirname(__FILE__).'/../views/form_author.tpl');
