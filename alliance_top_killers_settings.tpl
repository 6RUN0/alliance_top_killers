<!-- alliance_top_killers_settings.tpl -->
{if isset($alliance_top_killers_message.class)}
  <div class="alliance-top-killers-msg">
    {$alliance_top_killers_message.text}
  </div>
{/if}
<form method="post">
  <div><label for="alliance_top_killers_limit">Alliance Limit:</label></div>
  <div><input type="text" maxlength="4" size="5" value="{$alliance_top_killers_limit}" name="alliance_top_killers_limit" id="alliance_top_killers_limit" {if isset($alliance_top_killers_message.class)}class="{$alliance_top_killers_message.class}"{/if}/></div>
  <div><small>How many alliances to show on top list (Defaults to 10)</small></div><br />
  <div><input type="submit" value="submit" name="submit"></div>
</form><br />
<div class="block-header2"></div>
<div align="right">
  <small>Alliance Top Killers Mod <br />It's fork <a href="http://www.evekb.org/forum/viewtopic.php?t=18523">Corp Top Killers Mod</a><br />$Id$</small>
</div>
<!-- /alliance_top_killers_settings.tpl -->
