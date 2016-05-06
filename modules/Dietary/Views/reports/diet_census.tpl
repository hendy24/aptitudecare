<div id="page-header">
	<div id="action-left">&nbsp;</div>
	<div id="center-title">
		<h1>Diet Census</h1>
		{if $isPDF}
      		<h2>{$smarty.now|date_format}</h2>
    	{/if}
	</div>
  <div id="action-right">
  	{if $auth->isLoggedIn()}
  	<a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=diet_census&amp;location={$location->public_id}&amp;pdf=true" target="_blank">
  		<img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
  	</a>
  	{/if}
  </div>
</div>

<table class="form">
	<tr>
		<th>Room</th>
		<th>Patient Name</th>
		<th>Diet Order</th>
		<th>Texture</th>
		<th>Liquid Consistency</th>
	</tr>
	{foreach from=$dietCensus item=diet}
	<tr>
		<td>{$diet->room}</td>
		<td>{$diet->patient_name}</td>
		<td>{$diet->diet_order}</td>
		<td>{$diet->texture}</td>
		<td>{$diet->liquid_consistency}</td>
	</tr>
	{/foreach}
</table>