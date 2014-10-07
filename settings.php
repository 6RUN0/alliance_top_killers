<?php

/**
 * $Id$
 *
 * @category  Settings
 * @package   Alliance_Top_Killers
 * @author    MrRx7, boris_t <boris@talovikov.ru>
 * @copyright 2014
 * @license   http://opensource.org/licenses/MIT MIT
 * @see       https://github.com/evekb/evedev-kb/blob/master/common/includes/class.pageassembly.php
 */
class pAllianceTopKillersSettings extends pageAssembly
{
    public $page;
    private $_lim;
    private $_msg = array();

    /**
     * Constructor methods for this classes.
     */
    function __construct()
    {
        parent::__construct();
        $this->queue('start');
        $this->queue('form');
    }

    /**
     * Preparation of the form.
     *
     * @return none
     */
    function start()
    {
        $this->page = new Page();
        $this->page->setTitle('Settings - Alliance Top Killers');
        $this->page->addHeader('<link rel="stylesheet" type="text/css" href="' . KB_HOST . '/mods/alliance_top_killers/style.css" />');
        $this->_lim = config::get('alliance_top_killers_limit');

        if (empty($this->_lim)) {
            $this->_lim = 10;
            config::set('alliance_top_killers_limit', $this->_lim);
        }

        if (isset($_POST['submit']) && isset($_POST['alliance_top_killers_limit'])) {
            if ($_lim = $this->_is_natural($_POST['alliance_top_killers_limit'])) {
                $this->_lim = $_lim;
                config::set('alliance_top_killers_limit', $this->_lim);
            } else {
                $this->_msg = array(
                    'text' => 'Limit it is natural numeric',
                    'class' => 'alliance-top-killers-err',
                );
            }
        }

    }

    /**
     * Render of the form.
     *
     * @return string html
     */
    function form()
    {
        global $smarty;
        $smarty->assign('alliance_top_killers_limit', $this->_lim);
        $smarty->assign('alliance_top_killers_message', $this->_msg);
        $tpl = get_tpl('./mods/alliance_top_killers/alliance_top_killers_settings');
        return $smarty->fetch($tpl);
    }

    /**
     * Build context.
     *
     * @return none
     */
    function context()
    {
        parent::__construct();
        $this->queue('menu');
    }

    /**
     * Render of admin menu.
     *
     * @return string html
     */
    function menu()
    {
        include 'common/admin/admin_menu.php';
        return $menubox->generate();
    }

    /**
     * Finds whether the type of the given variable is natural number.
     *
     * @param string|int $num - number
     *
     * @return unsigned integer|false natular number or false
     */
    private function _is_natural($num)
    {
        $num = intval($num);
        if (is_int($num) && $num > 0) {
            return $num;
        }
        return false;
    }

}

$pageAssembly = new pAllianceTopKillersSettings();
event::call('pAllianceTopKillersSettings_assembling', $pageAssembly);
$html = $pageAssembly->assemble();
$pageAssembly->page->setContent($html);

$pageAssembly->context();
event::call('pAllianceTopKillersSettings_context_assembling', $pageAssembly);
$context = $pageAssembly->assemble();
$pageAssembly->page->addContext($context);

$pageAssembly->page->generate();
