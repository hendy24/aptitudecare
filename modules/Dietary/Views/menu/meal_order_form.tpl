<!-- modules/Dietary/Views/menu/meal_order_form.tpl -->


<div class="text-center" style="margin-top:30px">
	<img src="{$IMAGES}/logos_black_reduced/{$location->logo}" alt="">
</div>
<br>
<h2>Daily Patient Meal Order</h2>
<br>
<table>
	<tr>
		<td>Room:</td>
		<td style="border-bottom: 1px solid black; width: 200px;">&nbsp;</td>
		<td class="text-right">Date:</td>
		<td>{$startDate|date_format}</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<th colspan="2">Breakfast</th>
		<th colspan="2">Breakfast Alternate</th>
	</tr>
	<tr>
		<td colspan="2">
			{foreach from=$menuItems[0]->content item=menu}
			<ul>{$menu}</ul>
			{/foreach}
		</td>
		<td colspan="2">
			<br>
			<input type="checkbox"> Special Egg: ______________________________________ <br>
			<input type="checkbox"> Bacon <br>
			<input type="checkbox"> Sausage <br>
			<input type="checkbox"> Hot Cereal <br>
			<input type="checkbox"> Cold Cereal <br>
			<input type="checkbox"> Special Toast: ____________________________________ <br>
		</td>
	</tr>

	<tr>
		<td></td>
	</tr>

	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<th colspan="2">Lunch</th>
		<th colspan="2">Lunch Alternate</th>
	</tr>
	<tr>
		<td colspan="2">
			{foreach from=$menuItems[1]->content item=menu}
			<ul>{$menu}</ul>
			{/foreach}
		</td>
		<td colspan="2">
			<br>
			{foreach from=$alternates item=alternate}
			<input type="checkbox"> {$alternate}</input><br>
			{/foreach}
		</td>		
	</tr>

	<tr>
		<td></td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<th colspan="2">Dinner</th>
		<th colspan="2">Dinner Alternate</th>
	</tr>
	<tr>
		<td colspan="2">
			<br>
			{foreach from=$menuItems[2]->content item=menu}
			<ul>{$menu}</ul>
			{/foreach}
		</td>
		<td colspan="2">
			<br>
			{foreach from=$alternates item=alternate}
			<input type="checkbox"> {$alternate}</input><br>
			{/foreach}
		</td>		
	</tr>

	<tr><td colspan="4">&nbsp;</td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td colspan="4" class="text-center text-strong">Please fill out your choices &amp; your completed form will be collected today.<br />Food choices may be changed to meet dietary restrictions.</td>
	</tr>

</table>