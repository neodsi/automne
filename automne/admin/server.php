<?php
// +----------------------------------------------------------------------+
// | Automne (TM)														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2000-2010 WS Interactive								  |
// +----------------------------------------------------------------------+
// | Automne is subject to version 2.0 or above of the GPL license.		  |
// | The license text is bundled with this package in the file			  |
// | LICENSE-GPL, and is available through the world-wide-web at		  |
// | http://www.gnu.org/copyleft/gpl.html.								  |
// +----------------------------------------------------------------------+
// | Author: Sébastien Pauchet <sebastien.pauchet@ws-interactive.fr>	  |
// +----------------------------------------------------------------------+
//
// $Id: server.php,v 1.11 2010/03/08 16:41:21 sebastien Exp $

/**
  * PHP page : Load server detail window.
  * Used accross an Ajax request. Render server informations.
  * 
  * @package CMS
  * @subpackage admin
  * @author Sébastien Pauchet <sebastien.pauchet@ws-interactive.fr>
  */

require_once($_SERVER["DOCUMENT_ROOT"]."/cms_rc_admin.php");

$winId = sensitiveIO::request('winId', '', 'serverWindow');

define("MESSAGE_TOOLBAR_HELP",1073);
define("MESSAGE_PAGE_NO_SERVER_RIGHTS",748);
define("MESSAGE_PAGE_AUTOMNE_PARAMS_TESTS",749);
define("MESSAGE_PAGE_CHECK_FILES_ACCESS",750);
define("MESSAGE_PAGE_FOR_AUTOMNE",751);
define("MESSAGE_PAGE_FOR_AUTOMNE_DESC",752);
define("MESSAGE_PAGE_FOR_USERS",753);
define("MESSAGE_PAGE_FOR_USERS_DESC",754);
define("MESSAGE_PAGE_SERVER_PARAMS",755);
define("MESSAGE_TOOLBAR_HELP_DESC",756);
define("MESSAGE_PAGE_CHECK",757);
define("MESSAGE_PAGE_IN_PROGRESS",758);
define("MESSAGE_PAGE_DETAIL",759);
define("MESSAGE_PAGE_FILE_ACCESS",760);
define("MESSAGE_PAGE_PHP_INFO",761);
define("MESSAGE_PAGE_UPDATES",762);
define("MESSAGE_PAGE_ERRORS_LOGS",1609);
define("MESSAGE_PAGE_CHECK_ERRORS_LOGS",1607);
define("MESSAGE_PAGE_PICK_A_DATE",1606);
define("MESSAGE_SELECT_FILE",534);
define("MESSAGE_PAGE_INCORRECT_FORM_VALUES", 682);
define("MESSAGE_PAGE_FIELD_CLICK_TO_CORRECT", 1184);
define("MESSAGE_PAGE_AUTOMNE_UPDATES",1611);
define("MESSAGE_PAGE_AUTOMNE_UPDATES_DESC",1612);
define("MESSAGE_PAGE_UPDATE_FILE",1613);
define("MESSAGE_PAGE_UPDATE_FILE_DESC",1614);
define("MESSAGE_PAGE_FORCED_UPDATE_DESC",1615);
define("MESSAGE_PAGE_FORCED_UPDATE",1616);
define("MESSAGE_PAGE_LAUNCH_UPDATE",1617);
define("MESSAGE_PAGE_UPDATE_REPORT",1618);

//load interface instance
$view = CMS_view::getInstance();
//set default display mode for this page
$view->setDisplayMode(CMS_view::SHOW_RAW);
//This file is an admin file. Interface must be secure
$view->setSecure();

//CHECKS user has admin clearance
if (!$cms_user->hasAdminClearance(CLEARANCE_ADMINISTRATION_EDITVALIDATEALL)) { //templates
	CMS_grandFather::raiseError('User has no administration rights');
	$view->setActionMessage($cms_language->getMessage(MESSAGE_PAGE_NO_SERVER_RIGHTS));
	$view->show();
}

//Test all PHP requirements

