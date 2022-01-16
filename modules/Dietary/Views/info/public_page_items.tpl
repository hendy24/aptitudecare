<div class="container">
	<div class="row">
		<div class="col-sm-12">{$this->loadElement("selectLocation")}</div>
	</div>
	<div class="row mb-4 text-center">
		<div class="col-12"><h1>Public Page Display Items</h1></div>
		
	</div>
	

	<form name="welcome_info" id="welcome-info" method="post" action="{$SITE_URL}">
		<input type="hidden" name="module" value="Dietary">
		<input type="hidden" name="page" value="info">
		<input type="hidden" name="action" value="submitWelcomeInfo">
		<input type="hidden" name="location_detail_id" value="{$menuGreeting->id}">
		<input type="hidden" name="location" value="{$location->public_id}">
		<input type="hidden" name="path" value="{$current_url}">
		
		<div class="form-group">
			<label for="menu-greeting"><h3>Welcome Info</h3></label>
			<input type="text" id="menu-greeting" class="form-control" name="menu_greeting" aria-describedby="charLimit" value="{$menuGreeting->menu_greeting}" size="65" onkeydown="limitText(this.form.menu_greeting,this.form.countdown,75)" onkeyup="limitText(this.form.menu_greeting,this.form.countdown,75)" maxlength="75">
			<small id="charLimit" class="form-text text-muted">You have <input readonly type="text" name="countdown" size="3" value="75"> characters left. (Maximum characters: 75).</small>
		</div>

		<div class="row">
			<div class="col-12 text-right">
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</div>
	</form>

		
	<form name="meal_times" id="meal-times" method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="info">
		<input type="hidden" name="action" value="submitMealTimes">
		<input type="hidden" name="location" value="{$location->public_id}">
		<input type="hidden" name="path" value="{$current_url}">
		
		<div class="row">
			<div class="col-12">
				<h3>Meal Times</h3>
			</div>
		</div>

		<table class="table">
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
		</table>
		<div class="row text-right">
			<div class="col-12">
				<button class="btn btn-primary" type="submit">Save</button>
			</div>
		</div>

	</form>

	<form name="alt_menu_items" id="alt-menu-items" method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="info">
		<input type="hidden" name="action" value="submitAltItems">
		<input type="hidden" name="alt_menu_id" value="{$alternates->id}">
		<input type="hidden" name="path" value="{$current_url}">
		<input type="hidden" name="location" id="location" value="{$location->public_id}" />

		<div class="row">
			<div class="col-12">
				<h3>Alternate Menu Items</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<textarea id="summernote" class="form-control" name="alt_menu" id="alt-menu" cols="75" rows="10">{$alternates->content|unescape:"html"}</textarea>
			</div>
		</div>

		<div class="row mt-5 text-right">
			<div class="col-12"><button class="btn btn-primary" type="submit">Save</button></div>
		</div>					
	</form>
</div>
