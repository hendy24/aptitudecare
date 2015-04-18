<script>
	$(document).ready(function() {
		$("#module").change(function() {
			var module = $("#module option:selected").val();
			if (module == "Admission") {
				window.location.href = SITE_URL + "/?module=Admission";
			} else {
				window.location.href = SITE_URL + "/?module=" + module;
			}

		});
	});
</script>


<div id="action-left">
	{if count($modules) > 1}
	<span class="text-grey">Module:</span>
	<select name="module" id="module">
		{foreach $modules as $m}
			<option value="{$m->name}" {if $module == $m->name} selected{/if}>{$m->name}</option>
		{/foreach}
	</select>
	{/if}
</div>