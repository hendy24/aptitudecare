<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Beverage Report</h1>
  </div>
  <div id="action-right">
    {if $auth->isLoggedIn()}
    <a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=beverages&amp;location={$location->public_id}&amp;pdf2=true" target="_blank">
      <img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
    </a>
    {/if}
  </div>
</div>
<h2 class="report_date">{$smarty.now|date_format}</h2>


  {foreach from=$beverages item=beverage key=meal}
    <table class="form bev-table">
      <tr>
        <th colspan="2">{$this->mealName($meal)}</th>
      </tr>
      {foreach from=$beverage item=bev}
      <tr class="form-row">
        <td>{$bev["name"]}</td>
        <td class="text-right">{$bev["num"]}</td>
      </tr>
      {/foreach}
    </table>
    <div class="page-break"></div>
    <div class="page-header"></div>
  {/foreach}
