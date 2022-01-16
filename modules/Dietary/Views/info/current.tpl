<div class="container mt-4">
	<div class="row">
		<div class="col-lg-4 col-sm-12">
			{$this->loadElement("module")}
		</div>
		<div class="col-lg-4 col-sm-12 text-center">
			{$this->loadElement("selectLocation")}
		</div>
		<div class="col-lg-4 col-sm-12 text-right">
			<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#printModal">Print Menu</button>
		</div>
	</div>
	
	<div class="row m-3">
		<div class="col-sm-3 text-right">
			<a href="{$SITE_URL}/?module=Dietary&amp;page=info&amp;action=current&amp;location={$location->public_id}&amp;weekSeed={$retreatWeekSeed}"><i class="fas fa-arrow-alt-circle-left fa-2x"></i></a>
		</div>
		<div class="col-sm-6">
			<h2 class="text-center"><strong>Week {$menuWeek}</strong> of the <br><strong>{$menu->name}</strong> Menu</h2>
		</div>
		<div class="col-sm-3">
			<a href="{$SITE_URL}/?module=Dietary&amp;page=info&amp;action=current&amp;location={$location->public_id}&amp;weekSeed={$advanceWeekSeed}"><i class="fas fa-arrow-alt-circle-right fa-2x"></i></a>	
		</div>
	</div>

	<input type="hidden" name="location" id="location" value="{$location->public_id}">

	<table class="table">
		<tr>
			<th colspan="3" class="text-center">{$startDate|date_format:"%A, %B %e, %Y"}</th>
		</tr>
		<tr>
		{foreach from=$menuItems item="menuItem" name="menuItems"}
			<td>
				<div>
					<div {if $menuItem->type == "MenuMod"}class="text-primary"{elseif $menuItem->type == "MenuChange"}class="text-warning"{/if}>
						<ul>
						{foreach $menuItem->content as $menu}
							<li>{$menu|strip_tags:true}</li>
						{/foreach}
						</ul>
					</div>
					<div class="align-text-baseline text-right">
						<a href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=edit&amp;location={$location->public_id}&amp;type={$menuItem->type}&amp;id={$menuItem->public_id}&amp;date={"$startDate + $count day"|date_format:"%Y-%m-%d"}" class="btn-table">Edit</a>
					</div>
				</div>

			</td>
		{if $smarty.foreach.menuItems.iteration is div by 3}
		{$count++|truncate:0:""}
		</tr>

			{if !$smarty.foreach.menuItems.last}
			<tr>
				<th colspan="3" class="text-center">{"$startDate + $count day"|date_format:"%A, %B %e, %Y"}</th>
			</tr>
			<tr>
			{else}
			</tr>
			{/if}
		{/if}

		{/foreach}
	</table>

	<h2 class="text-center mt-5 pb-2">Menu Color Legend</h2>
		<div class="row">
			<div class="col-lg-1 bg-warning"></div>
			<div class="col-lg-11">Menu Items with this font color have been edited at a corporate level for this location. These changes are <strong>permanent</strong> and will display each time the menu starts over.</div>
		</div>
		<div class="row mt-2">
			<div class="col-lg-1 bg-primary"></div>
			<div class="col-lg-11">Menu Items with this font are a <strong>one-time</strong> change and will not recur when the menu starts over.</div>
		</div>

	</div>
</div>


<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content datepicker-modal">
			<div class="modal-header">
				<h5>Select Date</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Select the date for which you would like the menu to print.</p>
				<input type="text" class="datepicker" name="datetime_start">
				<div class="row text-right align-bottom">
					<div class="col-12">
						<button type="submit" class="print btn btn-primary">Print</button>
					</div>
				</div>
				
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>
</div>

