<?php //Generated on Wed, 19 May 2010 18:19:49 +0200 by Automne (TM) 4.0.2
require_once($_SERVER["DOCUMENT_ROOT"]."/cms_rc_frontend.php");
if (!isset($cms_page_included) && !$_POST && !$_GET) {
	CMS_view::redirect('http://127.0.0.1/web/demo/print-2-accueil.php', true, 301);
}
 ?>
<?php require_once($_SERVER["DOCUMENT_ROOT"].'/automne/classes/polymodFrontEnd.php');  ?><?php /* Template [print.xml] */   ?><?php if (defined('APPLICATION_XHTML_DTD')) echo APPLICATION_XHTML_DTD."\n";   ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
	<?php echo '<meta http-equiv="Content-Type" content="text/html; charset='.strtoupper(APPLICATION_DEFAULT_ENCODING).'" />';    ?>
	<title>Automne 4 : Automne version 4, goûter à la simplicité</title>
	<link rel="stylesheet" type="text/css" href="/css/print.css" />
</head>
<body>
<h1>Automne version 4, goûter à la simplicité</h1>
<h3>

</h3>
<?php /* Start clientspace [second] */   ?><?php /* Start row [615 [Actualités] Dernière actualité - r66_615_Derniere_actualite.xml] */   ?>
	<?php $cache_a01ed0637afdd27060c22b66b6dda70b = new CMS_cache('a01ed0637afdd27060c22b66b6dda70b', 'polymod', 'auto', true);
if ($cache_a01ed0637afdd27060c22b66b6dda70b->exist()):
	//Get content from cache
	$cache_a01ed0637afdd27060c22b66b6dda70b_content = $cache_a01ed0637afdd27060c22b66b6dda70b->load();
else:
	$cache_a01ed0637afdd27060c22b66b6dda70b->start();
	   ?>
