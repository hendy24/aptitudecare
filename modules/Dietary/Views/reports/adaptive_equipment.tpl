<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Adaptive Equipment Report</h1>
    {if $isPDF}
      <h2>{$smarty.now|date_format}</h2>
    {/if}
  </div>
  <div id="action-right">
  	{if $auth->isLoggedIn()}
  	<a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=adaptive_equipment&amp;location={$location->public_id}&amp;pdf=true" target="_blank">
  		<img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
  	</a>
  	{/if}
  </div>
</div>

<table class="form">
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