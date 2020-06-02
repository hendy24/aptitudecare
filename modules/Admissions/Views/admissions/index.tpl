<div class="container">
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

	<h1>{$page_title}</h1>
	<div class="table-responsive">
		<table class="table table-striped prospects">
			<thead>
				<tr>
					<th scope="col">Resident Name</th>
					<th scope="col">Phone</th>
					<th scope="col">Email</th>
					<th scope="col">Primary Contact</th>
					<th scope="col">Timeframe</th>
					<th scope="col">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$prospects item="p"}
					<tr>
						<td>{$p->last_name}, {$p->first_name}</a></td>
						<td><a href="tel:{$p->phone}">{$p->phone}</a></td>
						<td><a href="mailto:{$p->email}">{$p->email}</a></td>
						<td class="main-contact">
							<p>{$p->contact_first_name} {$p->contact_last_name}</p>
							<p class="text-8"><a href="tel:{$p->contact_phone}">{$p->contact_phone}</a></p>
							<p class="text-8"><a href="mailto:{$p->contact_email}">{$p->contact_email}</a></p>
						</td>						
						<td>
							<input type="hidden" name="resident_id" class="resident-id" value="{$p->public_id}">
							<select class="form-control timeframe" name="timeframe">
								{foreach from=$timeframe item="t"}
								<option value="{$t->id}" {if $p->timeframe == $t->id} selected{/if}>{$t->name}</option>
								{/foreach}
							</select>
						</td>
						<td>
							<div class="dropdown">
							    <button class="btn text-right" type="button" id="prospectsInfoDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-tools"></i></button>
							    <div class="dropdown-menu" aria-labelledby="prospectdInfoDropdown">
							        <a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=profile&amp;id={$p->public_id}&amp;pipeline={if $is_prospect}prospect{else}lead{/if}" class="dropdown-item">{if $is_prospect}Prospect{else}Lead{/if} Profile</a>
							        {if !$is_prospect} 
									<a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=convert_to_prospect&amp;id={$p->public_id}" class="dropdown-item">Convert to Current Prospect</a>
							        {/if}
							        {if $is_prospect}
							        <a href="{$SITE_URL}/?module=Admissions&page=assessments&action=schedule&id={$p->public_id}" class="dropdown-item">Schedule Assessment</a>
							        {/if}
							    </div>
							</div>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
</div>