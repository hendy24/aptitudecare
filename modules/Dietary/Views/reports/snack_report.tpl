<style>
	.container{
		width: 75%;
		margin: 20px auto;
		text-align: left;
		font-weight: normal;
		border-collapse: collapse;
	}

	.tooltiptext {
		visibility: hidden;
		width: 85px;
		background-color: black;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 5px 0;
		margin-left: 40px;
		margin-top: 10px;

		/* Position the tooltip */
		position: absolute;
		z-index: 1;
	}

	.tool-tip:hover .tooltiptext {
		visibility: visible;
	}
	
	.report_date span {
		text-align: center;
	}

	.report_date input {
		text-align: center;
	}
	
@media print{
.do-not-print {
	display: none;
}
</style>

{if !$isPDF}
{literal}
<script type="text/javascript">
var snackOn = "";
$(document).ready(function() {
    //Loop through all Labels with class 'editable'.
    $(".editable").each(function () {
        //Reference the Label.
        var label = $(this);
		
        //Add a TextBox next to the Label.
        label.after("<input type = 'text' class = 'date-picker' style = 'display:block;display:none' />");
        //Reference the TextBox.
        var textbox = $(this).next();
 
        //Set the name attribute of the TextBox.
        //textbox[0].name = this.id.replace("lbl", "txt");
        //console.log(textbox);
 
        //Assign the value of Label to TextBox.
        textbox.val(label.text());
 
        //When Label is clicked, hide Label and show TextBox.
        label.click(function () {
            $(this).hide();
            $(this).next().show();
			//$(this).next().attr("style", "display: block; margin: 0 auto; text-align: center; font-family: inherit; font-weight: 500; line-height: 1.1; color: inherit;");
            $(this).next().datepicker('show');
        });
 
        //When focus is lost from TextBox, hide TextBox and show Label.
        textbox.focusout(function () {
            $(this).hide();
			$(this).prev().html($(this).val());
            $(this).prev().show();
        });
    });
	$('.date-picker').datepicker({
		dateFormat: "M d, yy",
		minDate: new Date(),
		onSelect: function(dateText, inst) {
			//$(this).prev()[0].childNodes[0].nodeValue = dateText;
			$(this).prev().html(dateText);
			snackOn = dateText;
			console.log($(this).prev());
		},
	});
	
	$("#action-right > a").click(function(){
		if(snackOn != "")
		{
			console.log($(this).attr("href")+"&date=" + snackOn);
			window.open($(this).attr("href")+"&date=" + snackOn, '_blank');
			return false;
		} else {
			return true;
		}
	});
});
</script>
{/literal}
<div id="page-header">
  <div id="action-left">
    &nbsp;
  </div>
  <div id="center-title">
    <h1>Snack Report</h1>
  </div>
  <div id="action-right">
	<a class="" href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=snack_report&amp;location={$location->public_id}&amp;pdf2=true" target="_blank" alt="Table">
      <img src="{$FRAMEWORK_IMAGES}/print.png" alt="Table">
    </a>
	<a class="tool-tip" href="{$SITE_URL}/?module=Dietary&amp;page=reports&amp;action=snack_labels&amp;location={$location->public_id}&amp;pdf2=true" target="_blank" alt="Labels">
	  <span class="tooltiptext">5160 Labels</span>
	  <img src="{$FRAMEWORK_IMAGES}/print.png" alt="Labels">
	</a>
  </div>
</div>
{else}
<h1>Snack Report</h1>
{/if}

<h2 class="report_date"><span class="editable">{if $printDate != ""}{$printDate|date_format}{else}{$smarty.now|date_format}{/if}</span><img src="{$FRAMEWORK_IMAGES}/edit.png" class="do-not-print"></h2>

<div class="container">
  <form action="{$SITE_URL}" method="POST">
    <input type="hidden" name="module" value="Dietary">
    <input type="hidden" name="page" value="reports">
    <input type="hidden" name="action" value="snack_report">
    <input type="hidden" name="location" value="{$location->public_id}">
    <table class="table">
      <tr>
        <th>Room</th>
        <th>Patient Name</th>
        <th>Diet</th>
        <th>Allergies</th>
        <th>Texture</th>
        <th>Snack</th>
        <th>Time</th>
      </tr>
      {foreach from=$snacks item=item key=time}
        {foreach from=$item item=snack}
        <tr>
          <td>{$snack->number}</td>
          <td>{$snack->patient_name}</td>
          <td>{$snack->diet}{if $snack->diet_info_other}, {$snack->diet_info_other}{/if}</td>
          <td>{$snack->allergy}</td>
          <td>{$snack->texture}{if $snack->texture_other}, {$snack->texture_other}{/if}</td>
          <td>{$snack->name}</td>
          <td>{$snack->time}</td>
        </tr>

        {/foreach}
      {/foreach}
    </table>
  </form>
</div>
