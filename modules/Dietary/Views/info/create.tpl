

<div id="page-header">
  <div id="action-left">
    {$this->loadElement("module")}
  </div>
  <div id="center-title">
    {$this->loadElement("selectLocation")}
  </div>
  <br>
  <br>
  <br>
  <form method="POST" action="{$SITE_URL}?module={$this->getModule()}&amp;page=info&amp;action=create&amp;location={$location->public_id}">
  	<div style="margin-bottom:10px;">
	  	<label>New menu name:</label><input type="text" name="newmenu" placeholder="Enter menu name.">
	</div>
  	<div style="margin-bottom:10px;">
	  	<label>How many days for this menu?</label><input type="number" name="numberofdays">
  	</div>
  	<br>
  	<input type="submit" value="Submit">
  </form>
</div>