//Test PHP version
$content = '
<h1>'.$cms_language->getMessage(MESSAGE_PAGE_AUTOMNE_PARAMS_TESTS).'</h1>
<ul class="atm-server">';
if (version_compare(PHP_VERSION, "5.2.0") === -1) {
	$content .= '<li class="atm-pic-cancel">Error, PHP version ('.PHP_VERSION.') not match</li>';
} else {
	$content .= '<li class="atm-pic-ok">PHP version OK ('.PHP_VERSION.')</li>';
}
//GD
if (!function_exists('imagecreatefromgif') || !function_exists('imagecreatefromjpeg') || !function_exists('imagecreatefrompng')) {
	$content .= '<li class="atm-pic-cancel">Error, GD extension not installed</li>';
} else {
	$content .= '<li class="atm-pic-ok">GD extension OK</li>';
}
//MySQL
if (!class_exists('PDO')) {
	$content .= '<li class="atm-pic-cancel">Error, PDO extension not installed</li>';
} else {
	$q = new CMS_query('SELECT VERSION() as v;');
	if ($q->hasError()) {
		$content .= '<li class="atm-pic-cancel">Error, MySQL connection error. Please check /config.php file for correct connection informations.</li>';
	} else {
		$version = $q->getValue('v');
		if (version_compare($version, '5.0.0') === -1) {
			$content .= '<li class="atm-pic-cancel">Error, MySQL version ('.$version.') not match (5.0.0 minimum)</li>';
		} else {
			$content .= '<li class="atm-pic-ok">MySQL connection and version OK ('.$version.')</li>';
		}
	}
}
//MBSTRING
if (!function_exists('mb_substr') || !function_exists('mb_convert_encoding')) {
	$content .= '<li class="atm-pic-cancel">Error, Multibyte String (mbsring) extension not installed (only needed if UTF-8 encoding is used)</li>';
} else {
	$content .= '<li class="atm-pic-ok">Multibyte String (mbsring) extension OK</li>';
}
//LDAP
if (!defined('APPLICATION_LDAP_AUTH') || (defined('APPLICATION_LDAP_AUTH') && APPLICATION_LDAP_AUTH)) {
	if (!function_exists('ldap_bind')) {
		$content .= '<li class="atm-pic-cancel">Error, LDAP extension not installed (only needed if LDAP authentification is used)</li>';
	} else {
		$content .= '<li class="atm-pic-ok">LDAP extension OK</li>';
	}
}
//XAPIAN
if (class_exists('CMS_module_ase')) {
	$xapianVersion = '';
	if (function_exists('xapian_version_string')) {
		$xapianVersion = xapian_version_string();
	} elseif (class_exists('Xapian')) {
		$xapianVersion = Xapian::version_string();
	} else {
		$content .= '<li class="atm-pic-cancel">Error, Xapian extension not installed (only needed if ASE module is installed)</li>';
	}
	if ($xapianVersion) {
		if (version_compare($xapianVersion, '1.0.2') === -1) {
			$content .= '<li class="atm-pic-cancel">Error, Xapian version ('.$xapianVersion.') not match (1.0.2 minimum)</li>';
		} else {
			$content .= '<li class="atm-pic-ok">Xapian extension OK ('.$xapianVersion.')</li>';
		}
	}
}
//Files writing
$randomFile = realpath($_SERVER['DOCUMENT_ROOT']).'/test_'.md5(mt_rand().microtime()).'.tmp';
if (!is_writable(realpath($_SERVER['DOCUMENT_ROOT']))) {
	$content .= '<li class="atm-pic-cancel">Error, No permissions to write files on website root directory ('.realpath($_SERVER['DOCUMENT_ROOT']).')</li>';
} else {
	$content .= '<li class="atm-pic-ok">Website root filesystem permissions are OK</li>';
}
//Email
if (!@mail("root@localhost", "Automne SMTP Test", "Automne SMTP Test")) {
	$content .= '<li class="atm-pic-cancel">Error, No SMTP server founded</li>';
} else {
	$content .= '<li class="atm-pic-ok">SMTP server OK</li>';
}
//Memory
@ini_set('memory_limit', "32M");
if (ini_get('memory_limit') && ini_get('memory_limit') < 32) {
	$content .= '<li class="atm-pic-cancel">Error, Cannot upgrade memory limit to 32M. Memory detected : '.ini_get('memory_limit')."\n";
} else {
	$content .= '<li class="atm-pic-ok">Memory limit OK</li>';
}
//CLI
if (io::strtolower(io::substr(PHP_OS, 0, 3)) === 'win') {
	if (defined('PATH_PHP_CLI_WINDOWS') && PATH_PHP_CLI_WINDOWS && is_file(PATH_PHP_CLI_WINDOWS)) {
		//test CLI version
		$return = CMS_patch::executeCommand(PATH_PHP_CLI_WINDOWS.' -v',$error);
		if ($error && $return !== false) {
			$content .= '<li class="atm-pic-cancel">Error when testing php CLI with command "'.PATH_PHP_CLI_WINDOWS.' -v" : '.$error."\n";
		}
		if ($return === false) {
			$content .= '<li class="atm-pic-cancel">Error, passthru() and exec() commands not available</li>';
		}
		if (io::strpos(io::strtolower($return), '(cli)') === false) {
			$content .= '<li class="atm-pic-cancel">Error, installed php is not the CLI version : '.$return."\n";
		} else {
			$cliversion = trim(str_replace('php ', '', io::substr(io::strtolower($return), 0, io::strpos(io::strtolower($return), '(cli)'))));
			if (version_compare($cliversion, "5.2.0") === -1) {
				$content .= '<li class="atm-pic-cancel">Error, PHP CLI version ('.$cliversion.') not match</li>';
			} else {
				$content .= '<li class="atm-pic-ok">PHP CLI version OK ('.$cliversion.')</li>';
			}
		}
	} else {
		$content .= '<li class="atm-pic-question">CLI is not set. Define constant PATH_PHP_CLI_WINDOWS in config.php to set it</li>';
	}
} else {
	$error = '';
	if (!defined('PATH_PHP_CLI_UNIX') || !PATH_PHP_CLI_UNIX) {
		$return = CMS_patch::executeCommand('which php 2>&1',$error);
		if ($error && $return !== false) {
			$content .= '<li class="atm-pic-cancel">Error when finding php CLI with command "which php" : '.$error."\n";
		}
		if ($return === false) {
			$content .= '<li class="atm-pic-cancel">Error, passthru() and exec() commands not available</li>';
		} elseif (io::substr($return,0,1) != '/') {
			$content .= '<li class="atm-pic-cancel">Error when finding php CLI with command "which php"</li>';
		}
		//test CLI version
		$return = CMS_patch::executeCommand('php -v',$error);
	} else {
		//test CLI version
		$return = CMS_patch::executeCommand(PATH_PHP_CLI_UNIX.' -v',$error);
		if ($error && $return !== false) {
			$content .= '<li class="atm-pic-cancel">Error when testing php CLI with command "'.PATH_PHP_CLI_UNIX.' -v" : '.$error."\n";
		}
		if ($return === false) {
			$content .= '<li class="atm-pic-cancel">Error, passthru() and exec() commands not available</li>';
		}
	}
	if (io::strpos(io::strtolower($return), '(cli)') === false) {
		$content .= '<li class="atm-pic-cancel">Error, installed php is not the CLI version : '.$return."\n";
	} else {
		$cliversion = trim(str_replace('php ', '', io::substr(io::strtolower($return), 0, io::strpos(io::strtolower($return), '(cli)'))));
		if (version_compare($cliversion, "5.2.0") === -1) {
			$content .= '<li class="atm-pic-cancel">Error, PHP CLI version ('.$cliversion.') not match</li>';
		} else {
			$content .= '<li class="atm-pic-ok">PHP CLI version OK ('.$cliversion.')</li>';
		}
	}
}

