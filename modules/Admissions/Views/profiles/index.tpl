<div id="profile" class="container ">
	<h1>{$prospect->first_name} {$prospect->last_name}</h1>

	<div id="accordion">
		<form action="{$SITE_URL}/?module=Admissions&page=profiles&action=save_profile&id={$prospect->public_id}" enctype="multipart/form-data">
			<input type="hidden" id="prospect" value="{$prospect->public_id}">
		
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
			  				    <label for="religion">Religion Preference</label>
			  				    <select name="religion" id="religion" class="form-control">
			  				        <option value=""></option>
			  				        {foreach from=$religion item="r"}
			  				        <option value="{$r->id}" {if $prospect->religion == $r->id} selected{/if}>{$r->name}</option>
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

						<table class="table contact-info">
							<thead>
								<th>&nbsp;</th>
								<th>Name</th>
								<th>Contact Type</th>
								<th>&nbsp;</th>
							</thead>
							<tbody id="contact-table-body">						
								{foreach from=$contacts item="c"}
								<tr>
									<td>
										{if $c->primary_contact}<i class="fas fa-hospital-user" data-toggle="tooltip" data-placement="top" title="Primary Contact"></i><input type="hidden" name="contact[$k][poa]" value="1">{/if}
										{if $c->poa}<i class="fas fa-balance-scale-left" data-toggle="tooltip" data-placement="top" title="Power of Attorney"></i>{/if}
									</td>
									<td>
										<a tabindex="0" role="button" class="btn" data-toggle="popover" data-trigger="focus" title="Contact Info" data-content="<strong>Phone:</strong>{$c->phone} <br><strong>Email:</strong> {$c->email}" data-html="true">{$c->first_name} {$c->last_name}</a>
									</td>
									<td>{$c->contact_type}</td>
									<td class="text-right">
										<button type="button" class="modal-webpage btn" data-toggle="modal" data-remote="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=resident_contact&amp;prospect_id={$prospect->public_id}&amp;contact_id={$c->public_id}&contact_link={$c->contact_link}" data-target="#addNewContact"><i class="fas fa-user-edit"></i></button>
										<a class="btn delete" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-user-minus"></i></a>
										<input type="hidden" class="prospect-id" value="{$prospect->public_id}">
										<input type="hidden" class="contact-id" value="{$c->public_id}">
										<input type="hidden" class="contact-link" value="{$c->contact_link}">
									</td>
								</tr>
								{/foreach}
							</tbody>

						</table>
						{$this->loadElement('addContact')}
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
									
		</form>

		<form id="fileForm" enctype="multipart/form-data">
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
						<div class="row justify-content-center">
							<div class="col">
					  			<table id="prospect-files" class="table table-striped">
					  				<thead>
					  					<tr>
					  						<th colspan="2" class="text-center">Current Files</th>
					  					</tr>
					  				</thead>
					  				<tbody id="file-table-row">
					  					{foreach from=$files item=file}
					  					<tr>
											<td>{$file->name}</td>
											<td class="text-right"><a href="{$AWS}/client_files/{$file->file_name}" target="_blank"><i class="fas fa-file fa-2x"></i></a></td>
										</tr>
					  					{/foreach}
					  				</tbody>
					  			</table>
					  		</div>
					  	</div>

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
									<input type="file" name="file" id="file" class="custom-file-input">
									<label for="file" class="custom-file-label">Choose File</label>
								</div>			  					
			  				</div>
			  				<div class="col-sm-2 col-md-1">
			  					<button type="button" id="addFile" class="btn btn-primary active-button">Upload</button>
			  				</div>
			  			</div>
			  		</div>
				</div>
			</div>		
			<!-- /files -->
		</form>
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

<script src="https://github.com/pipwerks/PDFObject/blob/master/pdfobject.min.js"></script>