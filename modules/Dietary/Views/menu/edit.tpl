

<script>
	$(document).ready(function() {
		$("#reset").submit(function(e) {
			e.preventDefault();
			$.post(SITE_URL, { 
				module: "Dietary",
				page: "MenuMod",
				action: "deleteId",
				id: $("#public-id").val(),
				}, function (response) {
					window.location.href = SITE_URL + "/?module=Dietary&page=dietary&action=current&location=" + $("#location").val();
				}, "json"
			);
		});
	});
</script>

<h1>Edit the Menu for {$date|date_format:"%A, %B %e, %Y"}</h1>

<form name="edit" id="edit" method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="menu" />
	<input type="hidden" name="action" value="submitEdit" />
	<input type="hidden" name="path" value="{$current_url}" />	
	<input type="hidden" name="location" id="location" value="{$location->public_id}" />
	<input type="hidden" name="menu_type" value="{$menuType}" />
	<input type="hidden" name="date" value="{$date}" />
	<input type="hidden" name="public_id" id="public-id" value="{$menuItem->public_id}" />

	<table class="form">
		<tr>
			<td colspan="2"><strong>Menu:</strong></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea name="menu_content" id="menu-content" cols="50" rows="20">
					{foreach $menuItem->content as $menu}
						{$menu|unescape:'html'}
					{/foreach}
				</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"><strong>Reason for menu change:</strong></td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="reason" id="reason" cols="80" rows="10">{if $menuType == "MenuMod"}{$menuItem->reason}{/if}</textarea></td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onclick="history.go(-1)"> </td>
			<td style="text-align: right">
				{if $menuMod}
					<input type="submit" name="reset" value="Reset to Original Item">
				{/if}
				<input type="submit" value="Change Menu"></td>
		</tr>
	</table>
</form>
