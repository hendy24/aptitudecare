<style>
  .container{
    width: 75%;
    margin: 20px auto;
    text-align: left;
    font-weight: normal;
    border-collapse: collapse;
  }
</style>

<script>
  $(document).ready(function() {
    $("#traycard").submit(function(e) {
      e.preventDefault();
      var date = $("#selectedDate").val();
      window.location = SITE_URL + "/?module=Dietary&page=patient_info&action=specific_traycard&patient={$patient->public_id}&location={$location->public_id}&date=" + date + "&pdf=true";
    });
  });
</script>

<div id="page-header">
  <div id="action-left">
    {$this->loadElement("module")}
  </div>
  <div id="center-title">
    {$this->loadElement("selectLocation")}
  </div>
</div>


<form action="{$SITE_URL}/?module=Dietary&amp;page=patient_info&amp;action=specific_traycard&amp;patient={$patient->public_id}&amp;location={$location->public_id}&amp;pdf=true" id="traycard" method="POST">
  <input type="hidden" name="page" value="patient_info" />
  <input type="hidden" name="action" value="specific_traycard" />
  <input type="hidden" name="patient" value="{$patient->public_id}" />
  <input type="hidden" name="location" value="{$location->public_id}" />
  <input type="hidden" name="pdf" value="true" />
  <table class="form">
    <tr>
      <td>Patient:</td>
      <td>
        <select name="patient">
          {foreach from=$currentPatients key=k item=patients name=count}
            {if !empty ($patients->last_name)}
              <option value="{$patients->public_id}" {if $patients->public_id == $patient->public_id} selected{/if}>{$patients->last_name}, {$patients->first_name}</option>
            {/if}
          {/foreach}
        </select>
      </td>
    </tr>
    <tr>
      <td>Date:</td>
      <td><input class="datepicker" name="date" id="selectedDate" /></td>
    </tr>
    <tr>
      <td>Meal:</td>
      <td>
          <select name="meal">
            <option selected="selected">All</option>
            <option>Breakfast</option>
            <option>Lunch</option>
            <option>Dinner</option>
          </select>
      </td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td class="text-right" colspan="2"><input type="submit" value="Submit" /></td>
    </tr>
  </table>
</form>

