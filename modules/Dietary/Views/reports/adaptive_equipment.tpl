<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Adaptive Equipment Report</h1>      
  </div>
  <div id="action-right">
  	{if !$isPDF}
  	<a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=adaptive_equipment&amp;location={$location->public_id}&amp;pdf2=true" target="_blank">
  		<img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
  	</a>
  	{/if}
  </div>
</div>
<h2 class="report_date">{$smarty.now|date_format}</h2>
<table class="table">
	<tr>
		<th width="75">Room</th>	
		<th width="250">Patient</th>
		<th width="500">Adaptive Equipment</th>
	</tr>
	{foreach from=$patients item=patient}
	<tr class="form-row">
		<td>{$patient->number}</td>
		<td>{$patient->fullName()}</td>
		<td>{$patient->ae_name}</td>
	</tr>
	{/foreach}
</table>