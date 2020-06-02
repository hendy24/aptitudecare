<div class="container">

	<h1>Contact</h1>
	
	<form id="residentContact" action="{$SITE_URL}">
		<input type="hidden" name="module" value="Admissions">
		<input type="hidden" name="page" value="admissions">
		<input type="hidden" name="action" value="save_contact">
		<input type="hidden" id="prospect-id" name="prospect_id" value="{$resident_id}">
		<input type="hidden" id="contact-id" name="contact_id" value="{$contact->id}">
		<input type="hidden" name="contact_link" value="{$contact_link->id}">


		<!-- contact type -->
		<div class="row">
			<div class="col-12">
	            <div class="form-group">
	                <label for="contact-type">Contact Type</label>
	                <select name="contact_type" id="contact-type" class="form-control" required>
	                    <option value=""></option>
	                    {foreach from=$contact_type item="ct"}
	                    <option value="{$ct->id}" {if $contact_link->contact_type == $ct->id} selected{/if}>{$ct->name}</option>
	                    {/foreach}
	                </select>
	            </div>
			</div>
		</div>
		<!-- /contact type -->

		<!-- contact Name -->
		<div class="row">
			<div class="col-6">
				<div class="form-group">
				    <label for="first-name">First Name</label>
				    <input type="text" class="form-control" id="first-name" name="first_name" value="{$contact->first_name}" required>
				</div>
			</div>

			<div class="col-6">
				<div class="form-group">
				    <label for="last-name">Last Name</label>
				    <input type="text" class="form-control" id="last-name" name="last_name" value="{$contact->last_name}" required>
				</div>
			</div>
		</div>
		<!-- /contact Name -->


		<!-- phone and email -->
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="email-address">Contact Email:</label>
					<input type="text" id="email-address" class="form-control" name="email" value="{$contact->email}" required>
				</div>				
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="phone">Contact Phone:</label>
					<input type="text" class="form-control phone" name="phone" value="{$contact->phone}" required>
				</div>
			</div>

		</div>
		<!-- /phone and email -->				



		<!-- physical address -->
		<div class="row">
			<div class="col-12">
				<div class="form-group">
				    <label for="address">Address</label>
				    <input type="text" class="form-control" id="address" name="address" value="{$contact->address}">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
				    <label for="city">City</label>
				    <input type="text" class="form-control" id="city" name="city" value="{$contact->city}">
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
				    <label for="state">State</label>
				    <select name="state" id="state" class="form-control">
				        <option value=""></option>
				        {foreach from=$states item="s"}
				        <option value="{$s->abbr}" {if $contact->state == $s->abbr} selected{/if}>{$s->abbr} - {$s->name}</option>
				        {/foreach}
				    </select>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
				    <label for="zip">Zip Code</label>
				    <input type="text" class="form-control" id="zip" name="zip" value="{$contact->zip}">
				</div>
			</div>
		</div>
		<!-- /physical address -->

		<!-- poa and primary contact -->
		<div class="row">
			<div class="col-12">
				<div class="form-check form-check-inline">
				    <input type="checkbox" id="poa" name="poa" value="1" class="form-check-input" {if $contact_link->poa} checked{/if}>
				    <label for="poa" class="form-check-label">Power of Attorney</label>
				</div>
				<div class="form-check form-check-inline">
				    <input type="checkbox" id="primary-contact" name="primary_contact" value="1" class="form-check-input" {if $contact_link->primary_contact} checked{/if}>
				    <label for="primary_contact" class="form-check-label">Primary Contact</label>
				</div>
			</div>
		</div>
		<!-- /poa and primary contact -->

		<div class="row">
			<div class="col-12 text-right">
				<button type="button" id="save-contact" class="btn btn-primary">Save</button>
			</div>
		</div>
	</form>

</div>


<script>
	$(".phone").mask("(999) 999-9999");

	$("#save-contact").click(function() {
		var prospect = $("#prospect-id").val();
		var contact = $("#contact-id").val();
		var contactType = $("#contact-type :selected").val();
		var pipeline = $("#pipeline").val();
		var data = $("#residentContact").serialize();

		$.post(SITE_URL, data, function (e) {
				//console.log(e);
				location.reload();
			}
		);
	});


</script>