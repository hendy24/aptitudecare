<div id="profile" class="container ">
	<h1>{$prospect->first_name} {$prospect->last_name}</h1>

	<form action="{$SITE_URL}" enctype="multipart/form-data">
		<input type="hidden" name="module" value="Admissions">
		<input type="hidden" name="page" value="admissions">
		<input type="hidden" name="action" value="save_profile">
		<input type="hidden" name="id" value="{$prospect->public_id}">
		<input type="hidden" name="pipeline" value="{$pipeline}">

		<div id="accordion">


			<!-- resident info -->
			<div class="card">
				<div class="card-header" id="headingOne">
				  <h2 class="mb-0">
				    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				      <i class="fa fa-plus mr-3"></i>Personal Info
				    </button>
				  </h2>
				</div>
			
				<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
			  		<div class="card-body">
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

						<!-- physical address -->
						<div class="row">
							<div class="col-12">
								<div class="form-group">
								    <label for="address">Resident Address</label>
								    <input type="text" class="form-control" id="address" name="address" value="{$prospect->address}">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="form-group">
								    <label for="city">City</label>
								    <input type="text" class="form-control" id="city" name="city" value="{$prospect->city}">
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="form-group">
								    <label for="state">State</label>
								    <select name="state" id="state" class="form-control">
								        <option value=""></option>
								        {foreach from=$states item="s"}
								        <option value="{$s->abbr}" {if $prospect->state == $s->abbr} selected{/if}>{$s->abbr} - {$s->name}</option>
								        {/foreach}
								    </select>
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="form-group">
								    <label for="zip">Zip Code</label>
								    <input type="text" class="form-control" id="zip" name="zip" value="{$prospect->zip}">
								</div>
							</div>
						</div>
						<!-- /physical address -->

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
									<label for="phone">Resident Phone:</label>
									<input type="text" class="form-control phone" name="phone" value="{$prospect->phone}" required>
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
								<p>Is the resident a veteran?</p>
								<div class="form-check form-check-inline">			
								    <input type="radio" id="veteran" name="veteran" value="1" class="form-check-input" {if $prospect->veteran == 1} checked{/if}>
								    <label for="veteran" class="form-check-label">Yes</label>
								</div>
								<div class="form-check form-check-inline">
								    <input type="radio" id="veteran1" name="veteran" value="0" class="form-check-input" {if $prospect->veteran == 0} checked{/if}>
								    <label for="veteran1" class="form-check-label">No</label>
								</div>
							</div>
							<!-- <div class="col-sm-12 col-md-6">
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
						</div> -->
						<!-- /email and phone -->
			  		</div>

			  		<!-- religion and career -->
			  		<div class="row">
			  			<div class="col-sm-12 col-md-6">
			  				<div class="form-group">
			  				    <label for="religion-preference">Religion Preference</label>
			  				    <select name="religion_preference" id="religion-preference" class="form-control">
			  				        <option value=""></option>
			  				        {foreach from=$religion_preferences item="rp"}
			  				        <option value="{$rp->id}" {if $prospect->religion_preference == $rp->id} selected{/if}>{$rp->name}</option>
			  				        {/foreach}
			  				    </select>
			  				</div>
			  			</div>
			  			<div class="col-sm-12 col-md-6">
			  				<div class="form-group">
			  				    <label for="profession">Former Profession</label>
			  				    <input type="text" class="form-control" id="profession" name="profession" value="{$prospect->profession}">
			  				</div>
			  			</div>
			  		</div>
			  		<!-- /religion and career -->

			  		<!-- background info -->
			  		<div class="row">
			  			<div class="col-12">
			  				<div class="form-group">
			  					<label for="background-info">Tell us a little about the resident's life</label>
			  					<textarea name="background_info" id="background-info" class="form-control" rows="5">{$prospect->background_info}</textarea>
			  				</div>
			  			</div>
			  		</div>
			  		<!-- /background info -->
				</div>
			</div>
			<!-- /resident info -->




			<!-- contact info -->
			<div class="card">
				<div class="card-header" id="headingTwo">
				  <h2 class="mb-0">
				    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
				      	<i class="fa fa-plus mr-3"></i>Contact Info
				    </button>
				  </h2>
				</div>
			
				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
			  		<div class="card-body">
			  			<div class="row mb-3">
							<div class="col-12 text-white">
								<button type="button" id="addContact" class="modal-webpage btn btn-primary" data-toggle="modal" data-remote="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=add_contact&amp;prospect_id={$prospect->public_id}&amp;pipeline={$pipeline}" data-target="#addNewContact">Add New Contact</a>
							</div>
						</div>


						<table class="table contact-info">
							<thead>
								<th>&nbsp;</th>
								<th>Name</th>
								<th>Contact</th>
								<th>Address</th>
								<th>Contact Type</th>
								<th>&nbsp;</th>
							</thead>
							<tbody>						
								{foreach from=$contacts item="c"}
								<tr>
									<td>
										{if $c->primary_contact}<i class="fas fa-hospital-user" data-toggle="tooltip" data-placement="top" title="Primary Contact"></i>{/if}
										{if $c->poa}<i class="fas fa-balance-scale-left" data-toggle="tooltip" data-placement="top" title="Power of Attorney"></i>{/if}
									</td>
									<td>{$c->first_name} {$c->last_name}</td>
									<td>
										<p><a href="tel:{$c->phone}">{$c->phone}</a></p>
										<p><a href="mailto:{$c->email}">{$c->email}</a></p>
									</td>
									<td>
										<p>{$c->address}</p>
										<p>{$c->city}, {$c->state} {$c->zip}</p>
									</td>
									<td>{$c->contact_type}</td>
									<td>
										<button type="button" class="modal-webpage btn" data-toggle="modal" data-remote="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=resident_contact&amp;prospect_id={$prospect->public_id}&amp;contact_id={$c->public_id}&contact_link={$c->contact_link}&amp;pipeline={$pipeline}" data-target="#addNewContact"><i class="fas fa-user-edit"></i></button>
										<a class="btn delete" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-user-minus"></i></a>
										<input type="hidden" class="prospect-id" value="{$prospect->public_id}">
										<input type="hidden" class="contact-id" value="{$c->public_id}">
										<input type="hidden" class="contact-link" value="{$c->contact_link}">
									</td>
								</tr>
								{/foreach}
							</tbody>

						</table>

			  		</div>
				</div>
			</div>
			<!-- /contact info -->



			<!-- financial info -->			
			<div class="card">
				<div class="card-header" id="headingFour">
				  <h5 class="mb-0">
				    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseOne">
				      <i class="fa fa-plus mr-3"></i>Financial Info
				    </button>
				  </h5>
				</div>
			
				<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
			  		<div class="card-body">
						
			  		</div>
				</div>
			</div>
			<!-- /financial info -->


			<!-- <div class="card">
				<div class="card-header" id="headingFive">
				  <h5 class="mb-0">
				    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseOne">
				      <i class="fa fa-plus mr-3"></i>Health Assessment
				    </button>
				  </h5>
				</div>
			
				<div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
			  		<div class="card-body">
						
			  		</div>
				</div>
			</div> -->
									


			<!-- files -->
			<div class="card">
				<div class="card-header" id="headingThree">
				  <h2 class="mb-0">
				    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseOne">
				      <i class="fa fa-plus mr-3"></i>Documents
				    </button>
				  </h2>
				</div>
			
				<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
			  		<div id="file-card" class="card-body">
			  			<div class="row file-select">
			  				<div class="col-sm-12 col-md-4">
			  					<div class="form-group">
			  					    <select name="file_type[]" id="file-type" class="form-control">
			  					        <option value="">Select file type...</option>
			  					        {foreach from=$file_type item="ft"}
			  					        <option value="{$ft->id}">{$ft->name}</option>
			  					        {/foreach}
			  					    </select>
			  					</div>
			  				</div>
			  				<div class="col-sm-10 col-md-7">
								<div class="custom-file">
									<input type="file" name="file[]" class="custom-file-input">
									<label for="" class="custom-file-label">Choose File</label>
								</div>			  					
			  				</div>
			  				<div class="col-sm-2 col-md-1">
			  					<button type="button" class="btn btn-primary add-file active-button"><i class="fa fa-plus"></i></button>
			  				</div>
			  			</div>
			  		</div>
				</div>
			</div>		
			<!-- /files -->

		</div>

				
		<div class="row mt-4">
			<div class="col-12 text-right">
				<button type="button" class="btn btn-secondary" onClick="history.go(-1)">Cancel</button>
				<button type="submit" class="btn btn-primary">Save</button>				
			</div>
		</div>
	</form>
</div>


{$this->loadElement('deleteModal')}
{$this->loadElement('webpageModal', "")}