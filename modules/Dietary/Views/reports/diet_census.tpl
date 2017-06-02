<script>
	$(document).ready(function() {
		$(".order").click(function(e) {
			e.preventDefault();
			var id = $(this).attr("id");
			var url = SITE_URL + "/?module=Dietary&page=reports&action=diet_census&location=" + $("#location").val() + "&orderby=" + id;
			window.location = url;
		});
	});
</script>

<div id="page-header">
	{if $auth->isLoggedIn()}
	<div id="action-left">&nbsp;</div>
	{/if}
	<div id="center-title">
		<h1>Diet Census {if $isPDF} for {$smarty.now|date_format}{/if}</h1>
	</div>
  <div id="action-right">
  	{if $auth->isLoggedIn()}
  	<a href="{$pageUrl}&amp;pdf=true" target="_blank">
  		<img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
  	</a>
  	{/if}
  </div>
</div>

<input type="hidden" id="location" name="location" value="{$location->public_id}">
<input type="hidden" id="current-url" name="current_url" value="{$current_url}">
<table class="table">
	<tr>
		<th><a href="" id="room" class="order">Room</a></th>
		<th><a href="" id="patient_name" class="order">Patient Name</a></th>
		<th><a href="" id="diet_order" class="order">Diet Order</a></th>
		<th style="width: 200px;"><a href="" id="allergies" class="order">Allergies</a></th>
		<th><a href="" id="texture" class="order">Texture</a></th>
		<th><a href="" id="liquid_consistency" class="order">Liquid/Fluid/Orders</a></th>
	</tr>
	{foreach from=$dietCensus item=diet}
	<tr class="form-row">
		<td>{$diet->room}</td>
		<td>{$diet->patient_name}</td>
		<td>{$diet->diet_order}</td>
		<td>{$diet->allergies}</td>
		<td>{$diet->texture}</td>
		<td>{$diet->liquid_fluid_order}</td>
	</tr>
	{/foreach}
</table>
