<!-- page container -->
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h1>{$page_header}</h1>
		</div>
	</div>



	
	<form name="user" id="edit" method="post" action="{$SITE_URL}">
		<input type="hidden" name="page" value="users" />
		<input type="hidden" name="action" value="save_user" />
		<input type="hidden" name="id" value="{$user->public_id}" />
		<input type="hidden" name="location_public_id" value="{$current_location}">
		<input type="hidden" name="path" value="{$current_url}" />

		<!-- name -->
		<div class="form-group row">
			<div class="col-md-6 col-sm-12">
				<label for="first-name">First Name:</label>
				<input type="text" class="form-control" name="first_name" id="first-name" value="{$user->first_name}">
			</div>
			<div class="col-md-6 col-sm-12">
				<label for="last-name">Last Name:</label>
				<input type="text" class="form-control" name="last_name" id="last-name" value="{$user->last_name}">
			</div>
		</div>
		<!-- /name -->

		<!-- email, phone -->
		<div class="form-group row">
			<div class="col-md-8 col-sm-12">
				<label for="email">Email:</label>
				<input type="text" class="form-control" name="email" id="email" value="{$user->email}">
			</div>
			<div class="col-md-4 col-sm-12">
				<label for="phone">Phone:</label>
				<input type="text" class="form-control" name="phone" id="phone" value="{$user->phone}">
			</div>
		</div>
		<!-- /email, phone -->

		<!-- password -->
		{if $existing}
		<div class="row">	
			<div class="col-sm-12">
				<a href="{$SITE_URL}/?page=users&amp;action=reset_password&amp;id={$user->public_id}" class="btn btn-secondary">Reset Password</a>
			</div>	
		</div>
		{else}
		<div class="form-group row">
			<div class="col-md-8 col-sm-12">
				<label for="password">Password:</label>
				<input type="password" class="form-control" name="password" id="password">
			</div>
			<div class="col-md-4 col-sm-12">
				<label for="verify-password">Verify Password:</label>
				<input type="password" class="form-control" name="verify_password" id="verify-password">
			</div>	
		</div>
		<div class="form-group row">
			<div class="col-sm-12 text-right">
				<input type="checkbox" class="form-check-input text-right" name="temp_password" id="temp-password" value="true"> 
				<label class="form-check-label" for="temp-password">Temporary Password</label>
			</div>
		</div>
		{/if}
		<!-- /password -->


		<!-- default location -->
		<div class="form-group row">
			<div class="col-sm-12">
				<label for="user-location">Default Location:</label>
				<select name="default_location" id="user-location" class="form-control">
					<option value="">Select a location...</option>
					{foreach $additional_locations as $location}
					<option value="{$location->id}" {if $default_location == $location->id} selected{/if}>{$location->name}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<!-- /default location -->

		<!-- additional locations -->
		{if count($additional_locations) > 1}
			{foreach $additional_locations as $k => $loc name=count}
			<input type="checkbox" name="additional_locations[{$k}]" class="form-check-input" id="{$loc->id}" value="{$loc->id}" {foreach $assigned_locations as $location} {if $location->id == $loc->id} checked{/if}{/foreach}> 
			<label class="form-check-label" for="{$loc->id}">{$loc->name}</label>
			{/foreach}
		{/if}
		<!-- additional locations -->


		<!-- default group -->
		<div class="form-group row">
			<div class="col-sm-12">
				<label for="group">Default Group:</label>
				<select name="group" class="form-control" id="group">
					<option value="">Select a group role...</option>
					{foreach $groups as $group}
					<option value="{$group->id}" {if $group_id == $group->id} selected {/if}>{$group->description}</option>
					{/foreach}
				</select>

			</div>
		</div>
		<!-- /default group -->


		<!-- additional groups -->
		<div class="row form-group">
			<div class="col-sm-12">
				Additional Groups:
			</div>
		</div>

		<div class="row form-check mb-5">
			{foreach from=$groups item=group name=count}
				<div class="col-lg-4 col-md-6 col-sm-12">
					<input 
						type="checkbox" 
						class="form-check-input" 
						name="additional_groups[{$k}]" 
						id="{$group->id}" value="{$group->id}" 
						{foreach $user_groups as $ug} 
							{if $group->id == $ug->group_id} 
								checked
							{/if}
						{/foreach}
					> 
					<label class="form-check-label" for="{$group->id}">{$group->description}</label>
				</div>
			{/foreach}
		</div>
		<!-- additional groups -->


		<!-- default module -->
		<div class="row form-group">
			<div class="col-sm-12">
				<label for="user-module">Default Module:</label>
				<select name="default_module" class="form-control" id="user-module">
					<option value="">Select a module...</option>
					{foreach $available_modules as $mod}
						<option value="{$mod->id}" {if $default_mod== $mod->id} selected{/if}>{$mod->name}</option>
					{/foreach}
				</select>

			</div>
		</div>
		<!-- /default module -->


		<!-- additional modules -->
		<div class="row form-check">	
				{foreach from=$available_modules item=module}
				<div class="col-sm-12 col-lg-6">
					<input type="checkbox" class="form-check-input" name="additional_modules[]" id="module-{$module->id}" value="{$module->id}" {foreach $assigned_modules as $assigned}{if $module->id == $assigned->module_id} checked{/if}{/foreach}>
					<label class="form-check-label" for="module-{$module->id}">{$module->name}</label>
				</div>
				{/foreach}	
		</div>
		<!-- /additional modules -->


		<!-- buttons -->
		<input type="button" class="btn btn-secondary" value="Cancel" onClick="history.go(-1);return true;">
		<input class="btn btn-primary" type="submit" value="Save">
		<!-- /buttons -->



</div>
<!-- /page container -->




	<table class="form">	
		<tr>
			<td></td>
			<td></td>
		</tr>
	</table>
</form>`