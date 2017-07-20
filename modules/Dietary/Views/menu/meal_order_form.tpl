<!-- modules/Dietary/Views/menu/meal_order_form.tpl -->
<div id="meal-order-form">
	<div class="meal-order-form-header">
		<img src="{$IMAGES}/logos_black_reduced/{$location->logo}" alt="">
	</div>
	<br>
	<h2>Daily Patient Meal Order</h2>
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
			<td>&nbsp;</td>
			<th colspan="2">Breakfast Alternate</th>
		</tr>
		<tr>
			<td colspan="2">
				{foreach from=$menuItems[0]->content item=menu}
					<p>{$menu}</p>
				{/foreach}
			</td>
			<td width="10" class="text-center" style="vertical-align:middle;">OR</td>
			<td colspan="2">
				&nbsp;
				<input type="checkbox"> Special Egg: ______________________________________ <br>
				&nbsp;
				<input type="checkbox"> Bacon <br>
				&nbsp;
				<input type="checkbox"> Sausage <br>
				&nbsp;
				<input type="checkbox"> Hot Cereal <br>
				&nbsp;
				<input type="checkbox"> Cold Cereal <br>
				&nbsp;
				<input type="checkbox"> Special Toast: ____________________________________ <br>
			</td>
		</tr>

		<tr>
			<td></td>
		</tr>

		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<th colspan="2">Lunch</th>
			<td>&nbsp;</td>
			<th colspan="2">Lunch Alternate (Please select one)</th>
		</tr>
		<tr>
			<td colspan="2">
				{foreach from=$menuItems[1]->content item=menu}
					<p>{$menu}</p>
				{/foreach}
			</td>
			<td class="text-center" style="vertical-align:middle">OR</td>
			<td colspan="2">
				&nbsp;
				{foreach from=$alternates item=alternate}
				<input type="checkbox"> {$alternate}</input><br>
				&nbsp;
				{/foreach}
			</td>
		</tr>

		<tr>
			<td></td>
		</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<th colspan="2">Dinner</th>
			<td>&nbsp;</td>
			<th colspan="2">Dinner Alternate (Please select one)</th>
		</tr>
		<tr>
			<td colspan="2">
				{foreach from=$menuItems[2]->content item=menu}
					<p>{$menu}</p>
				{/foreach}
			</td>
			<td class="text-center" style="vertical-align:middle">OR</td>
			<td colspan="2">
				&nbsp;
				{foreach from=$alternates item=alternate}
				<input type="checkbox"> {$alternate}</input><br>
				&nbsp;
				{/foreach}
			</td>
		</tr>

		<tr><td colspan="5">&nbsp;</td></tr>
		<tr><td colspan="5">&nbsp;</td></tr>
		<tr>
			<td colspan="5" class="text-center text-strong">Please fill out your choices &amp; your completed form will be collected today.<br />Food choices may be changed to meet dietary restrictions.</td>
		</tr>

	</table>
</div>
