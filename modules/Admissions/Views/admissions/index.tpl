<div class="container">
	<div class="row">
		<div class="col-sm-12 text-center">
			{$this->loadElement('selectLocation')}
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 mt-1">
			<a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=new-prospect" class="btn btn-primary text-white">New Prospect</a>
		</div>
	</div>

	<h1>Current Prospects</h1>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Name</th>
					<th scope="col">Phone</th>
					<th scope="col">Email</th>
					<th scope="col">Timeframe</th>
					<th scopte="col">Admission Date</th>
					<th scope="col">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$prospects item="p"}
					<tr>
						<td>{$p->last_name}, {$p->first_name}</td>
						<td>{$p->phone}</td>
						<td>{$p->email_address}</td>
						<td>{$p->timeframe}</td>
						<td></td>
						<td>
							<div class="dropdown">
							    <button class="btn text-right" type="button" id="prospectsInfoDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-tools"></i></button>
							    <div class="dropdown-menu" aria-labelledby="prospectdInfoDropdown">
							        <a href="{$SITE_URL}" class="dropdown-item">Edit</a>
							        <a href="{$SITE_URL}" target="_blank" class="dropdown-item"></a>
							        <a href="" class="dropdown-item"></a>
							    </div>
							</div>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
</div>