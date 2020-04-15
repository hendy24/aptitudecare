<div class="row">
	<div class="col-sm-12">{$this->loadElement("selectLocation")}</div>
</div>

<div class="container">
	<h1>Set Menu Start Date</h1>

	<div class="row mt-4">
		<div class="col-sm-6">
			<p><strong>Current Menu</strong>: {$currentMenu->name}</p>
		</div>
		<div class="col-sm-6">
			<p><strong>Date Started</strong>: {$currentMenu->date_start|date_format}</p>
		</div>
	</div>


	<form id="start-date" name="start_date" method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="info">
		<input type="hidden" name="action" value="submitStartDate">
		<input type="hidden" name="location" value="{$location->public_id}">
		<input type="hidden" name="path" value="{$current_url}">
		
		<div class="row">
			<div class="col-sm-6">
				<strong>Choose the menu: &nbsp;</strong>
				{foreach from=$availableMenus item="menu"}
				<input type="radio" name="menu" value="{$menu->public_id}">{$menu->name}
				{/foreach}
			</div>
			<div class="col-sm-6">
				<strong>Select the start date</strong>:
				<input type="input" name="date_start" class="datepicker">
			</div>
		</div>

		<div class="row mt-5 text-right">
			<div class="col-sm-12">
				<buton type="submit" class="btn btn-primary">Submit</buton>
			</div>
		</div>
	</form>

	<br>
	<br>
	<div class="row">
		<div class="col-sm-12">	
			<p>NOTE: You only need to change the menu twice per year when you are ready to change to a new menu. For example, if you are currently on the Fall/Winter menu you will not need to use this page until just prior to changing to the Spring/Summer menu.</p>
			<p>PLEASE REMEMBER: Once the menu is set to start it will continue to rotate through the menu until it reaches the start date for the new menu season. You can see the start dates for each menu above.</p>
		</div>
	</div>
</div>