<div class="container">
	{if $auth->isLoggedIn()}
	<div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <h1>Diet Census</h1>
            {if $isPDF}
            <h2>{$smarty.now|date_format}</h2>
            {/if} 
          </div>
          <div class="col-2 text-right">
               {if $auth->isLoggedIn()}
               <a href="{$pageUrl}&amp;pdf=true" target="_blank"><i class="fas fa-print fa-2x"></i></a>
               {/if}
          </div>
    </div>
    {/if}

	<input type="hidden" id="location" name="location" value="{$location->public_id}">
	<input type="hidden" id="current-url" name="current_url" value="{$current_url}">
	<div class="table-responsive">
		<table class="table table-striped">
			{if !$auth->isLoggedIn()}
			<tr>
				<td colspan=6 class="text-center"><h1>Diet Census for {$smarty.now|date_format}</h1></td>
			</tr>
			{/if}
			<thead {if isset($is_pdf)}class="thead-dark"{/if}>
				<tr>
					<th><a href="" id="room" class="order">Room</a></th>
					<th><a href="" id="patient_name" class="order">Patient Name</a></th>
					<th><a href="" id="diet_order" class="order">Diet Order</a></th>
					<th><a href="" id="allergies" class="order">Allergies</a></th>
					<th><a href="" id="texture" class="order text-left">Texture</a></th>
					<!-- <th><a href="" id="liquid_consistency" class="order">Liquid/Fluid/Orders</a></th> -->
				</tr>
			</thead>
			{foreach from=$dietCensus item=diet}
			<tr>
				<td>{$diet->room}</td>
				<td>{$diet->patient_name}</td>
				<td>{$diet->diet_order}</td>
				<td>{$diet->allergies}</td>
				<td>{$diet->texture}</td>
				<!-- <td>{$diet->liquid_fluid_order}</td> -->
			</tr>
			{/foreach}
		</table>
	</div>
</div>
