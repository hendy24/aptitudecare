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

<h1>Snack Report</h1>
<div class="container">
  <form action="{$SITE_URL}" method="POST">
    <input type="hidden" name="module" value="Dietary">
    <input type="hidden" name="page" value="reports">
    <input type="hidden" name="action" value="snack_report">
    <input type="hidden" name="location" value="{$location->public_id}">
    <table class="form">
      <tr>
        <td>Choose Day:</td>
        <td> <input class="datepicker" name="date" /></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" class="text-right"> <input type="submit" value="Submit" /></td>
      </tr>
    </table>
  </form>
</div>