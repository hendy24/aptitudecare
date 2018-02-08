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
		{$this->loadElement("selectLocation")}
	</div>
	<div id="action-right">
		<select name="num_days" id="num-days">
			{foreach from=$numberOfDays item=days key=key name=day}
				<option value="{$key}" {if $key == $numDays} selected{/if}>{$days}</option>
			{/foreach}
		</select>	
	</div>
</div>
<h1>Menu Change Details</h1>

<div class="clear"></div>

<table class="view" style="width: 75%">
	{foreach from=$menuItems item=mod key=key name=menu_mod}
	<tr>
		<th class="header-row" colspan="5">Changed Menu</th>
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
		<td>{$this->mealName($mod->meal_id)}</td>
		<td>
			<ul>
			{foreach $mod->mod_content as $menu}
				<li>{$menu}</li>
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
				<li>{$menu}</li>
			{/foreach}
			</ul>
		</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="5" style="height: 100px">&nbsp;</td>
	</tr>
	{/foreach}
</table>


{if isset ($pagination) && $pagination->num_pages > 1}
	{$url = "{$SITE_URL}?module={$this->module}&page={$this->page}&action={$this->action}&days={$numDays}&location={$location->public_id}"}
	{$this->loadElement("pagination", $url)}
{/if}