//Conf PHP
//try to change some misconfigurations
@ini_set('magic_quotes_gpc', 0);
@ini_set('magic_quotes_runtime', 0);
@ini_set('magic_quotes_sybase', 0);
if (ini_get('magic_quotes_gpc') != 0) {
	$content .= '<li class="atm-pic-cancel">Error, PHP magic_quotes_gpc is active and cannot be changed</li>';
}
if (ini_get('magic_quotes_runtime') != 0) {
	$content .= '<li class="atm-pic-cancel">Error, PHP magic_quotes_runtime is active and cannot be changed</li>';
}
if (ini_get('magic_quotes_sybase') != 0) {
	$content .= '<li class="atm-pic-cancel">Error, PHP magic_quotes_sybase is active and cannot be changed</li>';
}
if (ini_get('register_globals') != 0) {
	$content .= '<li class="atm-pic-cancel">Error, PHP register_globals is active and cannot be changed</li>';
}
if (ini_get('allow_url_include') != 0) {
	$content .= '<li class="atm-pic-cancel">Error, PHP allow_url_include is active and cannot be changed</li>';
}
//check for safe_mode
if (ini_get('safe_mode') && strtolower(ini_get('safe_mode')) != 'off') {
	$content .= '<li class="atm-pic-cancel">Error, PHP safe_mode is active</li>';
}
$content .='</ul>';

