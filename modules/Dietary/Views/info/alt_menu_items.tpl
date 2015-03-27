<div id="locations">
	{include file="$VIEWS/elements/select-facility.tpl"}
</div>
<h1 class="no-margin">Alternate Menu Items</h1>

<form name="alt_menu_items" id="alt-menu-items" method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="info">
	<input type="hidden" name="action" value="submitAltItems">
	<input type="hidden" name="alt_menu_id" value="{$alternates->id}">
	<input type="hidden" name="path" value="{$current_url}">
	<input type="hidden" name="location" id="location" value="{$location->public_id}" />

	<table class="form">
		<tr>
			<td colspan="2">
				<textarea name="alt_menu" id="alt-menu" cols="75" rows="10">
					{$alternates->content|unescape:"html"};
				</textarea>
			</td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onclick="history.go(-1)"></td>
			<td class="text-right"><input type="submit" value="Change Alternates"></td>
		</tr>
	</table>	
</form>


