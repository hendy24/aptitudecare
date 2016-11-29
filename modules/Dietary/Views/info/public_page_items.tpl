<script type="text/javascript">
	function limitText(limitField, limitCount, limitNum) {
		console.log(limitField);
		if (limitField.value.length > limitNum) {
			limitField.value = limitField.value.substring(0, limitNum);
		} else {
			limitCount.value = limitNum - limitField.value.length;
		}
	}
</script>

<div id="page-header">
	<div id="action-left">&nbsp;</div>
	<div id="center-title">{$this->loadElement("selectLocation")}</div>
	<div id="action-right">&nbsp;</div>
</div>



<div class="multiple-page-form">
	<h2>Welcome Info</h2>
	<form name="welcome_info" id="welcome-info" method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="info">
		<input type="hidden" name="action" value="submitWelcomeInfo">
		<input type="hidden" name="location_detail_id" value="{$menuGreeting->id}">
		<input type="hidden" name="location" value="{$location->public_id}">
		<input type="hidden" name="path" value="{$current_url}">
		<table class="form-multiple">
			<tr>
				<td><input type="text" name="menu_greeting" value="{$menuGreeting->menu_greeting}" size="65" onkeydown="limitText(this.form.menu_greeting,this.form.countdown,75)" onkeyup="limitText(this.form.menu_greeting,this.form.countdown,75)" maxlength="75"></td>
			</tr>
			<tr>
				<td class="text-right"><font size="1">You have <input readonly type="text" name="countdown" size="3" value="75"> characters left. (Maximum characters: 75). </font></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="text-right"><input type="submit" value="Save"></td>
			</tr>
		</table>
	</form>
</div>

<div class="multiple-page-form">
	<h2>Meal Times</h2>
	<form name="meal_times" id="meal-times" method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="info">
		<input type="hidden" name="action" value="submitMealTimes">
		<input type="hidden" name="location" value="{$location->public_id}">
		<input type="hidden" name="path" value="{$current_url}">

		<table class="form-multiple">
			<tr>
				<td>&nbsp;</td>
				<td><strong>Start</strong></td>
				<td><strong>End</strong></td>
			</tr>
			<tr>
				<td>Breakfast</td>
			{foreach from=$meals item="meal" name="meal_name"}
				
				{if $meal@iteration == 2}
				<td>Lunch</td>
				{/if}
				{if $meal@iteration == 3}
				<td>Dinner</td>
				{/if}

				<td><input type="text" name="start[{$meal->id}]" value="{$meal->start|date_format:"%l:%M %P"}" class="timepicker"></td>
				<td><input type="text" name="end[{$meal->id}]" value="{$meal->end|date_format:"%l:%M %P"}" class="timepicker"></td>
			</tr>
			{/foreach}
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" class="text-right"><input type="submit" value="Save"></td>
			</tr>
		</table>
		
	</form>
</div>

<div class="multiple-page-form">
	<h2>Alternate Menu Items</h2>
	<form name="alt_menu_items" id="alt-menu-items" method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="info">
		<input type="hidden" name="action" value="submitAltItems">
		<input type="hidden" name="alt_menu_id" value="{$alternates->id}">
		<input type="hidden" name="path" value="{$current_url}">
		<input type="hidden" name="location" id="location" value="{$location->public_id}" />

		<table class="form-multiple">
			<tr>
				<td colspan="2" class="text-center">
					<textarea name="alt_menu" id="alt-menu" cols="75" rows="10">{$alternates->content|unescape:"html"}</textarea>
					<p class="text-12" style="margin:0;"><strong>IMPORTANT:</strong> The alternate menu items must be separated with a semicolon (;) for them to display properly.</p>
				</td>
			</tr>
			<tr>
				<td class="text-right"><input type="submit" value="Save"></td>
			</tr>
		</table>	
	</form>
</div>
