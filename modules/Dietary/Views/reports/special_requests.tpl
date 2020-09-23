<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Special Requests</h1>
  </div>
  <div id="action-right">
    {if !$isPDF}
    <a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=special_requests&amp;location={$location->public_id}&amp;pdf2=true" target="_blank">
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

  {foreach from=$sp_array item=sr key=meal}
		{if $isPDF}<div class="page-header"><h2 class="report_date">{$smarty.now|date_format}</h2></div>{/if}
		<table class="form bev-table">
		  <tr>
			<th colspan="3">{$this->mealName($meal)}</th>
		  </tr>
	  {foreach from=$sr item=sri}
		  <tr class="form-row">
			<td>{$sri["number"]}</td>
			<td>{if $sri["isolation"] == true}✱{/if}{$sri["name"]}{if $sri["isolation"] == true} (ISOLATION){/if}</td>
			<td style="width:50%">{$sri["special"]}</td>
		  </tr>
	  {/foreach}
		</table>
		{if $sr@last != true}<div class="page-break"></div>{/if}
  {/foreach}
