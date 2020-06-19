<p>
	<button id="searchExistingButton" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#searchExisting" aria-expanded="true" aria-controls="searchExisting">Search Existing Contacts</button>
	<button id="addNewButton" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addNew" aria-expanded="false" aria-controls="addNew">Create a New Contact</button>
</p>

<div class="row">
	<div class="col-12">
		<div class="collapse show" id="searchExisting">
			<div class="card card-body">
				<div class="row">
					<div class="col-sm-3">
				        <div class="form-group">
				            <label for="contact-type">Contact Type</label>
				        	<select id="addExistingContactType" class="form-control">
				               	<option value=""></option>
				            	{foreach from=$contact_type item="ct"}
				                <option value="{$ct->id}">{$ct->name}</option>
				                {/foreach}
				            </select>
				        </div>
					</div>

					<div class="col-sm-4">
						<div class="form-group">
						    <label for="contact-name">Name</label>
						    <input type="text" class="form-control" id="contact-name" value="">
						    <input type="hidden" id="contact-id">
						</div>
					</div>

					<div class="col-sm-4">
						<br>
						<div class="form-check">
						    <input type="checkbox" id="poa" value="1" class="form-check-input">
						    <label for="poa" class="form-check-label">Power of Attorney</label>
						</div>
						<div class="form-check">
						    <input type="checkbox" id="primary-contact" value="1" class="form-check-input">
						    <label for="primary-contact" class="form-check-label">Primary Contact</label>
						</div>
					</div>

					<div class="col-sm-1 text-right">
						<label for="add-button">&nbsp;</label><br>
						<button type="button" id="link-contact" class="btn btn-primary">Add</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col">
		<div class="collapse" id="addNew">
			<div class="card card-body">

				<!-- contact type -->
				<div class="row">
					<div class="col-12">
			            <div class="form-group">
			                <label for="contact-type">Contact Type</label>
			                <select id="addNewContactType" class="form-control">
			                    <option value=""></option>
			                    {foreach from=$contact_type item="ct"}
			                    <option value="{$ct->id}">{$ct->name}</option>
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
						    <input type="text" class="form-control" id="contactFirstName" value="">
						</div>
					</div>

					<div class="col-6">
						<div class="form-group">
						    <label for="last-name">Last Name</label>
						    <input type="text" class="form-control" id="contactLastName" value="">
						</div>
					</div>
				</div>
				<!-- /contact Name -->


				<!-- phone and email -->
				<div class="row">
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label for="email-address">Contact Email:</label>
							<input type="text" id="contactEmail" class="form-control" value="">
						</div>				
					</div>
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label for="phone">Contact Phone:</label>
							<input type="text" id="contactPhone" class="form-control phone" value="">
						</div>
					</div>

				</div>
				<!-- /phone and email -->				



				<!-- physical address -->
				<div class="row">
					<div class="col-12">
						<div class="form-group">
						    <label for="address">Address</label>
						    <input type="text" class="form-control" id="contactAddress" value="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
						    <label for="city">City</label>
						    <input type="text" class="form-control" id="contactCity" value="">
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="form-group">
						    <label for="state">State</label>
						    <select id="contactState" class="form-control">
						        <option value=""></option>
						        {foreach from=$states item="s"}
						        <option value="{$s->abbr}">{$s->abbr} - {$s->name}</option>
						        {/foreach}
						    </select>
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="form-group">
						    <label for="zip">Zip Code</label>
						    <input type="text" class="form-control" id="contactZip" value="">
						</div>
					</div>
				</div>
				<!-- /physical address -->

				<!-- poa and primary contact -->
				<div class="row">
					<div class="col-12">
						<div class="form-check form-check-inline">
						    <input type="checkbox" id="newPoa" value="1" class="form-check-input">
						    <label for="poa" class="form-check-label">Power of Attorney</label>
						</div>
						<div class="form-check form-check-inline">
						    <input type="checkbox" id="newPrimaryContact" value="1" class="form-check-input">
						    <label for="primary_contact" class="form-check-label">Primary Contact</label>
						</div>
					</div>
				</div>
				<!-- /poa and primary contact -->
				
				<div class="row">
					<div class="col text-right">
						<label for="add-button">&nbsp;</label><br>
						<button type="button" id="add-contact" class="btn btn-primary">Add</button>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>







