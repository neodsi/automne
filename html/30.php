<?php //Generated on Tue, 23 Jun 2009 18:05:13 +0200 by Automne (TM) 4.0.0rc1
if (!isset($cms_page_included) && !$_POST && !$_GET) {
	header('HTTP/1.x 301 Moved Permanently', true, 301);
	header('Location: http://127.0.0.1/web/fr/30-notions-essentielles.php');
	exit;
}
require_once($_SERVER["DOCUMENT_ROOT"]."/cms_rc_frontend.php");
 ?><?php if (defined('APPLICATION_XHTML_DTD')) echo APPLICATION_XHTML_DTD."\n";  ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
		<title>Automne 4 : Pr�-requis</title>
			<?php echo CMS_view::getCSS(array('/css/common.css','/css/interieur.css'), 'screen');  ?>

		<!--[if lte IE 6]> 
		<link rel="stylesheet" type="text/css" href="/css/ie6.css" media="screen" />
		<![endif]-->
		<?php echo CMS_view::getJavascript(array('/js/sifr.js','/js/common.js','/js/CMS_functions.js'));  ?>

		<link rel="icon" type="image/x-icon" href="http://127.0.0.1/favicon.ico" />
	<meta name="language" content="fr" />
	<meta name="generator" content="Automne (TM)" />
	<meta name="identifier-url" content="http://127.0.0.1" />

	</head>
	<body>
		<div id="main">
			<div id="container">
				<div id="header">
					
								

<a id="lienAccueil" href="http://127.0.0.1/web/fr/2-accueil.php" title="Retour � l'accueil">Retour � l'accueil</a>


							
				</div>
				<div id="backgroundBottomContainer">
					<div id="menuLeft">
						<ul class="CMS_lvl1"><li class="CMS_lvl1 CMS_open "><a class="CMS_lvl1" href="http://127.0.0.1/web/fr/2-accueil.php">Accueil</a><ul class="CMS_lvl2"><li class="CMS_lvl2 CMS_open "><a class="CMS_lvl2" href="http://127.0.0.1/web/fr/3-presentation.php">Pr�sentation</a><ul class="CMS_lvl3"><li class="CMS_lvl3 CMS_nosub "><a class="CMS_lvl3" href="http://127.0.0.1/web/fr/29-automne-v4.php">Automne</a></li><li class="CMS_lvl3 CMS_nosub "><a class="CMS_lvl3" href="http://127.0.0.1/web/fr/33-nouveautes.php">Nouveaut�s</a></li><li class="CMS_lvl3 CMS_nosub CMS_current"><a class="CMS_lvl3" href="http://127.0.0.1/web/fr/30-notions-essentielles.php">Pr�-requis</a></li></ul></li><li class="CMS_lvl2 CMS_sub "><a class="CMS_lvl2" href="http://127.0.0.1/web/fr/24-documentation.php">Fonctionnalit�s</a></li><li class="CMS_lvl2 CMS_sub "><a class="CMS_lvl2" href="http://127.0.0.1/web/fr/31-exemples-de-modules.php">Exemples de modules</a></li></ul></li></ul>
					</div>
					<div id="content" class="page30">
						<div id="breadcrumbs">
							<a href="http://127.0.0.1/web/fr/2-accueil.php">Accueil</a>

 &gt; 
