<?php

/**
 * @author Andy Snowden
 * @copyright 2013
 * @version 1.1
 */

event::register('home_context_assembling', 'alliance_top_killers::add');

class alliance_top_killers {

	function add($page) {
		global $sourcePage;
		$sourcePage = $page;
		$page->addBefore('topLists', 'alliance_top_killers::show');
	}

  function show() {
  	global $smarty;
   	include_once('mods/alliance_top_killers/alliance_top_killers.php');
  	$html .= $smarty->fetch('../../../mods/alliance_top_killers/alliance_top_killers.tpl');
    return $html;
  }
}
