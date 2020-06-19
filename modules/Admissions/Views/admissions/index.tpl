<div id="prospects" class="container">
	<div class="row">
		<div class="col-sm-12 text-center">
			{$this->loadElement('selectLocation')}
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4 mt-1">
			<a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=new_prospect" class="btn btn-primary text-white">New Prospect</a>
		</div>
	</div>

	<h1>Prospects</h1>
	
	<ul class="nav justify-content-center">
		{foreach from=$status item="s"}
		<li class="nav-item">
			<a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=index&amp;filterBy={$s->description}" class="nav-link {if $s->description == $activeTab} selected{/if}">{$s->name}</a>
		</li>
		{/foreach}
	</ul>		
	<div class="table-responsive">
		<table class="table table-striped prospects">
			<thead>
				<tr>
					<th scope="col"><a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=index&amp;filterBy={$activeTab}&amp;sortBy=room" id="sort-room">Room <i class="fas fa-caret-down"></i></a></th>
					<th scope="col"><a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=index&amp;filterBy={$activeTab}&amp;sortBy=resident_name">Resident Name <i class="fas fa-caret-down"></i></a></th>
					<th scope="col"><a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=index&amp;filterBy={$activeTab}&amp;sortBy=contact">Primary Contact <i class="fas fa-caret-down"></i></a></th>
					<th scope="col"><a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=index&amp;filterBy={$activeTab}&amp;sortBy=timeframe">Estimated Admission Date <i class="fas fa-caret-down"></i></a></th>
					<th scope="col">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$prospects key="k" item="p"}
					<tr>
						<td>{$p->number}</td>
						<td>
							<a tabindex="0" role="button" class="btn" data-toggle="popover" data-trigger="focus" title="Contact Info" data-content='<strong>Phone:</strong> <a href="tel:{$p->phone}">{$p->phone}</a> <br><strong>Email:</strong> <a href="mailto:{$p->email}">{$p->email}</a>' data-html="true">{$p->last_name}, {$p->first_name}</a>
						</td>
						<td class="main-contact">
							<a tabindex="0" role="button" class="btn" data-toggle="popover" data-trigger="focus" title="Contact Info" data-content='<strong>Phone:</strong> <a href="tel:{$p->contact_phone}">{$p->contact_phone}</a> <br><strong>Email:</strong> <a href="mailto:{$p->contact_email}">{$p->contact_email}</a>' data-html="true">{$p->contact_first_name} {$p->contact_last_name}</a>
						</td>	
						<td>{$p->datetime_admit}</td>
						<td>
							<div class="dropdown">
							    <button class="btn text-right" type="button" id="prospectsInfoDropdown{$k}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-tools"></i></button>
							    <div class="dropdown-menu" aria-labelledby="prospectsInfoDropdown{$k}">
							        <a href="{$SITE_URL}/?module=Admissions&amp;page=profiles&amp;id={$p->public_id}" class="dropdown-item">Profile</a>
									<a href="{$SITE_URL}/?module=Admissions&amp;page=admissions&amp;action=convert_to_prospect&amp;id={$p->public_id}" class="dropdown-item">Convert to Current Prospect</a>
							        <a href="{$SITE_URL}/?module=Admissions&page=admissions&action=assign_room&prospect={$p->public_id}" class="dropdown-item">Assign a Room</a>
							        <a href="{$SITE_URL}/?module=Admissions&page=assessments&action=schedule&id={$p->public_id}" class="dropdown-item">Schedule Assessment</a>
							    </div>
							</div>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
</div>