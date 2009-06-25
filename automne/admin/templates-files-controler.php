<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Automne (TM)														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2000-2009 WS Interactive								  |
// +----------------------------------------------------------------------+
// | Automne is subject to version 2.0 or above of the GPL license.		  |
// | The license text is bundled with this package in the file			  |
// | LICENSE-GPL, and is available through the world-wide-web at		  |
// | http://www.gnu.org/copyleft/gpl.html.								  |
// +----------------------------------------------------------------------+
// | Author: S�bastien Pauchet <sebastien.pauchet@ws-interactive.fr>	  |
// +----------------------------------------------------------------------+
//
// $Id: templates-files-controler.php,v 1.2 2009/06/22 14:10:33 sebastien Exp $

/**
  * PHP controler : Receive actions on modules categories
  * Used accross an Ajax request to process one module categories action
  * 
  * @package CMS
  * @subpackage admin
  * @author S�bastien Pauchet <sebastien.pauchet@ws-interactive.fr>
  */

require_once($_SERVER["DOCUMENT_ROOT"]."/cms_rc_admin.php");

function checkNode($value) {
	return $value != 'source' && strpos($value, '..') === false;
}

define("MESSAGE_PAGE_STYLESHEET", 1486);
define("MESSAGE_PAGE_WYSIWYG", 1487);
define("MESSAGE_PAGE_JAVASCRIPT", 1488);
define("MESSAGE_ACTION_DELETE_FILE", 1500);
define("MESSAGE_ERROR_DELETE_FILE", 1501);
define("MESSAGE_ACTION_UPDATE_FILE", 1502);
define("MESSAGE_ERROR_UPDATE_FILE", 1503);
define("MESSAGE_ACTION_CREATE_FILE", 1504);
define("MESSAGE_ERROR_CREATE_FILE_EXTENSION", 1505);
define("MESSAGE_ERROR_CREATE_FILE_EXISTS", 1506);

//Controler vars
$action = sensitiveIO::request('action', array('delete', 'update', 'create'));
$fileType = sensitiveIO::request('type', array('css', 'js'));
$node = sensitiveIO::request('node', 'checkNode', '');
$definition = sensitiveIO::request('definition');
$filelabel = sensitiveIO::request('filelabel');

//load interface instance
$view = CMS_view::getInstance();
//set default display mode for this page
$view->setDisplayMode(CMS_view::SHOW_JSON);

//CHECKS user has module clearance
if (!$cms_user->hasAdminClearance(CLEARANCE_ADMINISTRATION_EDIT_TEMPLATES)) {
	CMS_grandFather::raiseError('User has no rights on page templates ...');
	$view->show();
}
if (!$node && $action != 'create') {
	CMS_grandFather::raiseError('Unknown node ...');
	$view->show();
}

switch ($fileType) {
	case 'css':
		$dir = $_SERVER['DOCUMENT_ROOT'].'/css/';
		$allowedFiles = array(
			'css' => array('name' => $cms_language->getMessage(MESSAGE_PAGE_STYLESHEET), 'class' => 'atm-css'),
			'xml' => array('name' => $cms_language->getMessage(MESSAGE_PAGE_WYSIWYG), 'class' => 'atm-xml'),
		);
	break;
	case 'js':
		$dir = $_SERVER['DOCUMENT_ROOT'].'/js/';
		$allowedFiles = array('js' => array('name' => $cms_language->getMessage(MESSAGE_PAGE_JAVASCRIPT), 'class' => 'atm-js'));
	break;
	default:
		CMS_grandFather::raiseError('Unknown fileType to use ...');
		$view->show();
	break;
}

$file = $dir.$node;
if (!is_file($file) && $action != 'create') {
	CMS_grandFather::raiseError('Action on folders is not allowed');
	$view->show();
}
if ($action != 'create') {
	$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
	if (!isset($allowedFiles[$extension])) {
		CMS_grandFather::raiseError('Action on this type of file is not allowed.');
		$view->show();
	}
}
$cms_message = '';
$content = array('success' => false);

switch ($action) {
	case 'delete':
		if (@unlink($file)) {
			$content = array('success' => true);
			$log = new CMS_log();
			$log->logMiscAction(CMS_log::LOG_ACTION_TEMPLATE_DELETE_FILE, $cms_user, "File : ".$node);
			$cms_message = $cms_language->getMessage(MESSAGE_ACTION_DELETE_FILE, array($node));
		} else {
			$cms_message = $cms_language->getMessage(MESSAGE_ERROR_DELETE_FILE).' '.$node;
		}
	break;
	case 'update':
		if (file_exists($file)) {
			$file = new CMS_file($file);
			if ($file->setContent($definition) && $file->writeToPersistence()) {
				$log = new CMS_log();
				$log->logMiscAction(CMS_log::LOG_ACTION_TEMPLATE_EDIT_FILE, $cms_user, "File : ".$node);
				$content = array('success' => true);
				$cms_message = $cms_language->getMessage(MESSAGE_ACTION_UPDATE_FILE, array($node));
			} else {
				$cms_message = $cms_language->getMessage(MESSAGE_ERROR_UPDATE_FILE).' '.$node;
			}
		}
	break;
	case 'create':
		if (is_dir($file)) {
			if (!is_file($dir.$node.'/'.$filelabel)) {
				$extension = strtolower(pathinfo($dir.$node.'/'.$filelabel, PATHINFO_EXTENSION));
				if (isset($allowedFiles[$extension])) {
					$file = new CMS_file($dir.$node.'/'.$filelabel);
					if ($file->setContent($definition) && $file->writeToPersistence()) {
						$log = new CMS_log();
						$log->logMiscAction(CMS_log::LOG_ACTION_TEMPLATE_EDIT_FILE, $cms_user, "File : ".$node.'/'.$filelabel);
						$content = array('success' => true);
						$cms_message = $cms_language->getMessage(MESSAGE_ACTION_CREATE_FILE, array($filelabel));
					} else {
						$cms_message = $cms_language->getMessage(MESSAGE_ERROR_UPDATE_FILE).' '.$filelabel;
					}
				} else {
					$cms_message = $cms_language->getMessage(MESSAGE_ERROR_CREATE_FILE_EXTENSION, array($filelabel));
				}
			} else {
				$cms_message = $cms_language->getMessage(MESSAGE_ERROR_CREATE_FILE_EXISTS, array($filelabel));
			}
		}
	break;
	default:
		CMS_grandFather::raiseError('Unknown action to do ...');
		$view->show();
	break;
}

//set user message if any
if ($cms_message) {
	$view->setActionMessage($cms_message);
}
if ($content) {
	$view->setContent($content);
}
$view->show();
?>