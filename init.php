<?php

/**
 * $Id$
 *
 * @category  Init
 * @package   Alliance_Top_Killers
 * @author    Andy Snowden, boris_t <boris@talovikov.ru>
 * @copyright 2014
 * @license   http://opensource.org/licenses/MIT MIT
 */

require_once 'mods/alliance_top_killers/alliance_top_killers.php';

event::register('home_context_assembling', 'AllianceTopKillers::add');

$modInfo['alliance_top_killers']['name'] = 'Alliance Top Killers';
$modInfo['alliance_top_killers']['abstract'] = '';
$modInfo['alliance_top_killers']['about'] = 'It\'s fork <a href="http://www.evekb.org/forum/viewtopic.php?t=18523">Corp Top Killers Mod</a>. Fork by <a href="https://github.com/6RUN0">boris_t</a>.<br /> <a href="https://github.com/6RUN0/alliance_top_killers/archive/master.zip">Get Alliance Top Killers</a>';
