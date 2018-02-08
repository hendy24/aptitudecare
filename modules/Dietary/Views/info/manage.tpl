<script>
	$(document).ready(function() {
		$(".delete").click(function(e) {
			e.preventDefault();
			var dataRow = $(this).parent().parent();
			var item = $(this);
			$("#dialog").dialog({
				buttons: {
					"Confirm": function() {
						var row = item.children().next($(".public-id"));
						var id = row.val();
							
						$.ajax({
							type: 'post',
							url: SITE_URL,
							data: {
								page: "info",
								action: 'delete_menu',
								menu: id,
							},
							success: function() {
								dataRow.fadeOut("slow");
							}
						});
						$(this).dialog("close");

					},
					"Cancel": function() {
						$(this).dialog("close");
					}
				}
			});
		});

	});
</script>


<h1>Manage Menus</h1>
<br>
<table class="view">
	<tr>
		<th>Menu Name</th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Edit</span></th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Delete</span></th>
	{foreach from=$menus item=menu}
	<tr>
		<td>{$menu->name}</td>
		<td>
			<a href="{$SITE_URL}/?module=Dietary&amp;page=info&amp;action=edit_menu&amp;menu={$menu->public_id}">
				<img src="{$FRAMEWORK_IMAGES}/pencil.png" alt="">
			</a>
		</td>
		<td>
			<a href="" value="{$menu->public_id}" class="delete">
				<img src="{$FRAMEWORK_IMAGES}/delete.png" alt="">
				<input type="hidden" name="public_id" class="public-id" value="{$menu->public_id}" />
			</a>
		</td>
	</tr>
	{/foreach}
</table>




<div id="dialog" title="Confirmation Required">
	<p>Are you sure you want to delete this item? This cannot be undone.</p>
</div>