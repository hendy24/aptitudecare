<div class="container-fluid menu-page">
	<div class="row text-center">
		<div class="col-lg-4 col-sm-12">
			<img src="{$IMAGES}/food-entree.jpg" class="img-fluid" alt="">
		</div>
		<div class="col-lg-4 col-sm-12">
			<img src="{$IMAGES}/food-fruit.jpg" class="img-fluid" alt="">
		</div>
		<div class="col-lg-4 col-sm-12">
			<img src="{$IMAGES}/food-dessert.jpg" class="img-fluid" alt="">
		</div>
	</div>
</div>

<div class="container text-center">
	<h1 class="info-title">Current Menu</h1>
	<div class="row">
		<div class="col-lg">
			<h5>{"$startDate"|date_format:"%A, %B %e, %Y"}</h5>
		</div>
	</div>
	<div class="row">			
	{foreach from=$menuItems item="menuItem" name="menuItems"}
			<div class="col-md py-3 info-item">
				<h6 class="text-strong">
					{if $menuItem->meal_id == 1}Breakfast{/if}
					{if $menuItem->meal_id == 2}Lunch{/if}
					{if $menuItem->meal_id == 3}Dinner{/if}
				</h6>
				{foreach $menuItem->content as $menu}
					{if !empty($menu)}<p>{$menu|strip_tags:true}</p>{/if}
				{/foreach}		
			</div>

		{if $smarty.foreach.menuItems.iteration is div by 3}
		{$count++|truncate:0:""}
		</div>
			{if !$smarty.foreach.menuItems.last}
			<div class="row">
				<div class="col-lg mt-5">
					<h5>{"$startDate + $count day"|date_format:"%A, %B %e, %Y"}</h5>	
				</div>
			</div>
			<div class="row">
			{/if}
		{/if}
	{/foreach}	
	<div class="row new-section">
		<div class="col-lg mt-5">
			<h2>Always Available Menu</h2>
			<p>{$alternates->content}</p>
		</div>
	</div>
	<div class="row new-section">
		<div class="col-lg">
			<p class="font-weight-bold"></p>
		</div>
	</div>
</div>
