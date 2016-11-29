<div id="action-left">
	&nbsp;
</div>
<div id="center-title">
	{$this->loadElement("selectLocation")}
</div>
<div id="action-right">
	&nbsp;
</div>

<div class="clear"></div>
<h1>Set Menu Start Date</h1>

<div class="current-menu-info">
	<p><strong>Current Menu</strong>: {$currentMenu->name}</p>
	<p><strong>Date Started</strong>: {$currentMenu->date_start|date_format}</p>
</div>

<form id="start-date" name="start_date" method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="info">
	<input type="hidden" name="action" value="submitStartDate">
	<input type="hidden" name="location" value="{$location->public_id}">
	<input type="hidden" name="path" value="{$current_url}">
	<br><br>
	<table class="form">
		<tr>
			<td><strong>Choose the menu:</strong></td>
		</tr>
		{foreach from=$availableMenus item="menu"}
			<tr>
				<td><input type="radio" name="menu" value="{$menu->public_id}">{$menu->name}</td>
			</tr>
		{/foreach}

		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><strong>Select the start date</strong>:</td>
		</tr>
		<tr>
			<td><input type="input" name="date_start" class="datepicker"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="text-right"><input type="submit" value="Save"></td>
		</tr>
	</table>
</form>

<br>
<br>
<div id="page-info">
	<p>NOTE: You only need to change the menu twice per year when you are ready to change to a new menu. For example, if you are currently on the Fall/Winter menu you will not need to use this page until just prior to changing to the Spring/Summer menu.</p>
	<p>PLEASE REMEMBER: Once the menu is set to start it will continue to rotate through the menu until it reaches the start date for the new menu season. You can see the start dates for each menu above.</p>


</div>
