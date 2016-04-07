

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

		$(".facilities-list").hide();

		$('input:radio[name="edit_type"]').change(function() {
			if ($(this).is(':checked') && $(this).val() == 'select_locations') {
				$(".facilities-list").show();
			} else {
				$(".facilities-list").hide();
			}
		});
	});
</script>

<h1>Edit Menu Item</h1>

<form name="edit" id="edit" method="post" action="{$SITE_URL}">
	<input type="hidden" name="page" value="menu" />
	<input type="hidden" name="action" value="edit_corporate_menu" />
	<input type="hidden" name="path" value="{$current_url}" />	
	<input type="hidden" name="type" value="{$menuType}" />
	<input type="hidden" name="id" id="public-id" value="{$menuItem->public_id}" />

	<table class="form">
		<tr>
			<td colspan="3"><strong>Menu:</strong></td>
		</tr>
		<tr>
			<td colspan="3">
				<textarea name="menu_content" id="menu-content" cols="50" rows="20">{foreach $menuItem->content as $menu}{$menu}{/foreach}</textarea>
			</td>
		</tr>
		<tr>
			<td style="width: 135px">&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td colspan="3"><strong>Make the change to:</strong></td>
		</tr>
		<tr id="change-type">
			<td><input type="radio" name="edit_type" value="corp_menu">Corporate Menu</td>
			<td colspan="2"><input type="radio" name="edit_type" id="individualLocations" value="select_locations">Individual Locations <span class="text-10">(recurring only for the selected locations)</span></td>
		</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr class="facilities-list">
				<td colspan="3">
					<table>
						<tr>
						{foreach from=$allLocations item=facility key=key name=facilities}
							<td><input type="checkbox" name="facility{$key}" value="{$facility->public_id}">{$facility->name}</td>
						{if $smarty.foreach.facilities.iteration is div by 2}
						</tr>
						<tr class="facilities-list">
						{/if}
						{/foreach}
						</tr>
					</table>


		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td><input type="button" value="Cancel" onclick="history.go(-1)"> </td>
			<td>&nbsp;</td>
			<td style="text-align: right">
				{if $menuMod}
					<input type="submit" name="reset" value="Reset to Original Item">
				{/if}
				<input type="submit" value="Change Menu"></td>
		</tr>


	</table>
</form>