<?php /*Generated on Wed, 19 May 2010 18:19:49 +0200 by Automne (TM) 4.0.2 */
if(!APPLICATION_ENFORCES_ACCESS_CONTROL || (isset($cms_user) && is_a($cms_user, 'CMS_profile_user') && $cms_user->hasModuleClearance('pnews', CLEARANCE_MODULE_VIEW))){
	$content = "";
	$replace = "";
	if (!isset($objectDefinitions) || !is_array($objectDefinitions)) $objectDefinitions = array();
	$blockAttributes = array (
		'module' => 'pnews',
		'language' => 'fr',
	);
	$parameters['pageID'] = '2';
	if (!isset($cms_language) || (isset($cms_language) && $cms_language->getCode() != 'fr')) $cms_language = new CMS_language('fr');
	$parameters['public'] = true;
	if (isset($parameters['item'])) {$parameters['objectID'] = $parameters['item']->getObjectID();} elseif (isset($parameters['itemID']) && sensitiveIO::isPositiveInteger($parameters['itemID']) && !isset($parameters['objectID'])) $parameters['objectID'] = CMS_poly_object_catalog::getObjectDefinitionByID($parameters['itemID']);
	if (!isset($object) || !is_array($object)) $object = array();
	if (!isset($object[1])) $object[1] = new CMS_poly_object(1, 0, array(), $parameters['public']);
	$parameters['module'] = 'pnews';
	//SEARCH lastNews TAG START 7_8ac9b9
	$objectDefinition_lastNews = '1';
	if (!isset($objectDefinitions[$objectDefinition_lastNews])) {
		$objectDefinitions[$objectDefinition_lastNews] = new CMS_poly_object_definition($objectDefinition_lastNews);
	}
	//public search ?
	$public_7_8ac9b9 = isset($public_search) ? $public_search : false;
	//get search params
	$search_lastNews = new CMS_object_search($objectDefinitions[$objectDefinition_lastNews], $public_7_8ac9b9);
	$launchSearch_lastNews = true;
	//add search conditions if any
	$search_lastNews->setAttribute('itemsPerPage', (int) CMS_polymod_definition_parsing::replaceVars("1", $replace));
	$search_lastNews->addOrderCondition("publication date after", "desc");
	//RESULT lastNews TAG START 8_65a096
	//launch search lastNews if not already done
	if($launchSearch_lastNews && !isset($results_lastNews)) {
		if (isset($search_lastNews)) {
			$results_lastNews = $search_lastNews->search();
		} else {
			CMS_grandFather::raiseError("Malformed atm-result tag : can't use this tag outside of atm-search \"lastNews\" tag ...");
			$results_lastNews = array();
		}
	} elseif (!$launchSearch_lastNews) {
		$results_lastNews = array();
	}
	if ($results_lastNews) {
		$object_8_65a096 = (isset($object[$objectDefinition_lastNews])) ? $object[$objectDefinition_lastNews] : ""; //save previous object search if any
		$replace_8_65a096 = $replace; //save previous replace vars if any
		$count_8_65a096 = 0;
		$content_8_65a096 = $content; //save previous content var if any
		$maxPages_8_65a096 = $search_lastNews->getMaxPages();
		$maxResults_8_65a096 = $search_lastNews->getNumRows();
		foreach ($results_lastNews as $object[$objectDefinition_lastNews]) {
			$content = "";
			$replace["atm-search"] = array (
				"{resultid}" 	=> (isset($resultID_lastNews)) ? $resultID_lastNews : $object[$objectDefinition_lastNews]->getID(),
				"{firstresult}" => (!$count_8_65a096) ? 1 : 0,
				"{lastresult}" 	=> ($count_8_65a096 == sizeof($results_lastNews)-1) ? 1 : 0,
				"{resultcount}" => ($count_8_65a096+1),
				"{maxpages}"    => $maxPages_8_65a096,
				"{currentpage}" => ($search_lastNews->getAttribute('page')+1),
				"{maxresults}"  => $maxResults_8_65a096,
				"{altclass}"    => (($count_8_65a096+1) % 2) ? "CMS_odd" : "CMS_even",
			);
			$content .="
			<div class=\"lastNews\">
			<div class=\"newsTop\">
			<h3><a href=\"".CMS_tree::getPageValue("5","url")."?item=".$object[1]->getValue('id','')."\">".$object[1]->objectValues(1)->getValue('value','')."</a></h3>
			<span>".$object[1]->getValue('formatedDateStart','d/m/Y')."</span>
			<div class=\"spacer\"></div>
			</div>
			<div class=\"newsContent\">
			";
			//IF TAG START 9_a34fb1
			$ifcondition = CMS_polymod_definition_parsing::replaceVars(CMS_polymod_definition_parsing::prepareVar($object[1]->objectValues(4)->getValue('imageWidth','')), $replace);
			if ($ifcondition) {
				$func = create_function("","return (".$ifcondition.");");
				if ($func()) {
					$content .="
					<div class=\"imgRight shadow\">
					<img src=\"".$object[1]->objectValues(4)->getValue('imagePath','')."/".$object[1]->objectValues(4)->getValue('imageName','')."\" alt=\"".$object[1]->objectValues(4)->getValue('imageLabel','')."\" title=\"".$object[1]->objectValues(4)->getValue('imageLabel','')."\" />
					</div>
					";
				}
			}//IF TAG END 9_a34fb1
			$content .="
			".$object[1]->objectValues(2)->getValue('htmlvalue','')."
			";
			//IF TAG START 10_71611d
			$ifcondition = CMS_polymod_definition_parsing::replaceVars(CMS_polymod_definition_parsing::prepareVar($object[1]->objectValues(3)->getValue('value','')), $replace);
			if ($ifcondition) {
				$func = create_function("","return (".$ifcondition.");");
				if ($func()) {
					$content .="
					<a href=\"".CMS_tree::getPageValue("5","url")."?item=".$object[1]->getValue('id','')."\" class=\"blocLien\" title=\"En savoir plus concernant '".$object[1]->getValue('label','')."'\">
					<span class=\"blocLienBottom\">En savoir plus</span>
					</a>
					";
				}
			}//IF TAG END 10_71611d
			$content .="
			<div class=\"spacer\"></div>
			<div class=\"newsBottom\">
			<a class=\"newsAll\" href=\"".CMS_tree::getPageValue("5","url")."\">Toute l'actualite</a>
			";
			//FUNCTION TAG START 11_a3e844
			$parameters_11_a3e844 = array ('selected' => CMS_polymod_definition_parsing::replaceVars("3", $replace),);
			$object_11_a3e844 = &$object[1];
			if (method_exists($object_11_a3e844, "rss")) {
				$content .= CMS_polymod_definition_parsing::replaceVars($object_11_a3e844->rss($parameters_11_a3e844, array (
					0 =>
					array (
						'textnode' => '
						',
					),
					1 =>
					array (
						'nodename' => 'a',
						'attributes' =>
						array (
							'class' => 'newsRSS',
							'title' => '{description}',
							'href' => '{url}',
						),
						'childrens' =>
						array (
							0 =>
							array (
								'nodename' => 'img',
								'attributes' =>
								array (
									'src' => '/img/demo/common/rss.gif',
									'alt' => '{label}',
								),
							),
						),
					),
					2 =>
					array (
						'textnode' => '
						',
					),
				)), $replace);
			} else {
				CMS_grandFather::raiseError("Malformed atm-function tag : can't found method rss on object : ".get_class($object_11_a3e844));
			}
			//FUNCTION TAG END 11_a3e844
			$content .="
			</div>
			</div>
			</div>
			";
			$count_8_65a096++;
			//do all result vars replacement
			$content_8_65a096.= CMS_polymod_definition_parsing::replaceVars($content, $replace);
		}
		$content = $content_8_65a096; //retrieve previous content var if any
		$replace = $replace_8_65a096; //retrieve previous replace vars if any
		$object[$objectDefinition_lastNews] = $object_8_65a096; //retrieve previous object search if any
	}
	//RESULT lastNews TAG END 8_65a096
	//destroy search and results lastNews objects
	unset($search_lastNews);
	unset($results_lastNews);
	//SEARCH lastNews TAG END 7_8ac9b9
	$content = CMS_polymod_definition_parsing::replaceVars($content, $replace);
	$content .= '<!--{elements:'.base64_encode(serialize(array (
		'module' =>
		array (
			0 => 'standard',
			1 => 'standard',
			2 => 'standard',
			3 => 'pnews',
		),
	))).'}-->';
	echo $content;
	unset($content);}
	   ?>
	<?php $cache_a01ed0637afdd27060c22b66b6dda70b_content = $cache_a01ed0637afdd27060c22b66b6dda70b->endSave();
