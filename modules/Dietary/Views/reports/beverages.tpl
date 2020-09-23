<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Beverage Report</h1>
  </div>
  <div id="action-right">
    {if !$isPDF}
    <a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=beverages&amp;location={$location->public_id}&amp;pdf2=true" target="_blank">
      <img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
    </a>
    {/if}
  </div>
</div>
{if !$isPDF}
<h2 class="report_date">{$smarty.now|date_format}</h2>
{else}
<link rel="stylesheet" href="https://dev.aptitudecare.com/css/site_styles.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
{/if}
  {foreach from=$beverages item=beverage key=meal}
    {if $isPDF}<div class="page-header"><h2 class="report_date">{$smarty.now|date_format}</h2></div>{/if}
    <table class="form bev-table">
      <tr>
        <th colspan="2">{$this->mealName($meal)}</th>
      </tr>
      {foreach from=$beverage item=bev}
      <tr class="form-row">
        <td>{$bev["name"]}{if $bev['other_id'] != NULL}(ISOLATION){/if}{if $bev['liq_name'] != NULL}({$bev['liq_name']}){/if}</td>
        <td class="text-right">{$bev["num"]}</td>
      </tr>
      {/foreach}
    </table>
	{if $beverage@last != true}<div class="page-break"></div>{/if}
	

  {/foreach}