$content = sensitiveIO::sanitizeJSString($content);

//Files tab
$filescontent = '
<h1>'.$cms_language->getMessage(MESSAGE_PAGE_CHECK_FILES_ACCESS).'</h1>
	<h2>'.$cms_language->getMessage(MESSAGE_PAGE_FOR_AUTOMNE).'</h2>
	'.$cms_language->getMessage(MESSAGE_PAGE_FOR_AUTOMNE_DESC).'<br /><br />
	<div id="launchFilesCheck"></div><br />
	<div id="filesCheckProgress"></div><br />
	<div id="filesCheckDetail"></div>
	<br />
	<h2>'.$cms_language->getMessage(MESSAGE_PAGE_FOR_USERS).'</h2>
	'.$cms_language->getMessage(MESSAGE_PAGE_FOR_USERS_DESC).'<br /><br />
	<div id="launchHtaccessCheck"></div><br />
	<div id="htaccessCheckProgress"></div><br />
	<div id="htaccessCheckDetail"></div>
';
$filescontent = sensitiveIO::sanitizeJSString($filescontent);

//Errors Tab
$dateFormat = $cms_language->getDateFormat();
$errorcontent = '
<h1>'.$cms_language->getMessage(MESSAGE_PAGE_CHECK_ERRORS_LOGS).'</h1>
<div id="errorsDate"></div><br />
<div id="serverErrorsDetails"></div>';
$errorcontent = sensitiveIO::sanitizeJSString($errorcontent);

//Update Tab
$fileDatas = array(
	'filename'		=> '',
	'filepath'		=> '',
	'filesize'		=> '',
	'fileicon'		=> '',
	'extension'		=> '',
);
$maxFileSize = CMS_file::getMaxUploadFileSize('K');
$filePath = $fileDatas['filepath'];
$fileDatas = sensitiveIO::jsonEncode($fileDatas);

$updatecontent = '
<h1>'.$cms_language->getMessage(MESSAGE_PAGE_AUTOMNE_UPDATES).'</h1>
<p>'.$cms_language->getMessage(MESSAGE_PAGE_AUTOMNE_UPDATES_DESC).'</p><br />
<div id="updateForm"></div><br />
<div id="updateDetails"></div>';
$updatecontent = sensitiveIO::sanitizeJSString($updatecontent);

