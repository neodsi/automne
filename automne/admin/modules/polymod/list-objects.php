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
// | Author: S�bastien Pauchet <sebastien.pauchet@ws-interactive.fr>      |
// +----------------------------------------------------------------------+
//
// $Id: list-objects.php,v 1.3 2009/06/22 15:36:16 sebastien Exp $

/**
  * PHP page : Load polyobjects items datas
  * Used accross an Ajax request.
  * Return formated items infos in JSON format
  *
  * @package CMS
  * @subpackage admin
  * @author S�bastien Pauchet <sebastien.pauchet@ws-interactive.fr>
  */

require_once($_SERVER["DOCUMENT_ROOT"]."/cms_rc_admin.php");

define("MESSAGE_PAGE_NO_ITEM", 530);
define("MESSAGE_PAGE_ITEM_NON_EXISTENT", 531);

//load interface instance
$view = CMS_view::getInstance();
//set default display mode for this page
$view->setDisplayMode(CMS_view::SHOW_JSON);

//get search vars
$objectId = sensitiveIO::request('objectId', 'sensitiveIO::isPositiveInteger');
$codename = sensitiveIO::request('module', CMS_modulesCatalog::getAllCodenames());
$fieldId = sensitiveIO::request('fieldId', 'sensitiveIO::isPositiveInteger');
$removeIds = sensitiveIO::request('removeIds');
$removeIds = $removeIds ? explode(',',$removeIds) : array();

$objectsDatas = array();
$objectsDatas['objects'] = array();

if (!$codename) {
	CMS_grandFather::raiseError('Unknown module ...');
	$view->setContent($objectsDatas);
	$view->show();
}
//CHECKS user has module clearance
if (!$cms_user->hasModuleClearance($codename, CLEARANCE_MODULE_EDIT)) {
	CMS_grandFather::raiseError('User has no rights on module : '.$codename);
	$view->setActionMessage($cms_language->getmessage(MESSAGE_ERROR_MODULE_RIGHTS, array($module->getLabel($cms_language))));
	$view->setContent($objectsDatas);
	$view->show();
}
//CHECKS objectId
if (!$objectId && !$fieldId) {
	CMS_grandFather::raiseError('Missing objectId to list in module '.$codename);
	$view->setContent($objectsDatas);
	$view->show();
} elseif (!$objectId && $fieldId) {
	$objectId = CMS_poly_object_catalog::getObjectIDForField($fieldId);
}

//load current object definition
$object = new CMS_poly_object_definition($objectId);

if (!$object->hasError()) {
	$objectsNames = CMS_poly_object_catalog::getListOfNamesForObject($objectId, false, array());
	if (is_array($objectsNames) && $objectsNames) {
		$objectsDatas['objects'][] = array(
			'id'			=> '0',
			'label'			=> '--',
		);
		foreach($objectsNames as $id => $label) {
			if (!$removeIds || !in_array($id, $removeIds)) {
				$objectsDatas['objects'][] = array(
					'id'			=> $id,
					'label'			=> html_entity_decode($label, ENT_COMPAT, strtoupper(APPLICATION_DEFAULT_ENCODING)),
				);
			}
		}
	} else {
		$objectsDatas['objects'][] = array(
			'id'			=> '0',
			'label'			=> $cms_language->getMessage(MESSAGE_PAGE_NO_ITEM, false, MOD_POLYMOD_CODENAME),
		);
	}
} else {
	$objectsDatas['objects'][] = array(
			'id'			=> '0',
			'label'			=> $cms_language->getMessage(MESSAGE_PAGE_ITEM_NON_EXISTENT, false, MOD_POLYMOD_CODENAME),
		);
}
$view->setContent($objectsDatas);
$view->show();
?>