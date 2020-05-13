<div class="container">
	<h1>{$prospect->first_name} {$prospect->last_name}</h1>

	<h2>Personal Info</h2>
	<form action="{$SITE_URL}">
		<!-- name -->
		<div class="row">
			<div class="col-sm-12 col-md-5">
				<div class="form-group">
					<label for="first-name">First Name:</label>
					<input type="text" id="first-name" class="form-control" name="first_name" value="{$prospect->first_name}" required>
				</div>
			</div>
			<div class="col-sm-12 col-md-7">
				<div class="form-group">
					<label for="last-name">Last Name:</label>
					<input type="text" id="last-name" class="form-control" name="last_name" value="{$prospect->last_name}" required>
				</div>			
			</div>
		</div>		
		<!-- /name -->

		<!-- birthdate and gender -->
		<div class="row">
			<div class="col-sm-12 col-md-3">
				<div class="form-group">
                    <label for="name">Birthdate</label>
                    <input type="text" class="form-control datepicker" id="birthdate" name="birthdate" value="{$prospect->birthdate|date_format:'%d %B, %Y'}" required>
				</div>
			</div>
			<div class="col-sm-12 col-md-2">
				<div class="form-group">
                    <label for="name">Gender</label>
                    <select name="gender" class="form-control" id="gender">
                        <option value=""></option>
                        <option value="male" {if $prospect->gender == "male"} selected{/if}>Male</option>
                        <option value="female" {if $prospect->gender == "female"} selected{/if}>Female</option>
                    </select>
				</div>	
			</div>
			<div class="col-sm-12 col-md-7">
				<div class="form-group">
					<label for="phone">Phone:</label>
					<input type="text" id="phone" class="form-control" name="phone" value="{$prospect->phone}" required>
				</div>
			</div>
		</div>		
		<!-- /birthdate and gender -->

		<!-- email and phone -->
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="email-address">Email Address:</label>
					<input type="text" id="email-address" class="form-control" name="email_address" value="{$prospect->email}">
				</div>				
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="timeframe">Select a timeframe for admission</label>
					<select name="timeframe" id="timeframe" class="form-control">
						<option value=""></option>
						{foreach from=$timeframe item='t'}
						<option value="{$t->id}" {if $prospect->timeframe == $t->id} selected{/if}>{$t->name}</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>
		<!-- /email and phone -->
		

		<br>
		<br>
		<h2>Contact Info</h2>
		
		<!-- Contact Name -->
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div class="form-group">
					<label for="first-name">Name:</label>
					<input type="text" id="first-name" class="form-control" name="first_name" value="{$prospect->contact_name}">
				</div>
			</div>
		</div>		

		<!-- /Contact Name -->

		<!-- phone and email -->
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="email-address">Email Address:</label>
					<input type="text" id="email-address" class="form-control" name="contact_email" value="{$prospect->contact_email}">
				</div>				
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="phone">Phone:</label>
					<input type="text" id="phone" class="form-control" name="contact_phone" value="{$prospect->contact_phone}">
				</div>
			</div>

		</div>
		<!-- /phone and email -->
		
		<div class="row">
			<div class="col-12 text-right">
				<button type="button" class="btn btn-secondary" onClick="history.go(-1)">Cancel</button>
				<button type="submit" class="btn btn-primary">Save</button>				
			</div>
		</div>
	</form>
</div>