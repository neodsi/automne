<?php require_once(/var/smb/clients/automne4/www-rev/trunk."/cms_rc_frontend.php");
CMS_view::redirect('http://test-folder/trunk/web/demo/2-accueil.php', true, 302);
 ?><?php //Generated on Thu, 11 Mar 2010 17:06:41 +0100 by Automne (TM) 4.0.1
require_once(dirname(__FILE__).'/../cms_rc_frontend.php');
if (!isset($cms_page_included) && !$_POST && !$_GET) {
	CMS_view::redirect('http://test-folder/trunk/web/1-demo-automne.php', true, 301);
}
 ?><?php if (defined('APPLICATION_XHTML_DTD')) echo APPLICATION_XHTML_DTD."\n";   ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
	<?php echo '<meta http-equiv="Content-Type" content="text/html; charset='.strtoupper(APPLICATION_DEFAULT_ENCODING).'" />';     ?>
	<link rel="icon" type="image/x-icon" href="http://test-folder/trunk/favicon.ico" />
	<meta name="language" content="fr" />
	<meta name="generator" content="Automne (TM)" />
	<meta name="identifier-url" content="http://test-folder/trunk" />
	<base href="http://test-folder/trunk/" />	<script type="text/javascript" src="/trunk/js/CMS_functions.js"></script>

	<style type="text/css">
		body{
			background-color:	#e9f1da;
			font-family:		arial,verdana,helvetica,sans-serif;
			font-size:			12px;
			margin:				0;
			padding:			0;
		}
		#atm-center{
			position:			absolute;
			left:				45%;
			top:				40%;
			padding:			2px;
			z-index:			20001;
		    height:				auto;
		}
		.atm-alert {
			position:			absolute;
			top:				0px;
			left:				0px;
			width:				400px;
			border:				1px solid #dde6cb;
			text-align:			center;
			background:			#FFFFFF url(<?php echo PATH_REALROOT_WR;     ?>/automne/admin/img/logo_small.gif) top right no-repeat;
			padding:			20px;
			left:				-150px;
			margin:				0 auto 0 auto;
		}
		hr {
			border:				1px solid #dde6cb;
		}
	</style>
</head>
<body>
<!-- empty template, only used for pages with redirection -->
<div id="atm-center">
	<div class="atm-alert">Cette page utilise le mod&egrave;le "Splash" qui ne comporte aucun contenu.<hr />This page use template "Splash" which does not have any content.</div>
</div>
<?php if (SYSTEM_DEBUG && STATS_DEBUG) {view_stat();}   ?>
</body>
</html>