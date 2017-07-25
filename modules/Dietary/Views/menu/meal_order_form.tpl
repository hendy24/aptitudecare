<!-- modules/Dietary/Views/menu/meal_order_form.tpl -->

<div class="meal-order-form">
	<div class="row">
		<div class="col-xs-12 text-center">
			<img src="{$IMAGES}/logos_black_reduced/{$location->logo}" alt="">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h2>Daily Patient Meal Order</h2>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-5">Room: ____________</div>
		<div class="col-xs-5 text-right">Date: {$startDate|date_format}</div>
	</div>
	<div class="space-row"></div>

	<!-- Breakfast menu section -->
	<div class="row text-center">
		<div class="col-xs-4"><h3>Breakfast</h3></div>
		<div class="col-xs-2">&nbsp;</div>
		<div class="col-xs-4"><h3>Breakfast Alternate</h3></div>
	</div>
	<div class="row">
		<div class="col-xs-4">
			{foreach from=$menuItems[0]->content item=menu}
				<p>{$menu}</p>
			{/foreach}
		</div>

		<div class="col-xs-2 text-center menu-middle-col">OR</div>
		<div class="col-xs-4">
			Special Egg: ______________<br>
			Bacon <br>
			Sausage <br>
			Hot Cereal <br>
			Cold Cereal <br>
			Special Toast: ____________
		</div>
	</div>
	<div class="space-row"></div>


	<!-- Lunch menu section -->
	<div class="row text-center">
		<div class="col-xs-4"><h3>Lunch</h3></div>
		<div class="col-xs-2">&nbsp;</div>
		<div class="col-xs-4"><h3>Lunch Alternate (select one)</h3></div>
	</div>
	<div class="row">
		<div class="col-xs-4">
			{foreach from=$menuItems[1]->content item=menu}
				<p>{$menu}</p>
			{/foreach}
		</div>
		<div class="col-xs-2 text-center menu-middle-col">OR</div>
		<div class="col-xs-4">
			{foreach from=$alternates item=alternate}
				{$alternate}<br>
			{/foreach}
		</div>
	</div>
	<div class="space-row"></div>


	<!-- Dinner menu section -->
	<div class="row text-center">
		<div class="col-xs-4"><h3>Dinner</h3></div>
		<div class="col-xs-2">&nbsp;</div>
		<div class="col-xs-4"><h3>Dinner Alternate (select one)</h3></div>
	</div>
	<div class="row">
		<div class="col-xs-4">
			{foreach from=$menuItems[2]->content item=menu}
				<p>{$menu}</p>
			{/foreach}
		</div>
		<div class="col-xs-2 text-center menu-middle-col">OR</div>
		<div class="col-xs-4">
			{foreach from=$alternates item=alternate}
				{$alternate}<br>
			{/foreach}
		</div>
	</div>
	<div class="space-row"></div>
	<div class="space-row"></div>

	<div class="row">
		<div class="col-xs-12 text-center text-strong">Please fill out your choices &amp; your completed form will be collected today.<br />Food choices may be changed to meet dietary restrictions.</div>
	</div>
</div>
