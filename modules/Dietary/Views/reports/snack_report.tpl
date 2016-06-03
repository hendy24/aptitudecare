<style>
  .container{
    width: 75%;
    margin: 20px auto;
    text-align: left;
    font-weight: normal;
    border-collapse: collapse;
  }
</style>

<div id="page-header">
  <div id="action-left">
    {$this->loadElement("module")}
  </div>
  <div id="center-title">
    {$this->loadElement("selectLocation")}
  </div>
  <div id="action-right">
    {if $auth->isLoggedIn()}
    <a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=snack_report&amp;location={$location->public_id}&amp;pdf=true" target="_blank">
      <img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
    </a>
    {/if}
  </div>

</div>

<h1>Snack Report</h1>
<div class="container">
  <form action="{$SITE_URL}" method="POST">
    <input type="hidden" name="module" value="Dietary">
    <input type="hidden" name="page" value="reports">
    <input type="hidden" name="action" value="snack_report">
    <input type="hidden" name="location" value="{$location->public_id}">
    <table class="form">
      {foreach from=$snacks item=snack key=time}
      <tr>
        <th colspan="2" width="50%">{strtoupper($time)}</th>
      </tr>
      {foreach from=$snack item=s} 
      <tr>
        <td>{$s["name"]}</td>
        <td class="text-right">{$s["num"]}</td>
      </tr>
      {/foreach}
      {/foreach}
    </table>
  </form>
</div>