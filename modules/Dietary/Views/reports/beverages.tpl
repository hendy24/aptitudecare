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
  <form action="?module=Dietary&page=reports&action=beverages_pdf&location={$location->public_id}" method="POST">
    <label>Choose Day:</label>
    <input class="datepicker" name="date" />
    <br>
    <br>
    <input type="submit" value="Submit" />

  </form>
</div>