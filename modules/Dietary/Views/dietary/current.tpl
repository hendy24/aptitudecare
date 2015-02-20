{include file="$VIEWS/elements/{$searchBar}-search.tpl"}
<br>
<h1>Week Beginning {$startDate|date_format: "%A, %B %e, %G"}</h1>
<h2>Week # of the {$menu->name} Menu</h2>

<table>
	{assign var="counter" value="0"}
	{foreach from=$menuItems item="menuItem" name="menuItems"}
	{assign var="counter" value=$counter + 1}
	<tr>
		<td>{$menuItem->content}</td>
		{$menuItem@iteration}
	{if $counter % 3}
	</tr>
	{/if}
	{/foreach}
	
</table>

	{foreach from=$menuItems item='menuItem' name='menuItems'}
		{if $smarty.foreach.attributes.iteration is div by 3}
			<div class="dateTitle">
				{$startDate|date_format}
			</div>
		{/if}
		<div class="menuItems">
			{$menuItem->content|strip_tags|unescape:"html"}
		</div>
	{/foreach}
	<div class="clear"></div>
</div>
