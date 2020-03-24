<!-- modules/Dietary/Views/public/index.tpl -->

<div id="transitionDiv">
 		
	<!-- logo -->
	<img src="{$IMAGES}/aspencreek-logo-white.png" class="img-fluid"  alt="">
	<!-- /logo -->


	<!-- Main menu content page -->
	<div id="panel-0" class="rotatingPage">
		
		<!-- featured menu heading -->
		<div class="row rotatingPage">
			<div class="col-12 my-2">
				<h1 class="text-white">Featured Menu</h1>
			</div>
		</div>
		<!-- /featured menu heading -->

		<!-- menu content -->
		<div class="row">
			
			<!-- breakfast -->
			<div class="col-lg-4 col-sm-12 mb-4">
				<h2>Breakfast</h2>
				<p class="text-14 time">{$meal[0]->start|date_format:"%l:%M %P"} - {$meal[0]->end|date_format:"%l:%M %P"}</p>
				{foreach from=$menuItems[0]->content item=menu}
				<p>{$menu|strip_tags:true}</p>
				{/foreach}
			</div>
			<!-- /breakfast -->

			<!-- lunch -->
			<div class="col-lg-4 col-sm-12 mb-4">
				<h2>Lunch</h2>
				<p class="text-14 time">{$meal[1]->start|date_format:"%l:%M %P"} - {$meal[1]->end|date_format:"%l:%M %P"}</p>
				{foreach from=$menuItems[1]->content item=menu}
				<p>{$menu|strip_tags:true}</p>
				{/foreach}
			</div>
			<!-- /lunch -->

			<!-- dinner -->
			<div class="col-lg-4 col-sm-12 mb-4">
				<h2>Dinner</h2>
				<p class="text-14 time">{$meal[2]->start|date_format:"%l:%M %P"} - {$meal[2]->end|date_format:"%l:%M %P"}</p>
				{foreach from=$menuItems[2]->content item=menu}
				<p>{$menu|strip_tags:true}</p>
				{/foreach}
			</div>
			<!-- /dinner -->

		</div>
		<!-- /menu content -->

		<!-- alternate items -->
		<div class="row">
			<div class="col-12">
				<h3>Alternate Menu Items</h3>			
				{$alternates->content}
			</div>
		</div>
		<!-- /alternate items -->

		<!-- welcome message -->
		<div class="row">
			<div class="col-12 mt-4">
				<h4>{$locationDetail->menu_greeting}</h4>
			</div>
		</div>
		<!-- /welcome message -->
	</div>


	<!-- activities page -->
	<div id="panel-1" class="rotatingPage" style="display: none;">

		<!-- weekly activities heading -->
		<div class="row">
			<div class="col-12 mb-4">
				<h1>Weekly Activities</h1>
			</div>
		</div>
		<!-- /weekly activities heading -->

		<!-- activities -->
		<div class="row">
			{foreach $weekActivities as $k => $activity}
			<div class="col">
				<h2>{$k|date_format: "%A"}</h2>
				{if is_array($activity)}
				{foreach $activity as $a}
					<p>
						<strong>{$a->time_start|date_format: "%l:%M %P"|default:""}</strong>
							{$a->description}
					</p>
					
				{/foreach}
				{/if}
			</div>
			{/foreach}
		</div>	
		<!-- /activities -->		
	</div>
</div>