<div class="container">
	<h1>Add New Patient</h1>

	<form action="{$SITE_URL}" method="post">
		<input type="hidden" name="page" value="patientInfo">
		<input type="hidden" name="action" value="saveAddPatient">
		<input type="hidden" name="location" value="{$location->id}">
		<input type="hidden" name="number" value="{$number}">
		<input type="hidden" name="currentUrl" value="{$currentUrl}">
	
		<div class="row">
			<div class="col-md-6 col-sm-12">
				<div class="form-group">
					<label for="room">Room:</label>
					<input type="text" class="form-control" value="{$number}" name="room" readonly="readonly">
				</div>			
			</div>
			<div class="col-md-6 col-sm-12">
				<div class="form-group">
					<label for="admit-date">Admit Date:</label>
					<input type="text" class="datepicker form-control" id="admit-date" name="admit_date" value="" required>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-sm-12">
				<label for="first-name">First Name:</label>
				<input type="text" id="first-name" class="form-control" name="first_name">
			</div>
			<div class="col-md-6 col-sm-12">
				<label for="last-name">Last Name:</label>
				<input type="text" id="last-name" class="form-control" name="last_name">
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-right mt-5">
				<button type="button" class="btn btn-secondary" onclick="history.go(-1)">Cancel</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</div>
	</form>

	
</div>


