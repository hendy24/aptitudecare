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
								page: "users",
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
			window.location = SITE_URL + "/?page=data&action=manage&type=" + $("#page").val() + "&orderBy=" + order;
		});


		$("#locations").change(function() {
			window.location = SITE_URL + "/?page=users&action=manage&location=" + $("#locations option:selected").val();
		});

	});
</script>


<div id="page-header">
	<div id="action-left">
		<a class="button" href="{$SITE_URL}/?page=users&amp;action=add&amp;location={$location_id}">Add New</a>
	</div>
	<div id="center-title">
		<div id="locations">
			<select name="location" id="location">
			{foreach $locations as $location}
				<option value="{$location->public_id}" {if $location->public_id == $location_id} selected{/if}><h1>{$location->name}</h1></option>
			{/foreach}
			</select>
		</div>
	</div>
	<div id="action-right">
		{$this->loadElement("selectArea")}
	</div>
</div>


<h2>Manage Users</h2>

<table class="view">
	<tr>
		<th>Last Name</th>
		<th>First Name</th>
		<th>Email</th>
		<th>Phone</th>
		<th>Default Location</th>
		<th>Default Module</th>
		<th>Group Name</th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Edit</span></th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Delete</span></th>
	</tr>

	{foreach $users as $user}
	<tr>
		<td>{$user->last_name}</td>
		<td>{$user->first_name}</td>
		<td>{$user->email}</td>
		<td>{$user->phone}</td>
		<td>{$user->default_location}</td>
		<td>{$user->default_module}</td>
		<td>{$user->group_name}</td>
		<td>
			<a href="{$SITE_URL}/?page=users&amp;action=edit&amp;location={$location_id}&amp;id={$user->public_id}">
				<img src="{$FRAMEWORK_IMAGES}/pencil.png" alt="">
			</a>
		</td>
		<td>
			<a href="" value="{$user->public_id}" class="delete">
				<img src="{$FRAMEWORK_IMAGES}/delete.png" alt="">
				<input type="hidden" name="public_id" class="public-id" value="{$user->public_id}" />
			</a>
		</td>
	</tr>
	{/foreach}
</table>


<div id="dialog" title="Confirmation Required">
	<p>Are you sure you want to delete this item? This cannot be undone.</p>
</div>
