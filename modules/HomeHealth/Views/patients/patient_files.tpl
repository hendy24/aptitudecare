<h1>Patient Files<br>
	<span class="text-16">for</span><br><span class="text-20">{$patient->fullName()}</span></h1>

{foreach from=$patientFiles item=file}
	{$file->name}
{/foreach}