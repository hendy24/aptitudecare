
<table class="form menu">
	<tr>
		<td colspan="3" class="text-center"><h1>{$location->name} Dining Services</h1></td>
	</tr>	
	{foreach from=$menuItems item="menu" key="day"}
	<tr>
		<td colspan="3" class="menu-day-title"><strong>{$day}</strong></td>
	</tr>
	<tr>
		{foreach from=$menu item="content"}
			<td class="menu-content">
			{foreach $content as $item}
				{$item}<br>
			{/foreach}
			</td>
		{/foreach}
	</tr>
	{/foreach}
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" class="public-info">When a guest will be joining you for a meal, please provide the kitchen with a 2 hour notice. Thank You!</td>
	</tr>	
	<tr>
		<td colspan="3" class="public-info">If the daily special doesn't appeal to you today, please choose from the following alternate selections</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>

	<tr>
		<td colspan="3" class="menu-header">Alternate Menu</td>
	</tr>
	<tr>
		<td colspan="3" class="text-center">{$alternates->content}</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" class="menu-header">Beverage Options</td>
	</tr>
	<tr class="beverages">
		<td>Coffee</td>
		<td>Apple</td>
		<td>Whole Milk</td>
	</tr>
	<tr class="beverages">
		<td>Assorted Teas</td>
		<td>Orange Juice</td>
		<td>2% Milk</td>
	</tr>
	<tr class="beverages">
		<td>Hot Cocoa</td>
		<td>Cranberry</td>
		<td>Skim Milk</td>
	</tr>
	<tr class="beverages">
		<td>Sugar Free Hot Cocoa</td>
		<td>Tomato</td>
		<td>&nbsp;</td>
	</tr>
	<tr class="beverages">
		<td>&nbsp;</td>
		<td>Lemonade</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>

	<tr>
		<td colspan="3" class="public-info">Please contact the Nutrition Services Director, ______________________________________________________ for any questions or comments.</td>
	</tr>
</table>
		