endif;
unset($cache_a01ed0637afdd27060c22b66b6dda70b);
echo $cache_a01ed0637afdd27060c22b66b6dda70b_content;
unset($cache_a01ed0637afdd27060c22b66b6dda70b_content);
   ?>

<?php /* End row [615 [Actualités] Dernière actualité - r66_615_Derniere_actualite.xml] */   ?><?php /* End clientspace [second] */   ?><br /><?php /* Start clientspace [first] */   ?><?php /* Start row [110 Sous Titre (niveau 2) - r43_100_Sous_Titre.xml] */   ?>

<h2>Faciliter la communication et les échanges !</h2>

<?php /* End row [110 Sous Titre (niveau 2) - r43_100_Sous_Titre.xml] */   ?><?php /* Start row [200 Texte - r44_200_Texte.xml] */   ?>

<div class="text"><p><strong>Automne est votre solution</strong> si vous recherchez un outil de gestion de contenu performant et &eacute;volutif. </p><p>Un outil permettant autonomie et contr&ocirc;le &eacute;ditorial.</p><p>Que votre contenu soit statique ou dynamique avec une gestion en bases de donn&eacute;es, Automne facilite la communication et les &eacute;changes <strong>sans contraintes techniques.<br /></strong></p></div>

<?php /* End row [200 Texte - r44_200_Texte.xml] */   ?><?php /* End clientspace [first] */   ?><br />
<hr />
<div align="center">
	<small>
		
		
				Page  "Accueil" (http://127.0.0.1/web/demo/2-accueil.php)
				<br />
		Tir&eacute; du site http://<?php echo $_SERVER["HTTP_HOST"];    ?>
	</small>
</div>
<script language="JavaScript">window.print();</script>
<?php if (SYSTEM_DEBUG && STATS_DEBUG) {view_stat();}   ?>
</body>
</html>