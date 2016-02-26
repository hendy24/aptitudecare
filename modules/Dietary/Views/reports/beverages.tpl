<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Beverage Report</h1>
  </div>
  <div id="action-right">
    {if $auth->isLoggedIn()}
    <a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=beverages&amp;location={$location->public_id}&amp;pdf=true" target="_blank">
      <img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
    </a>
    {/if}
  </div>
</div>


<table class="form">
  <tr>
    <th width="200">Beverage</th>
    <th width="50">Count</th>
  </tr>
  {foreach from=$beverages item=beverage}
  <tr>
    <td>{$beverage->name}</td>
    <td>{$beverage->quantity}</td>
  </tr>
  {/foreach}
</table>