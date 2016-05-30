<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Beverage Report</h1>
    {if $isPDF}
      <h2>{$smarty.now|date_format}</h2>
    {/if}
  </div>
  <div id="action-right">
    {if $auth->isLoggedIn()}
    <a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=beverages&amp;location={$location->public_id}&amp;pdf=true" target="_blank">
      <img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
    </a>
    {/if}
  </div>
</div>


<table class="form bev-table">
  {foreach from=$beverages item=beverage key=meal}
  <tr>
    <th colspan="2" width="50%">{$this->mealName($meal)}</th>
  </tr>
  {foreach from=$beverage item=bev} 
  <tr>
    <td>{$bev["name"]}</td>
    <td class="text-right">{$bev["num"]}</td>
  </tr>
  {/foreach}
  {/foreach}
</table>