<?php

/**
 * $Id$
 *
 * @category  Classes
 * @package   Alliance_Top_Killers
 * @author    Andy Snowden, boris_t <boris@talovikov.ru>
 * @copyright 2014
 * @license   http://opensource.org/licenses/MIT MIT
 */

/**
 * Create a box to display the top alliance at something.
 * Subclasses of TopList define the something.
 *
 * @see https://github.com/evekb/evedev-kb/blob/master/common/includes/toplist/class.base.php
 */
class TopList_AllianceKills extends TopList_Base
{
    /**
     * Generate sql query.
     *
     * @return none
     */
    function generate()
    {
        $query = "SELECT COUNT(distinct(kll.kll_id)) AS cnt, ind.ind_all_id AS all_id
                  FROM kb3_kills kll
                  INNER JOIN kb3_inv_detail ind
                  ON (ind.ind_kll_id = kll.kll_id)";
        $this->setSQLTop($query);
        $query = "GROUP BY ind.ind_all_id ORDER BY 1 DESC LIMIT " . $this->limit;
        $this->setSQLBottom($query);

        if (count($this->inc_vic_scl)) {
            $this->setPodsNoobShips(true);
        } else {
            $this->setPodsNoobShips(config::get('podnoobs'));
        }
    }
}

/**
 * Create a box to display the top alliance at something.
 * Subclasses of TopList define the something.
 *
 * @see https://github.com/evekb/evedev-kb/blob/master/common/includes/toplist/class.base.php
 */
class TopList_AllianceLosses extends TopList_Base
{
    /**
     * Generate sql query.
     *
     * @return none
     */
    function generate()
    {
        $query = "SELECT COUNT(*) AS cnt,
                       kll.kll_all_id AS all_id,
                       alli.all_name,
                       alli.all_external_id
                  FROM kb3_kills kll
                  JOIN kb3_alliances alli
                  ON kll.kll_all_id = alli.all_id";
        $this->setSQLTop($query);
        $query = "GROUP BY kll.kll_all_id ORDER BY cnt DESC LIMIT " . $this->limit;
        $this->setSQLBottom($query);

        if (count($this->inc_vic_scl)) {
            $this->setPodsNoobShips(true);
        } else {
            $this->setPodsNoobShips(config::get('podnoobs'));
        }
    }
}

/**
 * Adds 'Top Alliance Killers' box to front page.
 */
class AllianceTopKillers
{
    /**
     * Adds a element in the queue.
     *
     * @param pHome $pHome object of pHome class
     *
     * @return none
     */
    static function add($pHome)
    {
        $pHome->addBefore('topLists', 'AllianceTopKillers::show');
    }

    /**
     * Render alliance_top_killers.tpl
     *
     * @param pHome $pHome object of pHome class
     *
     * @return none
     */
    static function show($pHome)
    {
        global $smarty;

        $pilot_id = config::get('cfg_pilotid');
        $corp_id = config::get('cfg_corpid');
        $alliance_id = config::get('cfg_allianceid');
        $limit = config::get('alliance_top_killers_limit');

        if (empty($limit)) {
            config::set('alliance_top_killers_limit', 10);
            $limit = 10;
        }

        if ($pHome->getView() == 'losses') {
            $all_top = new TopList_AllianceLosses();
            if (!empty($alliance_id)) {
                $all_top->addVictimAlliance($alliance_id);
            }
            if (!empty($corp_id)) {
                $all_top->addVictimCorp($corp_id);
            }
            if (!empty($pilot_id)) {
                $all_top->addVictimPilot($pilot_id);
            }
            $award_img = '/awards/moon.png';
            $smarty->assign('title', 'Top Alliance Losers');
        } else {
            $all_top = new TopList_AllianceKills();
            if (!empty($alliance_id)) {
                $all_top->addInvolvedAlliance($alliance_id);
            }
            if (!empty($corp_id)) {
                $all_top->addInvolvedCorp($corp_id);
            }
            if (!empty($pilot_id)) {
                $all_top->addInvolvedPilot($pilot_id);
            }
            $award_img = '/awards/eagle.png';
            $smarty->assign('title', 'Top Alliance Killers');
        }

        $all_top->setPodsNoobShips(config::get('podnoobs'));
        $all_top->setLimit($limit);
        $all_top->generate();
        $pHome->loadTime($all_top);

        $row = $all_top->getRow();

        if (isset($row)) {

            $max_kl = $row['cnt'];
            $alliance = new Alliance($row['all_id']);
            $bar = new BarGraph($row['cnt'], $max_kl);

            $smarty->assign('corp_portrait', $alliance->getPortraitURL(64));
            $smarty->assign('award_img', config::get('cfg_img') . $award_img);
            $smarty->assign('url', $alliance->getDetailsURL());
            $smarty->assign('name', $alliance->getName());
            $smarty->assign('bar', $bar->generate());
            $smarty->assign('cnt', $row['cnt']);

            $i = 2;

            while (($row = $all_top->getRow())) {
                $alliance = new Alliance($row['all_id']);
                $bar = new BarGraph($row['cnt'], $max_kl);
                $top[$i] = array(
                    'url' => $alliance->getDetailsURL(),
                    'name' => $alliance->getName(),
                    'bar' => $bar->generate(),
                    'cnt' => $row['cnt'],
                );
                $i++;
            }

            $smarty->assign('top', $top);
            $text = self::_date($pHome->getYear(), $pHome->getMonth(), $pHome->getWeek());
            $smarty->assign('comment', $text);
            $tpl = get_tpl('./mods/alliance_top_killers/alliance_top_killers');

            return $smarty->fetch($tpl);
        }
    }

    /**
     * Builds a box description.
     *
     * @param string|int $year  year
     * @param string|int $month month
     * @param string|int $week  week
     *
     * @return string
     */
    private static function _date($year, $month = 0, $week = 0)
    {
        if (!empty($month)) {
            $date = date_create("${year}-${month}");
            return 'kills in ' . date_format($date, 'F, Y');
        }
        if (!empty($week)) {
            return "kills in Week ${week}, ${year}";
        }
    }
}
