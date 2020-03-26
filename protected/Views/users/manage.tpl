<div class="container manage-users-page">
	
	<!-- top navigation -->
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div id="locations" class="input-group">
				{if count($locations) > 1}
					<select name="location" id="location" class="form-control">
					{foreach $locations as $location}
						<option value="{$location->public_id}" {if $location->public_id == $location_id} selected{/if}><h1>{$location->name}</h1></option>
					{/foreach}
					{foreach $areas as $area}
						<option value="{$area->public_id}" {if $area->public_id == $location_id} selected{/if}><h1>{$area->name}</h1></option>
					{/foreach}
					</select>
				{/if}
			</div>
		</div>
		<div class="col-md-4">
			<div class="col-lg-12">
				<a class="btn btn-primary text-white float-right" href="{$SITE_URL}/?page=users&amp;action=user&amp;type=add&amp;location={$location_id}">Add New</a>
			</div>

		</div>
	</div>
	<!-- top navigation -->

	<div class="row">
		<div class="col-12 my-5 text-center">
			<h2>Manage Users</h2>
		</div>
	</div>





	<table class="table">
		<thead>
			<tr>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Email</th>
				<th>Phone</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>

			{foreach $users as $user}
			<tr>
				<td>{$user->last_name}</td>
				<td>{$user->first_name}</td>
				<td>{$user->email}</td>
				<td>{$user->phone}</td>
				<td>
					<a href="{$SITE_URL}/?page=users&amp;action=user&amp;type=edit&amp;location={$location_id}&amp;id={$user->public_id}">
						<i class="fas fa-edit"></i>
					</a>
				</td>
				<td>
					<a href="" value="{$user->public_id}" data-toggle="modal" data-target="#deleteModal" class="delete">
						<i class="fas fa-trash"></i>
						<input type="hidden" name="public_id" class="public-id" value="{$user->public_id}" />
					</a>
				</td>
			</tr>
			{/foreach}
		</tbody>
	</table>


	{if isset ($pagination)}
		<div id="pagination">
			{if $pagination->current_page != 1}
				<a href="{$SITE_URL}?page=users&amp;action=manage&amp;location={$location_id}&amp;page_count={$pagination->current_page - 1}">&laquo;&nbsp; Previous</a>
			{/if}
			{for $i=max($pagination->current_page-5, 1); $i<=max(1, min($pagination->num_pages,$pagination->current_page+5)); $i++}
				{if $i == $pagination->current_page}
					<strong><a href="{$SITE_URL}?page=users&amp;action=manage&amp;location={$location_id}&amp;page_count={$i}" class="page-numbers">{$i}</a></strong>
				{else}
					<a href="{$SITE_URL}?page=users&amp;action=manage&amp;location={$location_id}&amp;page_count={$i}" class="page-numbers">{$i}</a>
				{/if}
			{/for}
			{if $pagination->current_page != $pagination->num_pages}
				<a href="{$SITE_URL}?page=users&amp;action=manage&amp;location={$location_id}&amp;page_count={$pagination->current_page + 1}">Next &nbsp;&raquo;</a>
			{/if}
		</div>
		{if $pagination->num_pages > 20}
		<div id="beginning-end">
			<a href="{$SITE_URL}?page=users&amp;action=manage&amp;location={$location_id}&amp;page_count=1" class="page-numbers">First Page</a>
			<a href="{$SITE_URL}?page=users&amp;action=manage&amp;location={$location_id}&amp;page_count={floor($pagination->num_pages)}" class="page-numbers">Last Page</a>
		</div>
		{/if}
	{/if}
</div>

{$this->loadElement("deleteModal")}

