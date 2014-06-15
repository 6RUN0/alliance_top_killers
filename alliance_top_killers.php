<?php

/**
 * @author Andy Snowden, boris_t(boris@talovikov.ru)
 * @copyright 2013
 * @version 1.1p2
 */

class TopList_AllianceKills extends TopList_Base {

	function generate() {

		$this->setSQLTop("SELECT COUNT(distinct(kll.kll_id)) AS cnt, ind.ind_all_id AS all_id
                        FROM kb3_kills kll
	                    INNER JOIN kb3_inv_detail ind
		                    ON (ind.ind_kll_id = kll.kll_id)");

		$this->setSQLBottom("GROUP BY ind.ind_all_id ORDER BY 1 DESC
                           LIMIT " . $this->limit);

    if (count($this->inc_vic_scl)) {
			$this->setPodsNoobShips(true);
		}
		else {
			$this->setPodsNoobShips(config::get('podnoobs'));
		}

	}

}

class TopList_AllianceLosses extends TopList_Base {

	function generate() {

		$this->setSQLTop("SELECT COUNT(*) AS cnt, kll.kll_all_id AS all_id, alli.all_name, alli.all_external_id
                        FROM kb3_kills kll
                      JOIN kb3_alliances alli
                        ON kll.kll_all_id = alli.all_id ");

		$this->setSQLBottom("GROUP BY kll.kll_all_id ORDER BY cnt DESC
                           LIMIT " . $this->limit);

		if (count($this->inc_vic_scl)) {
			$this->setPodsNoobShips(true);
		}
		else {
			$this->setPodsNoobShips(config::get('podnoobs'));
		}

	}

}

global $sourcePage;

$pilot_id = config::get('cfg_pilotid');
$corp_id = config::get('cfg_corpid');
$alliance_id = config::get('cfg_allianceid');
$limit = config::get('alliance_top_killers_limit');

$own = array();
if(!empty($pilot_id)) {
  foreach($pilot_id as $id) {
    $pilot = new Pilot($id);
    $own[] = $pilot->getCorp()->getAlliance()->getID();
  }
}

if(!empty($corp_id)) {
  foreach($corp_id as $id) {
    $corp = new Corporation($id);
    $own[] = $corp->getAlliance()->getID();
  }
}

if(!empty($alliance_id)) {
  $own = array_merge($own, $alliance_id);
}

if (empty($limit)) {
  config::set('alliance_top_killers_limit', 10);
  $limit = 10;
}

if ($sourcePage->getView() == 'losses') {
  $all_top = new TopList_AllianceLosses();
  if(!empty($own)) {
    $all_top->addVictimAlliance($own);
  }
  $award_img = '/awards/moon.png';
  $smarty->assign('title', "Top Alliance Losers");
}
else {
  $all_top = new TopList_AllianceKills();
  if(!empty($own)) {
    $all_top->addInvolvedAlliance($own);
  }
  $award_img = '/awards/eagle.png';
  $smarty->assign('title', "Top Alliance Killers");
}

$all_top->setPodsNoobShips(config::get('podnoobs'));
$all_top->setLimit($limit);
$all_top->generate();
$sourcePage->loadTime($all_top);

$row = $all_top->getRow();
$max_kl = $row['cnt'];
$alliance = new Alliance($row['all_id']);
$bar = new BarGraph($row['cnt'], $max_kl);

$smarty->assign('corp_portrait', $alliance->getPortraitURL(64));
$smarty->assign('award_img', config::get('cfg_img') . $award_img);
$smarty->assign('url', $alliance->getDetailsURL());
$smarty->assign('name', $alliance->getName());
$smarty->assign('bar', $bar->generate());
$smarty->assign('cnt', $row['cnt']);

$i = 1;

while(($row = $all_top->getRow()) && ($i < $limit)) {
  $alliance = new Alliance($row['all_id']);
  $bar = new BarGraph($row['cnt'], $max_kl);
  $top[$i + 1] = array(
    'url' => $alliance->getDetailsURL(),
    'name' => $alliance->getName(),
    'bar' => $bar->generate(),
    'cnt' => $row['cnt'],
  );
  $i++;
}

$smarty->assign('top', $top);
$smarty->assign('comment', "kills in " . date('F, Y', mktime(0, 0, 0, getMonth_atk(), 1, getYear_atk())));

function getMonth_atk() {
  $month = edkURI::getArg('m');
  $week = edkURI::getArg('w');
  if (!empty($month)) {
    return $month;
  }
  elseif(!empty($week)) {
    return date('m', mktime(0, 0, 0, 1, (($week - 1) * 7) + 1, getYear_atk()));
  }
  else {
    return kbdate('m');
  }
}

function getYear_atk() {
  $year = edkURI::getArg('y');
  if(empty($year)) {
    $year = kbdate('Y');
  }
  return $year;
}
