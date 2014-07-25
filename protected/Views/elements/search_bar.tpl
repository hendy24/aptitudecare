<div id="search-header">
	<div id="modules">
		Module: <select name="module" id="module">
			{foreach $modules as $m}
				<option value="{$m->public_id}" {if $module == $m->name} selected{/if}>{$m->name}</option>
			{/foreach}
		</select>
	</div>
	<h1>{$headerTitle}</h1>
	<div id="patient-search">
		Search: <input id="patient-search-box" type="text" name="patient_search" value="" placeholder="Patient Name"/>
		<input  type="submit" id="submit-patient-name" value="Go">
	</div>
</div>