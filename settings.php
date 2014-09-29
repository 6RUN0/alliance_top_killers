<?php

/**
 * @author MrRx7
 * @maintainer boris_t (boris@talovikov.ru)
 * @copyright 2010
 * @version 1.1p3
 */

class pAllianceTopKillersSettings extends pageAssembly {

  public $page;
  private $limit;
  private $version = '1.1p3';

  function __construct() {
    parent::__construct();
    $this->queue('start');
    $this->queue('form');
  }

  function start() {
    $this->page = new Page();
    $this->page->setTitle('Settings - Alliance Top Killers');
    $this->limit = config::get('alliance_top_killers_limit');

    if (empty($this->limit)) {
      $this->limit = 10;
      config::set('alliance_top_killers_limit', $this->limit);
    }

    if (isset($_POST['submit']) && isset($_POST['alliance_top_killers_limit'])) {
      $this->limit = $_POST['alliance_top_killers_limit'];
      config::set('alliance_top_killers_limit', $this->limit);
    } 

  }

  function form() {
    global $smarty;
    $smarty->assign('alliance_top_killers_limit', $this->limit);
    $smarty->assign('alliance_top_killers_version', $this->version);
    return $smarty->fetch(get_tpl('./mods/alliance_top_killers/alliance_top_killers_settings'));
  }

  function context() {
    parent::__construct();
    $this->queue('menu');
  }

  function menu() {
    require_once('common/admin/admin_menu.php');
    return $menubox->generate();
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
