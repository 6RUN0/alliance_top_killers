<?php

/**
 * $Id$
 * @author Andy Snowden
 * @maintainer boris_t (boris@talovikov.ru)
 * @copyright 2013
 */

require_once('mods/alliance_top_killers/alliance_top_killers.php');

event::register('home_context_assembling', 'alliance_top_killers::add');

$modInfo['alliance_top_killers']['name'] = 'Alliance Top Killers';
$modInfo['alliance_top_killers']['abstract'] = '';
$modInfo['alliance_top_killers']['about'] = 'It\'s fork <a href="http://www.evekb.org/forum/viewtopic.php?f=505&t=18523">Corp Top Killers Mod</a>. Fork by <a href="https://github.com/6RUN0">boris_t</a>.<br />
<a href="https://github.com/6RUN0/alliance_top_killers">Get Alliance Top Killers</a>';
