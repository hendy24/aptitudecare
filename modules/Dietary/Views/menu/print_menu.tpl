<div class="container print-menu">
	<div class="row">
		<div class="col-xs-12">
			<h1 class="print-menu">{$location->name}</h1>
		</div>
	</div>


	{foreach from=$menuItems item="menu" key="day"}
	<div class="row">
		<div class="col-xs-12 col-sm-12 menu-day-title">
			<strong>{$day|date_format:"%A, %B %e, %Y"}</strong>
		</div>
	</div>

		<div class="row">
			{foreach from=$menu item="content"}
			<div class="menu-content">
				{foreach $content as $item}
					{$item}<br>
				{/foreach}
			</div>
			{/foreach}
		</div>
	{/foreach}
	<div class="row">
		<hr>
		<div class="col-xs-12 menu-info">
			When a guest will be joining you for a meal, please provide the kitchen with a 2 hour notice. Thank You!
			If the daily special doesn't appeal to you today, please choose from the following alternate selections
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h2>Alternate Menu</h2>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<p>{$alternates->content}</p>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<h2>Beverage Options</h2>
		</div>
	</div>
	<div class="row">
		{foreach from=$beverages item=bev}
		<div class="menu-beverages">
			{$bev->name}
		</div>
		{/foreach}
	</div>
	<div class="row">
		<div class="col-xs-12 menu-info">
			<p>Please contact the Nutrition Services Director, <strong>{$nsd->name}</strong> for any questions or comments.</p>
		</div>
	</div>
</div>
