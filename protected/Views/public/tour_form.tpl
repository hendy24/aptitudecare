<div class="container tour-form">
	<div class="row mt-5">
		<div class="col-12 text-center">
			<img src="{$IMAGES}/logo-black.png" alt="">
		</div>
	</div>
	<form action="{$SITE_URL}">
		<input type="hidden" name="page" value="public">
		<input type="hidden" name="action" value="submit_tour_form">

		<!-- potential resident info -->		
		<h2>Potential Resident Info</h2>
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="Resident-first-name">First Name</label>
				    <input type="text" class="form-control" id="resident-first-name" name="resident_first_name" required>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="resident-last-name">Last Name</label>
				    <input type="text" class="form-control" id="resident-last-name" name="resident_last_name" required>
				</div>
			</div>
		
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="resident-email">Email Address</label>
				    <input type="text" class="form-control" id="resident-email" name="resident_email">
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="resident-phone">Phone Number</label>
				    <input type="text" class="form-control phone" id="resident-phone" name="resident_phone">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="timeframe">Approximate Date Needed</label>
				    <select name="timeframe" id="timeframe" class="form-control" required>
				        <option value=""></option>
				        {foreach from=$timeframe item="t"}
				        <option value="{$t->id}">{$t->name}</option>
				        {/foreach}
				    </select>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<p>Primary Payor Source</p>
				{foreach from=$payor_source key=k item=v}
				<div class="form-check form-check-inline">
				    <input type="radio" id="payor-source{$k}" name="payor_source" value="{$v->id}" class="form-check-input">
				    <label for="payor-source{$k}" class="form-check-label">{$v->name}</label>
				</div>
				{/foreach}
			</div>
		</div>
		<!-- /potential resident info -->


		<!-- primary contact info -->	
		<h2>Primary Contact</h2>
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="contact-first-name">First Name</label>
				    <input type="text" class="form-control" id="contact-first-name" name="contact_first_name" required>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="contact-last-name">Last Name</label>
				    <input type="text" class="form-control" id="contact-last-name" name="contact_last_name" required>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="contact-email">Email Address</label>
				    <input type="text" class="form-control" id="contact-email" name="contact_email">
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="contact-phone">Phone Number</label>
				    <input type="text" class="form-control phone" id="contact-phone" name="contact_phone" required>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="contact-type">Relationship/Contact Type</label>
				    <select name="contact_type" id="contact-type" class="form-control" required>
				        <option value=""></option>
				        {foreach from=$contact_type item="t"}
				        <option value="{$t->id}">{$t->name}</option>
				        {/foreach}
				    </select>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="referral-source">Referral Source</label>
				    <select name="referral_source" id="referral-source" class="form-control" required>
				        <option value=""></option>
				        {foreach from=$referral_source item="r"}
				        <option value="{$r->id}">{$r->name}</option>
				        {/foreach}
				    </select>
				</div>
			</div>
		</div>
		<!-- /primary contact info -->

		<div class="row mt-3 mb-5">
			<div class="col-12 text-right">
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</div>
		

	</form>
</div>

<script>
	$(".phone").mask("(999) 999-9999");
</script>