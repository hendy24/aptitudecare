
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 text-center">
			{$this->loadElement("selectLocation")}
		</div>
	</div>
	<div class="row">
		<div class="col-lg-2 mt-1">
			<a href="{$SITE_URL}/?module=Dietary&amp;page=patient_info&amp;action=meal_tray_card&amp;location={$location->public_id}&amp;patient=all&amp;pdf=true"
				class="btn btn-primary text-white" target="_blank">Tray Cards</a>
		</div>
		<div class="col-lg-10 mt-1">
			<a href="{$SITE_URL}/?module=Dietary&amp;page=menu&amp;action=meal_order_form&amp;location={$location->public_id}&amp;pdf=true"
				class="btn btn-primary text-white" target="_blank">Meal Order Forms</a>
		</div>
	</div>

	<h1 class="text-center">Current Residents</h1>
	<input type="hidden" id="location" value="{$location->public_id}">
	<input type="hidden" name="currentUrl" value="{$current_url}">

	<div class="table-responsive">
		<table id="patient-info" class="table table-striped">
		    <thead class="thead-dark">
		      <tr>
		        <th scope="col">Room</th>
		        <th scope="col">Patient Name</th>
		        <th scope="col">&nbsp;</th>
		        <th scope="col">&nbsp;</th>
		        <th scope="col">&nbsp;</th>
		        <th scope="col">Room</th>
		        <th scope="col">Patient Name</th>
		        <th scope="col">&nbsp;</th>
		        <th scope="col">&nbsp;</th>
		      </tr>
		    </thead>
		    <tbody>
		      <tr>
		      {foreach from=$currentPatients key=k item=patient name=count}
		        <td id="{$patient->number}" value="{$patient->number}">{$patient->number}</td>

		        {if get_class($patient) == "Client"}
		        <td id="{$patient->public_id}">{$patient->last_name}, {$patient->first_name}</td>
		        <td>
		          <div class="dropdown">
		            <button class="btn text-right" type="button" id="patientDietInfoDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-tools"></i></button>
		            <div class="dropdown-menu" aria-labelledby="patientDietInfoDropdown">
		              <a href="{$SITE_URL}/?module=Dietary&amp;page=patient_info&amp;action=diet&amp;patient={$patient->public_id}" class="dropdown-item">Edit Diet</a>
		              <a href="{$SITE_URL}/?module=Dietary&amp;page=patient_info&amp;action=meal_tray_card&amp;patient={$patient->public_id}&amp;location={$location->public_id}&amp;pdf=true" target="_blank" class="dropdown-item">Current Tray Card</a>
		              <a href="{$SITE_URL}/?module=Dietary&amp;page=patient_info&amp;action=move_rooms&amp;patient={$patient->public_id}&amp;location={$location->public_id}" class="dropdown-item">Transfer Room</a>
		              <!-- <a href="{$SITE_URL}/?module=Dietary&amp;page=patient_info&amp;action=traycard_options&amp;patient={$patient->public_id}&amp;location={$location->public_id}" id="selected-traycard" class="dropdown-item">Selected Tray Card</a> -->
		            </div>
		          </div>
		        </td>
		        <td>
		          {if !$modEnabled}
		          <a href="#" class="delete-patient">
		            <button class="btn text-left" data-toggle="modal" data-target="#deleteModal" data-publicid="{$patient->public_id}" type="button">
		              <i class="fas fa-trash"></i>
		            </button>           
		          </a>
		          {/if}
		        </td>
		        {else}

		        <td>&nbsp;</td>
		        <td>&nbsp;</td>
		        <td>{if !$modEnabled}
		          <button class="btn text-left add-patient" type="button"><i class="fas fa-user-plus"></i></button>
		          <input type="hidden" class="room" value="{$patient->number}">
		          {/if}
		        </td>
		        {/if}

		      {if $smarty.foreach.count.iteration is div by 2}
		        </tr>
		        <tr>
		      {else}
		        <td>&nbsp;</td>
		      {/if}
		      {/foreach}
		      </tr>
		    </tbody>
		</table>
	</div>



</div>

{$this->loadElement('deleteModal')}
{$this->loadElement('selectDateModal')}
