<div class="container" id="leads">
	<div class="row">
		<div class="col-sm-12 text-center">
			{$this->loadElement('selectLocation')}
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 mt-1">
			<a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=new-lead" class="btn btn-primary text-white">New Lead</a>
		</div>
	</div>

	<h1>Prospect Leads</h1>
	<div class="table-responsive">
		<table class="table table-striped prospects">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Resident Name</th>
					<th scope="col">Phone</th>
					<th scope="col">Email</th>
					<th scope="col">Main Contact</th>
					<th scope="col">Timeframe</th>
					<th scope="col">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$leads item="p"}
					<tr>
						<td>{$p->last_name}, {$p->first_name}</a></td>
						<td><a href="tel:{$p->phone}">{$p->phone}</a></td>
						<td><a href="mailto:{$p->email}">{$p->email}</a></td>
						<td class="main-contact">
							<p>{$p->contact_name}</p>
							<p class="text-8"><a href="tel:{$p->contact_phone}">{$p->contact_phone}</a></p>
							<p class="text-8"><a href="mailto:{$p->contact_email}">{$p->contact_email}</a></p>
						</td>						
						<td>{$p->timeframe}</td>
						<td>
							<div class="dropdown">
							    <button class="btn text-right" type="button" id="prospectsInfoDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-tools"></i></button>
							    <div class="dropdown-menu" aria-labelledby="prospectsInfoDropdown">
							        <a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=profile&amp;id={$p->public_id}" class="dropdown-item">Lead Profile</a>
							        <a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=convert_to_prospect&amp;id={$p->public_id}" class="dropdown-item">Convert to Current Prospect</a>
							    </div>
							</div>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>

</div>