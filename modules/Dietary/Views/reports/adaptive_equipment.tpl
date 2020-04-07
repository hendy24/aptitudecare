<div class="container">
  {if $auth->isLoggedIn()}
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <h1>Adaptive Equipment Report</h1>
      {if $isPDF}
      <h2>{$smarty.now|date_format}</h2>
      {/if}
    </div>
    <div class="col-md-2 text-right">     
      <a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=adaptive_equipment&amp;location={$location->public_id}&amp;pdf=true" target="_blank">
        <img src="{$FRAMEWORK_IMAGES}/print.png" alt="">
      </a>
    </div>
  </div>
  {/if}


  <table class="table">
  	<thead class="table-dark">
      {if !$auth->isLoggedIn()}
      <tr>
        <th colspan="3"><h2 class="text-center">Adaptive Equipment Report</h2></th>
      </tr>
      {/if}
      <tr>
    		<th>Room</th>	
    		<th>Patient</th>
    		<th>Adaptive Equipment</th>
    	</tr>
    </thead>
    <tbody>
    	{foreach from=$patients item=patient}
    	<tr>
    		<td>{$patient->number}</td>
    		<td>{$patient->fullName()}</td>
    		<td>{$patient->ae_name}</td>
    	</tr>
    	{/foreach}
    </tbody>
  </table>
</div>