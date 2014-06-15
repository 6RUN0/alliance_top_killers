<?php

/**
 * @author MrRx7
 * @copyright 2010
 * @version 1.0
 */

//requires
require_once("common/admin/admin_menu.php");

//page setup
$settings = new Page();
//are we admin?
if (!$settings->isAdmin()){
    trigger_error('You are not a admin and thus shouldn\'t be able to access this page', E_ERROR);
}
//rest of page setup // no caching and title
$settings->setCachable(false);
$settings->setTitle('Settings - Corp Top Killers');

//page submit?
if (isset($_POST['submit'])) 
{
    //update settings    
    (isset($_POST['alliance_top_killers_limit'])) ? config::set('alliance_top_killers_limit', $_POST['alliance_top_killers_limit']) : config::set('alliance_top_killers_limit', 10);
} 

//pull any settings and set defaults if there are none
$limit = config::get('alliance_top_killers_limit');
if (!isset($limit)) {
    config::set('alliance_top_killers_limit', 10);
    $limit = 10;
}

$html = '
<form method="post" action="">
<table width="100%" cellpadding="0" cellspacing="0" class="kb-subtable">
  <tr>
    <td width="220">Corp Limit:</td>
    <td width="120">
      <input type="text" value="'.$limit.'" name="alliance_top_killers_limit" id="alliance_top_killers_limit"/>
   </td>
    <td>How many corps to show on top list (Defaults to 10)</td>
  </tr>
</table>
<br>
<input type="submit" value="submit" name="submit"></form>';

$html .= '<br /><br /><hr size="1" /><div align="right"><i><small>Corp Top Killers Mod (Version 1.0) by <a href="http://www.eve-razor.com/killboard" target="_blank">MrRx7</a></small></i></div>';

//dump to screen
$settings->setContent($html);
$settings->addContext($menubox->generate());
$settings->generate();
