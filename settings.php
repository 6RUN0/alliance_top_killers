<?php

/**
 * @author MrRx7
 * @maintainer boris_t (boris@talovikov.ru)
 * @copyright 2010
 * @version 1.1p2
 */

//requires
require_once("common/admin/admin_menu.php");

//page setup
$settings = new Page();
//are we admin?
if (!$settings->isAdmin()) {
  trigger_error('You are not a admin and thus shouldn\'t be able to access this page', E_ERROR);
}
//rest of page setup // no caching and title
$settings->setCachable(false);
$settings->setTitle('Settings - Alliance Top Killers');

//page submit?
if (isset($_POST['submit'])) {
  //update settings
  (isset($_POST['alliance_top_killers_limit'])) ? config::set('alliance_top_killers_limit', $_POST['alliance_top_killers_limit']) : config::set('alliance_top_killers_limit', 10);
} 

//pull any settings and set defaults if there are none
$limit = config::get('alliance_top_killers_limit');
if (empty($limit)) {
  config::set('alliance_top_killers_limit', 10);
  $limit = 10;
}

$html = '
<form method="post" action="">
  <div><label for="alliance_top_killers_limit">Alliance Limit:</label></div>
  <div><input type="text" maxlength="4" size="5" value="' . $limit . '" name="alliance_top_killers_limit" id="alliance_top_killers_limit"/></div>
  <div><small>How many alliances to show on top list (Defaults to 10)</small></div><br />
  <div><input type="submit" value="submit" name="submit"></div>
</form>';

$html .= '<br /><br /><hr size="1" /><div align="right"><small>Alliance Top Killers Mod (Version 1.1p2)<br />It\'s fork <a href="http://www.evekb.org/forum/viewtopic.php?f=505&t=18523">Corp Top Killers Mod</a></small></div>';

//dump to screen
$settings->setContent($html);
$settings->addContext($menubox->generate());
$settings->generate();
