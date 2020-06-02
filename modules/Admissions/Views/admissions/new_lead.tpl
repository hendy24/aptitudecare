<div class="container">
	<h1>New Prospect Lead</h1>

	<form action="{$SITE_URL}" class="form" method="post">
		<input type="hidden" name="module" value="Admissions">
		<input type="hidden" name="page" value="admissions">
		<input type="hidden" name="action" value="save-lead">
		<input type="hidden" name="path" value="{$current_url}">

		<h2>Resident Info</h2>
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="first-name">First Name:</label>
					<input type="text" id="first-name" class="form-control" name="first_name" required>
				</div>				
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="last-name">Last Name:</label>
					<input type="text" id="last-name" class="form-control" name="last_name" required>
				</div>				
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="email-address">Email Address:</label>
					<input type="text" id="email-address" class="form-control" name="email_address">
				</div>				
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="phone">Phone:</label>
					<input type="text" class="form-control phone" name="phone" required>
				</div>				
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="referral-source">Timeframe</label>
					<select name="timeframe" id="timeframe" class="form-control">
						<option value=""></option>
						{foreach from=$timeframe item='t'}
						<option value="{$t->id}">{$t->name}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="referral-source">Referral Source</label>
				    <select name="referral_source" id="referral-source" class="form-control">
				        <option value=""></option>
				        {foreach from=$referral_sources item="rs"}
				        <option value="$rs->id">{$rs->name}</option>
				        {/foreach}
				    </select>
				</div>				
			</div>
		</div>

		<br>
		<h2>Contact Info</h2>
		
		<!-- Contact Name -->
		<div class="row">
			<div class="col-sm-8 col-md-6">
				<div class="form-group">
					<label for="first-name">Contact Name:</label>
					<input type="text" id="first-name" class="form-control" name="contact_name" value="">
				</div>
			</div>
            <div class="col-sm-4 col-md-6">
                <div class="form-group">
                    <label for="contact-type">Contact Relationship</label>
                    <select name="contact_type" id="contact-type" class="form-control" required>
                        <option value=""></option>
                        {foreach from=$contact_type item="ct"}
                        <option value="$ct->id">{$ct->name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
		</div>		

		<!-- /Contact Name -->

		<!-- phone and email -->
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="email-address">Contact Email:</label>
					<input type="text" id="email-address" class="form-control" name="contact_email" value="">
				</div>				
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="phone">Contact Phone:</label>
					<input type="text" class="form-control phone" name="contact_phone" value="">
				</div>
			</div>
		</div>
		<!-- /phone and email -->
		



		<div class="row float-right my-4">
			<div class="col-md-12">
				<button type="button" class="btn btn-secondary" onClick="history.go(-1)">Cancel</button>
				<button type="submit" class="btn btn-primary">Save</button>			
			</div>
		</div>

	</form>
</div>