<a href="http://127.0.0.1/web/fr/3-presentation.php">Pr�sentation</a>

 &gt; 
						</div>
						<div id="title">
							<h1>Pr�-requis</h1>
						</div>
						
	
	
		<div class="text"><h3>L'installation / utilisation d'Automne 4 n&eacute;cessite certains  pr&eacute;-requis :</h3> <h2>Pr&eacute;-requis techniques obligatoires</h2> <h3>Serveur Linux, Windows, Max OSX, Solaris, BSD, ou tout autre syst&egrave;me syst&egrave;me Unix permettant de faire tourner les trois outils suivant sur lesquels repose Automne :</h3>    <ul><li>Serveur <a href="http://httpd.apache.org/">Apache</a>.</li><li><a href="http://www.php.net/">PHP 5.2.x</a>. Pour des raisons de s&eacute;curit&eacute; nous recommandons la derni&egrave;re version de la branche 5.x.<ul><li>Extension GD disponible pour PHP (permet le <a href="http://www.php.net/manual/fr/ref.image.php">traitement des images</a>) avec les librairies JPEG, GIF et PNG.</li><li>Option &quot;<a href="http://fr2.php.net/manual/fr/features.safe-mode.php">safe_mode</a>&quot; de PHP d&eacute;sactiv&eacute;e.</li><li>32 &agrave; 64Mo de m&eacute;moire allou&eacute; aux scripts PHP (en fonction du nombre d'extensions install&eacute;es sur PHP : plus d'extensions n&eacute;cessite plus de m&eacute;moire).</li></ul></li><li><a href="http://www.mysql.com/">MySQL 5.x .</a></li></ul>  <h3>Pour l'admnistration d'Automne : Internet Explorer 7, Firefox 3, Safari 3, Google Chrome, Opera 9</h3><p>Les pr&eacute;-requis en terme de navigateur du site public d&eacute;pendent des mod&egrave;les utilis&eacute;s pour cr&eacute;er les pages.</p> <p>&nbsp;</p></div>
	


<div class="text"><h2>Pr&eacute;-requis conseill&eacute;s</h2><ul><li>PHP install&eacute; sous forme de module Apache (la version CGI offre des performances moindres).</li><li><a href="http://fr.php.net/manual/fr/features.commandline.php">Module CLI de PHP install&eacute; </a>et disponible sur le serveur ainsi que les fonctions &quot;<a href="http://fr.php.net/system">system</a>&quot; et &quot;<a href="http://fr2.php.net/manual/fr/function.exec.php">exec</a>&quot; de PHP pour profiter des scripts en tache de fond.</li><li>Option<a href="http://fr2.php.net/manual/fr/ref.info.php#ini.magic-quotes-gpc"> &quot;magic_quotes_gpc&quot;</a> de PHP d&eacute;sactiv&eacute;e.</li><li>Apache doit avoir le droit de cr&eacute;er et de modifier l&rsquo;ensemble des fichiers d'Automne sur le serveur pour profiter du syst&egrave;me d&rsquo;installation et de mise &agrave; jour automatique. Sans cela, certaines parties de l&rsquo;installation et des mises &agrave; jour devront &ecirc;tre effectu&eacute;es manuellement.</li><li>Un cache de code PHP (opcode cache) tel que <a href="http://pecl.php.net/package/APC">APC</a> ou <a href="http://www.zend.com/products/zend_optimizer">Zend optimizer </a>est un plus pour les performances.</li><li>Certaines fonctionnalit&eacute;s d&rsquo;Automne (telle que la g&eacute;n&eacute;ration des pages du site) peuvent n&eacute;cessiter plus de m&eacute;moire vive (en particulier si vous avez compil&eacute; PHP avec un tr&egrave;s grand nombre d'extensions). En r&egrave;gle g&eacute;n&eacute;rale il est pr&eacute;f&eacute;rable de laisser PHP g&eacute;rer lui m&ecirc;me la m&eacute;moire vive allou&eacute; aux scripts en permettant l'usage de la fonction<a href="http://fr2.php.net/manual/fr/ini.core.php#ini.memory-limit"> &quot;memory_limit&quot;</a>.</li></ul><h3>Pour des raisons de performance, nous recommandons l&rsquo;usage d&rsquo;un serveur Linux ou Unix en production.</h3><h3>Du fait de l&rsquo;emploi de fichiers .htaccess, le serveur Apache est fortement conseill&eacute;.</h3></div>


						<a href="#header" id="top" title="haut de la page">Haut</a>
					</div>
					<div class="spacer"></div>
				</div>
			</div>
		</div>
		<div id="footer">
			<div id="menuBottom">
				<ul>
					<li><a href="http://127.0.0.1/web/fr/8-plan-du-site.php">Plan du site</a></li>
<li><a href="http://127.0.0.1/web/fr/9-contact.php">Contact</a></li>
				</ul>
				<div class="spacer"></div>
			</div>
		</div>
	<?php if (SYSTEM_DEBUG && STATS_DEBUG) {view_stat();}  ?>
</body>
</html>