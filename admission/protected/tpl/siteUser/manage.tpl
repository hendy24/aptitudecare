{setTitle title="Manage Users"}
<h1 class="text-center">Manage Users</h1>

{jQueryReady}
	$('#selectFacility').change(function() {
		document.location = SITE_URL + '/?page=siteUser&action=manage&facility=' + $('#selectFacility option:selected').val();
	});
{/jQueryReady}

<div class="left">
	<a href="{$SITE_URL}/?page=siteUser&action=add" class="button">New User</a>
</div>
<div class="right">
	<select id="selectFacility" name="facilities">
		<option value="">Select a facility...</option>
		{foreach $facilities as $f}
			<option value="{$f->pubid}" {if $facility->name == $f->name} selected{/if}>{$f->name}</option>
		{/foreach}
	</select>
</div>
<br />
<br />
<br />
<br />
<table cellpadding="5" cellspacing="0">
	<tr>
		<th>Name</th>
		<th>Username (Email Address)</th>
		<th>Phone</th>
		<th>Role</th>
	</tr>
	{foreach $users as $user}
		<tr bgcolor="{cycle values="#d0e2f0,#ffffff"}">
			<td><a href="{$SITE_URL}/?page=siteUser&action=edit&facility={$facility->pubid}&user={$user->pubid}">{$user->last}, {$user->first}</a></td>
			<td>{$user->email}</td>
			<td>{$user->phone}</td>
			<td>{$user->description}</td>
		</tr>
	{/foreach}
</table>
		
