<script>
	$(document).ready(function() {
		$(".delete").click(function(e) {
			e.preventDefault();
			var dataRow = $(this).parent().parent();
			console.log(dataRow);
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
								page: $("#page").val(),
								action: 'deleteId',
								id: id,
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

		$(".order").click(function(e) {
			e.preventDefault();
			var order = $(this).next("input").val();
			console.log
			window.location = SITE_URL + "/?page=physicians&action=manage&orderBy=" + order;
		});


		$("#locations").change(function() {
			window.location = SITE_URL + "/?page=physicians&action=manage&location=" + $("#locations option:selected").val();
		});

	});
</script>

<div id="page-header">
	<div id="action-left"><a class="button" href="{$SITE_URL}/?page=physicians&amp;action=physician&amp;type=add&amp;location={$location_id}">Add New</a></div>
	<div id="center-title">
		<div id="locations">
			<select name="location" id="location">
			{foreach $locations as $location}
				<option value="{$location->public_id}" {if $location->public_id == $location_id} selected{/if}><h1>{$location->name}</h1></option>
			{/foreach}
			</select>
		</div>
	</div>
	<div id="action-right"></div>
</div>

<h2>Manage Physicians</h2>


<table class="view">
	<tr>
		<th>Last Name</th>
		<th>First Name</th>
		<th>City</th>
		<th>State</th>
		<th>Phone</th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Edit</span></th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Delete</span></th>
	</tr>

	{foreach $physicians as $physician}
	<tr>
		<td>{$physician->last_name}</td>
		<td>{$physician->first_name}</td>
		<td>{$physician->city}</td>
		<td>{$physician->state}</td>
		<td>{$physician->phone}</td>
		<td>
			<a href="{$SITE_URL}/?page=physicians&amp;action=physician&amp;type=edit&amp;id={$physician->public_id}">
				<img src="{$FRAMEWORK_IMAGES}/pencil.png" alt="">
			</a>
		</td>
		<td>
			<a href="" value="{$physician->public_id}" class="delete">
				<img src="{$FRAMEWORK_IMAGES}/delete.png" alt="">
				<input type="hidden" name="public_id" class="public-id" value="{$physician->public_id}" />
			</a>
		</td>
	</tr>
	{/foreach}
</table>

{if isset ($pagination)}
	{$url = "{$SITE_URL}?page=physicians&action=manage&location={$location_id}"}
	{include file="elements/pagination.tpl"}	
{/if}


<div id="dialog" title="Confirmation Required">
	<p>Are you sure you want to delete this item? This cannot be undone.</p>
</div>