{$location = CMS_Hospital::generate()}
{$location->load($schedule->discharge_location_id)}

<h2 class="text-center">{$location->name}</h1>

<p class="text-center">{$location->address}<br />
{$location->city}, {$location->state} {$location->zip}
<br />Phone: {$location->phone}<br />
Fax: {$location->fax}</p>