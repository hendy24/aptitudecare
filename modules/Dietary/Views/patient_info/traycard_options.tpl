<style>
  .container{
    width: 75%;
    margin: 20px auto;
    text-align: left;
    font-weight: normal;
    border-collapse: collapse;
  }
</style>

<div id="page-header">
  <div id="action-left">
    {$this->loadElement("module")}
  </div>
  <div id="center-title">
    {$this->loadElement("selectLocation")}
  </div>
</div>

<div class="container">
  <form action="?module=Dietary&page=patient_info&action=traycard" method="POST">
    <label>Choose Day:</label>
    <input class="datepicker" name="date" />
    <br>
    <br>
    <label>Choose Meal:</label>
    <select name="meal">
      <option selected="selected">All</option>
      <option>Breakfast</option>
      <option>Lunch</option>
      <option>Dinner</option>
    </select>
    <br>
    <br>
    <label>Choose Patient:</label>
    <select name="patient">
      {foreach from=$currentPatients key=k item=patient name=count}

        {if get_class($patient) == "Patient"}
          <option value="{$patient->public_id}">{$patient->last_name}, {$patient->first_name}</option>
        {/if}
      {/foreach}
    </select>
    <br>
    <br>
    <input type="submit" value="Submit" />

  </form>
</div>