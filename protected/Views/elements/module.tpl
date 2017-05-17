<script>
	$(document).ready(function() {
		$("#module").change(function() {
			var module = $("#module option:selected").val();
			window.location.href = SITE_URL + "/?module=" + module;
		});
	});
</script>


{if count($modules) > 1}
	<span class="text-grey">Module:</span>
	<select name="module" class="btn btn-primary dropdown-toggle dropdown-toggle-split">
		{foreach $modules as $m}
			<option value="{$m->name}" {if $module == $m->name} selected{/if}>{$m->name}</option>
		{/foreach}
	</select>
	{/if}
