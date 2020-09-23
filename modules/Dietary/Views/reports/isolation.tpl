<script>
	$(document).ready(function() {
		$(".order").click(function(e) {
			e.preventDefault();
			var id = $(this).attr("id");
			var url = SITE_URL + "/?module=Dietary&page=reports&action=isolation&location=" + $("#location").val() + "&orderby=" + id;
			window.location = url;
		});
	});
</script>

{if !$isPDF}
<div id="page-header">
	<div id="action-left">&nbsp;</div>
	<div id="center-title">
		<h1>Isolation Census</h1>
	</div>
  <div id="action-right">
  	<a href="{$pageUrl}&amp;pdf2=true" target="_blank">
  		<img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
  	</a>
  </div>
</div>
{/if}

{*<h2 class="report_date">{$smarty.now|date_format}</h2>*}
<input type="hidden" id="location" name="location" value="{$location->public_id}">
<input type="hidden" id="current-url" name="current_url" value="{$current_url}">
<table class="table">
	{if $isPDF}
	<tr>
		<td colspan=7 class="text-center"><h1>Isolation Census for {$smarty.now|date_format}</h1></td>
	</tr>
	{/if}
	<tr>
		<th><a href="" id="room" class="order">Room</a></th>
		<th><a href="" id="patient_name" class="order">Patient Name</a></th>
		<th style="width: 150px"><a href="" id="diet_order" class="order">Diet Order</a></th>
		<th style="width: 200px;"><a href="" id="allergies" class="order">Allergies</a></th>
		<th><a href="" id="texture" class="order">Texture</a></th>
		<th><a href="" id="liquid_fluid_order" class="order">Liquid/Fluid/Orders</a></th>
		<th style="width:300px;"><a href="" id="beverages" class="order">Beverages</a></th>
	</tr>
	{foreach from=$dietCensus item=diet}
	<tr class="form-row">
		<td>{$diet->room}</td>
		<td>{$diet->patient_name}</td>
		<td>{$diet->diet_order}{if $diet->diet_info_other}, {$diet->diet_info_other}{/if}</td>
		<td>{$diet->allergies}</td>
		<td>{$diet->texture}{if $diet->texture_other}, {$diet->texture_other}{/if}</td>
		<td>{$diet->liquid_fluid_order}{if $diet->diet_other}, {$diet->diet_other}{/if}{if $diet->fluid_other}, {$diet->fluid_other}{/if}</td>
		<td>
		{if $diet->beverages[1] != ""}<b>Breakfast:</b>{$diet->beverages[1]}<br/>{/if}
		{if $diet->beverages[2] != ""}<b>Lunch:</b>{$diet->beverages[2]}<br/>{/if}
		{if $diet->beverages[3] != ""}<b>Dinner:</b>{$diet->beverages[3]}<br/>{/if}
		</td>
	</tr>
	{/foreach}
</table>
