<h1>Manage Menus</h1>


<table class="table">
	<tr>
		<th>Menu Name</th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Edit</span></th>
		<th style="width:20px;font-weight:normal"><span class="text-darker-grey">Delete</span></th>
	{foreach from=$menus item=menu}
	<tr>
		<td>{$menu->name}</td>
		<td>
			<a href="{$SITE_URL}/?module=Dietary&amp;page=info&amp;action=edit_menu&amp;menu={$menu->public_id}">
				<i class="fas fa-edit"></i>
			</a>
		</td>
		<td>
			<a href="" value="{$menu->public_id}" data-toggle="modal" data-target="#deleteModal" class="delete">
				<i class="fas fa-trash"></i>
				<input type="hidden" name="public_id" class="public-id" value="{$menu->public_id}" />
			</a>
		</td>
	</tr>
	{/foreach}
</table>

{$this->loadElement("deleteModal")}
