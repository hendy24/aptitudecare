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
      window.location = SITE_URL + "/?module=Dietary&page=patient_info&action=meal_traycard&patient={$patient->public_id}&location={$location->public_id}&date=" + date + "&meal_id=" + $("#meal-id option:selected").val() + "&pdf=true";
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


<form id="traycard">
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
          <select id="meal-id" name="meal">
            <option selected="selected" value="all">All</option>
            <option value="1">Breakfast</option>
            <option value="2">Lunch</option>
            <option value="3">Dinner</option>
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