$jscontent = <<<END
	var serverWindow = Ext.getCmp('{$winId}');
	//set window title
	serverWindow.setTitle('{$cms_language->getJsMessage(MESSAGE_PAGE_SERVER_PARAMS)}');
	//set help button on top of page
	serverWindow.tools['help'].show();
	//add a tooltip on button
	var propertiesTip = new Ext.ToolTip({
		target:		 serverWindow.tools['help'],
		title:			 '{$cms_language->getJsMessage(MESSAGE_TOOLBAR_HELP)}',
		html:			 '{$cms_language->getJsMessage(MESSAGE_TOOLBAR_HELP_DESC)}',
		dismissDelay:	0
	});
	
	//create files objects
	var progressFiles = new Ext.ProgressBar({
		id:				'filesCheckProgressBar'
    });
	var progressHtaccess = new Ext.ProgressBar({
		id:				'htaccessCheckProgressBar'
    });
	var launchFilesCheck = new Ext.Button({
		id:				'launchFilesCheck',
		text:			'{$cms_language->getJsMessage(MESSAGE_PAGE_CHECK)}',
		listeners:		{'click':function(){
			launchFilesCheck.disable();
	        progressFiles.wait({
	            interval:	200,
	            increment:	15,
				text:		'{$cms_language->getJsMessage(MESSAGE_PAGE_IN_PROGRESS)}'
	        });
			Automne.server.call({
				url:				'server-check.php',
				params: 			{
					action:				'check-files'
				},
				fcnCallback: 		function(response, options, content) {
					if (!filesCheckDetail.rendered) {
						filesCheckDetail.render('filesCheckDetail');
					}
					Ext.get('filesCheckDetailText').dom.innerHTML = content;
					progressFiles.updateText('').reset();
					launchFilesCheck.enable();
				},
				callBackScope:		this
			});
		},scope:this}
    });
	var launchHtaccessCheck = new Ext.Button({
		id:				'launchHtaccessCheck',
		text:			'{$cms_language->getJsMessage(MESSAGE_PAGE_CHECK)}',
		listeners:		{'click':function(){
			launchHtaccessCheck.disable();
	        progressHtaccess.wait({
	            interval:	200,
	            increment:	15,
				text:		'{$cms_language->getJsMessage(MESSAGE_PAGE_IN_PROGRESS)}'
	        });
			Automne.server.call({
				url:				'server-check.php',
				params: 			{
					action:				'check-htaccess'
				},
				fcnCallback: 		function(response, options, content) {
					if (!htaccessCheckDetail.rendered) {
						htaccessCheckDetail.render('htaccessCheckDetail');
					}
					Ext.get('htaccessCheckDetailText').dom.innerHTML = content;
					progressHtaccess.updateText('').reset();
					launchHtaccessCheck.enable();
				},
				callBackScope:		this
			});
		},scope:this}
    });
	var filesCheckDetail = new Ext.form.FieldSet({
		title:			'{$cms_language->getJsMessage(MESSAGE_PAGE_DETAIL)}',
		collapsible:	true,
		collapsed:		false,
		height:			220,
		autoScroll:		true,
		html:			'<div id="filesCheckDetailText"></div>'
	});
	var htaccessCheckDetail = new Ext.form.FieldSet({
		title:			'{$cms_language->getJsMessage(MESSAGE_PAGE_DETAIL)}',
		collapsible:	true,
		collapsed:		false,
		height:			220,
		autoScroll:		true,
		html:			'<div id="htaccessCheckDetailText"></div>'
	});
	
	//Server errors tab
	var errorsDate = new Ext.form.FormPanel({
		layout: 		'form',
		labelWidth:		135,
		border:			false,
		defaults: {
			anchor:			'97%',
			allowBlank:		true
		},
		items:[{
			id:				'errorsDatePicker',
			name:			'errorsDatePicker',
			fieldLabel:		'{$cms_language->getJsMessage(MESSAGE_PAGE_PICK_A_DATE)}',
			value:			'',
			xtype:			'datefield',
			format:			'{$dateFormat}',
			width:			100,
			anchor:			false,
			listeners:		{'select':function(field){
				if (field.isValid()) {
					Automne.server.call({
						url:				'server-errors.php',
						isUpload:			true,
						params: 			{
							date:				field.getRawValue()
						},
						success: 		function(response, options) {
							if (!serverErrorsDetails.rendered) {
								serverErrorsDetails.render('serverErrorsDetails');
							}
							serverErrorsDetails.setWidth(Ext.getCmp('serverErrors').getWidth() - 20);
							serverErrorsDetails.setHeight(Ext.getCmp('serverErrors').getHeight() - 85);
							Ext.get('serverErrorsDetailsText').dom.innerHTML = response.responseText;
						},
						callBackScope:		this
					});
				}
			}}
		}]
	});
	var serverErrorsDetails = new Ext.form.FieldSet({
		title:			'{$cms_language->getJsMessage(MESSAGE_PAGE_ERRORS_LOGS)}',
		collapsible:	false,
		collapsed:		false,
		width:			300,
		height:			300,
		autoScroll:		true,
		html:			'<pre id="serverErrorsDetailsText"></pre>'
	});
	
	//Update tab
	var updateForm = new Automne.FormPanel({
		id:				'form-update',
		layout: 		'form',
		border:			false,
		autoWidth:		true,
		autoHeight:		true,
		labelWidth:		135,
		labelAlign:		'right',
		defaults: {
			anchor:			'97%'
		},
		items:[{
            xtype: 			'atmFileUploadField',
            id: 			'form-update-file',
            emptyText: 		'{$cms_language->getJSMessage(MESSAGE_SELECT_FILE)}',
            fieldLabel: 	'<span class=\"atm-red\">*</span> {$cms_language->getJsMessage(MESSAGE_PAGE_UPDATE_FILE)}',
            name: 			'filename',
			allowBlank:		false,
            uploadCfg:	{
				file_size_limit:		'{$maxFileSize}',
				file_types:				'*.gz;*.tgz',
				file_types_description:	'{$cms_language->getJsMessage(MESSAGE_PAGE_UPDATE_FILE_DESC)}'
			},
			fileinfos:	{$fileDatas}
        },{
			fieldLabel:		'',
			name:			'force',
			inputValue:		'1',
			xtype:			'checkbox',
			boxLabel:		'<span ext:qtip="{$cms_language->getJsMessage(MESSAGE_PAGE_FORCED_UPDATE_DESC)}" class="atm-help"><span class=\"atm-red\">/!\\\</span> {$cms_language->getJsMessage(MESSAGE_PAGE_FORCED_UPDATE)}</span>'
		}],
		buttons:[{
			text:			'{$cms_language->getJsMessage(MESSAGE_PAGE_LAUNCH_UPDATE)}',
			xtype:			'button',
			iconCls:		'atm-pic-validate',
			name:			'submitAdmin',
			handler:		function() {
				var form = Ext.getCmp('form-update').getForm();
				if (form.isValid()) {
					Automne.server.call({
						url:				'server-update.php',
						isUpload:			true,
						params: 			form.getValues(),
						success: 		function(response, options) {
							Ext.getCmp('form-update-file').deleteFile();
							if (!updateDetails.rendered) {
								updateDetails.render('updateDetails');
							}
							updateDetails.setWidth(Ext.getCmp('updatesPanel').getWidth() - 18);
							updateDetails.setHeight(Ext.getCmp('updatesPanel').getHeight() - 225);
							Ext.get('updateDetailsText').update(response.responseText, true);
						},
						callBackScope:		this
					});
				} else {
					Automne.message.show('{$cms_language->getJSMessage(MESSAGE_PAGE_INCORRECT_FORM_VALUES)}', '', serverWindow);
				}
			},
			scope:			this
		}]
	});
	var updateDetails = new Ext.form.FieldSet({
		title:			'{$cms_language->getJsMessage(MESSAGE_PAGE_UPDATE_REPORT)}',
		collapsible:	false,
		collapsed:		false,
		width:			300,
		height:			300,
		autoScroll:		true,
		html:			'<pre id="updateDetailsText"></pre>'
	});
	
	serverWindow.correctUpdateErrors = function() {
		var correctErrorsButton = new Ext.Button({
			id:				'correctErrorsButton',
			text:			'{$cms_language->getJsMessage(MESSAGE_PAGE_FIELD_CLICK_TO_CORRECT)}',
			listeners:		{'click':function(t){
				//create window element
				var window = new Automne.frameWindow({
					id:				'server-update-correct-errors',
					frameURL:		'/automne/admin-v3/patch_error_correction.php',
					allowFrameNav:	true,
					width:			750,
					height:			580,
					animateTarget:	t,
					listeners:{'close':function(){
						Automne.server.call({
							url:				'server-update.php',
							isUpload:			true,
							params: 			{cms_action:'errorsCorrected'},
							success: 			function(response, options) {
								if (!updateDetails.rendered) {
									updateDetails.render('updateDetails');
								}
								updateDetails.setWidth(Ext.getCmp('updatesPanel').getWidth() - 20);
								updateDetails.setHeight(Ext.getCmp('updatesPanel').getHeight() - 225);
								Ext.get('updateDetailsText').update(response.responseText, true);
							},
							callBackScope:		this
						});
					}}
				});
				window.show();
			},scope:this}
	    });
		if (!correctErrorsButton.rendered) {
			correctErrorsButton.render('correctUpdateErrors');
		}
	}
	
	//create center panel
	var center = new Ext.TabPanel({
		activeTab:			 0,
		id:					'serverPanels',
		region:				'center',
		border:				false,
		enableTabScroll:	true,
		listeners: {
			'beforetabchange' : function(tabPanel, newTab, currentTab ) {
				if (newTab.beforeActivate) {
					newTab.beforeActivate(tabPanel, newTab, currentTab);
				}
				return true;
			},
			'tabchange': function(tabPanel, newTab) {
				if (newTab.afterActivate) {
					newTab.afterActivate(tabPanel, newTab);
				}
			},
			'resize': function() {
				if (serverErrorsDetails.rendered) {
					serverErrorsDetails.setWidth(Ext.getCmp('serverErrors').getWidth() - 20);
					serverErrorsDetails.setHeight(Ext.getCmp('serverErrors').getHeight() - 85);
				}
				if (updateDetails.rendered) {
					updateDetails.setWidth(Ext.getCmp('updatesPanel').getWidth() - 20);
					updateDetails.setHeight(Ext.getCmp('updatesPanel').getHeight() - 225);
				}
			}
		},
		items:[{
			id:					'serverDatas',
			title:				'{$cms_language->getJsMessage(MESSAGE_PAGE_SERVER_PARAMS)}',
			autoScroll:			true,
			border:				false,
			bodyStyle: 			'padding:5px',
			html: 				'$content'
		},{
			id:					'serverFiles',
			title:				'{$cms_language->getJsMessage(MESSAGE_PAGE_FILE_ACCESS)}',
			autoScroll:			true,
			border:				false,
			bodyStyle: 			'padding:5px',
			html:				'$filescontent',
			listeners:			{'activate':function(){
				if (!progressFiles.rendered) {
					progressFiles.render('filesCheckProgress');
				}
				if (!launchFilesCheck.rendered) {
					launchFilesCheck.render('launchFilesCheck');
				}
				if (!progressHtaccess.rendered) {
					progressHtaccess.render('htaccessCheckProgress');
				}
				if (!launchHtaccessCheck.rendered) {
					launchHtaccessCheck.render('launchHtaccessCheck');
				}
			}, scope:this}
		},{
			xtype:				'framePanel',
			title:				'{$cms_language->getJsMessage(MESSAGE_PAGE_PHP_INFO)}',
			id:					'phpDatas',
			frameURL:			'/automne/admin/phpinfo.php',
			allowFrameNav:		true
		},{
			id:					'serverErrors',
			title:				'{$cms_language->getJsMessage(MESSAGE_PAGE_ERRORS_LOGS)}',
			autoScroll:			true,
			border:				false,
			bodyStyle: 			'padding:5px',
			html: 				'$errorcontent',
			listeners:			{'activate':function(){
				if (!errorsDate.rendered) {
					errorsDate.render('errorsDate');
				}
			}, scope:this}
		},{
			id:					'updatesPanel',
			title:				'{$cms_language->getJsMessage(MESSAGE_PAGE_UPDATES)}',
			autoScroll:			true,
			border:				false,
			bodyStyle: 			'padding:5px',
			html: 				'$updatecontent',
			listeners:			{'activate':function(){
				if (!updateForm.rendered) {
					updateForm.render('updateForm');
				}
			}, scope:this}
		}]
	});
	
	serverWindow.add(center);
	//redo windows layout
	serverWindow.doLayout();
	//center.syncSize();
END;
$view->addJavascript($jscontent);
$view->show();
?>