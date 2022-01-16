<div class="container">   
     <div class="row">
          <div class="col-2"></div>
          <div class="col-8">
             <h1>Allergies Report</h1>
               {if $isPDF}
               <h2>{$smarty.now|date_format}</h2>
               {/if} 
          </div>
          <div class="col-2 text-right">
               {if $auth->isLoggedIn()}
               <a href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=allergies&amp;location={$location->public_id}&amp;pdf=true" target="_blank"><i class="fas fa-print fa-2x"></i></a>
               {/if}
          </div>
     </div>
     <div class="table-responsive">
          <table class="table table-striped">
               <thead class="thead-dark">
                    <tr>
                         <th>Room</th>
                         <th>Patient</th>
                         <th>Allergy</th>
                    </tr>
               </thead>
            {foreach from=$patients item=patient}
            <tr>
              <td>{$patient->number}</td>
              <td>{$patient->last_name}, {$patient->first_name}</td>
              <td>{$patient->allergy_name|default:"None"}</td>
            </tr>
            {/foreach}
          </table>
     </div>
</div>





