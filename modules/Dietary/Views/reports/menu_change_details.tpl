<script>
	$(document).ready(function() {
		var pageURL = SITE_URL + "/?module=Dietary&page=reports&action=menu_change_details&";
		var location = $("#location").val();

		$("#location").change(function() {
			window.location.href = pageURL + "location=" + $("option:selected", this).val() + "&days=" + $("#num-days option:selected").val();
		});

		$("#num-days").change(function() {
			window.location.href = pageURL + "location=" + $("#location option:selected").val() + "&days=" + $("option:selected", this).val();
		});
	});
</script>


<div id="page-header">
	<div id="action-left">
		<a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=menu_changes" class="button">Back</a>
	</div>
	<div id="center-title">
		<div id="locations">
			<select name="location" id="location">
				{foreach $locations as $location}	
					<option value="{$location->public_id}" {if $location->public_id == $selectedLocation->public_id} selected{/if}>{$location->name}</option>
				{/foreach}
			</select>
		</div>

		<h1>Menu Change Details</h1>
	</div>
	<div id="action-right">
		<select name="num_days" id="num-days">
			{foreach from=$numberOfDays item=days key=key name=day}
				<option value="{$key}" {if $key == $numDays} selected{/if}>{$days}</option>
			{/foreach}
		</select>	
	</div>
</div>

<div class="clear"></div>

<table class="view" style="width: 75%">
	{foreach from=$menuItems item=mod key=key name=menu_mod}
	<tr>
		<th colspan="5">Changed Menu</th>
	</tr>
	<tr>
		<td class="text-center" style="width: 15%"><strong>Date</strong></td>
		<td class="text-center" style="width: 10%"><strong>Meal/Day</strong></td>
		<td class="text-center" style="width: 35%"><strong>Menu</strong></td>
		<td class="text-center"><strong>Changed By</strong></td>
		<td class="text-center" style="width: 20%"><strong>Reason</strong></td>
	</tr>
	<tr>
		<td>{$mod->date|date_format}</td>
		<td>{$meal_name($mod->meal_id)}</td>
		<td>
			<ul>
			{foreach $mod->mod_content as $menu}
				<li>{$menu|unescape:'html'}</li>
			{/foreach}
			</ul>
		</td>
		<td>{$mod->user_name}</td>
		<td>{$mod->mod_reason|default: "No reason entered"}</td>
	</tr>
	<tr>
		<th colspan="5">Original Menu</th>
	</tr>
	<tr>
		<td></td>
		<td>{$mod->day}</td>
		<td>
			<ul>
			{foreach $mod->content as $menu}
				<li>{$menu|unescape:'html'}</li>
			{/foreach}
			</ul>
		</td>
		<td></td>
		<td></td>
	</tr>
	{/foreach}
</table>


{if isset ($pagination)}
	{$url = "{$SITE_URL}?module=Dietary&page=reports&action=menu_change_details"}
	{include file="elements/pagination.tpl"}	
{/if}