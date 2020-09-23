  <div class="traycard-column{if $k == 2} last-table{/if}">
    <table class="table traycard{if $k == 2} last-table{/if}">
     <tablebody>
      <tr>
        <td colspan="2" class="text-center text-20 text-strong">{$item->number} &mdash; {$item->patient_name}</td>
      </tr>
      <tr>
        <td colspan="2" class="text-strong text-18 text-center">
{foreach from=$item->dietOrderIcons key=k2 item=foo}
		<img src="{$IMAGES}/iddsi/{$foo}" style="height:34px;">
{/foreach}
		{$item->meal_name}</td>
      </tr>
      {if $item->birthday}
      <tr>
        <td colspan="2" class="text-green text-center text-18">Happy Birthday!</td>
      </tr>
      {/if}
      <tr>
        <td class="text-strong tc-label">Diet Order:</td>
        <td>{$item->diet_orders}</td>
      </tr>
      {if ($item->portion_size != "Regular")}
      <tr>
        <td class="text-strong tc-label">Portion Size:</td>
        <td>{$item->portion_size}</td>
      </tr>
      {/if}
      <tr>
        <td class="text-strong tc-label">Texture:</td>
        <td>{$item->textures}</td>
      </tr>
      <tr>
        <td class="text-strong tc-label">Adaptive Equipment:</td>
        <td>{$item->adapt_equip}</td>
      </tr>
      {if !empty($item->special_reqs)}
      <tr>
        <td class="text-strong tc-label">Special Requests:</td>
        <td>{$item->special_reqs|default:"None"}</td>
      </tr>
      {/if}
      <tr>
        <td class="text-strong tc-label">Other:</td>
        <td>{$item->orders}</td>
      </tr>
      <tr>
        <td class="text-strong tc-label">Beverages:</td>
        <td>{$item->beverages|default:"None"}</td>
      </tr>
      <tr class="spacer" style="height:10px">
        {*Works fine in chrome, not in webkit2pdf*}
        {*<td colspan="2"><div style="position:absolute;width:33.3%;padding-right:.6in;display:flex;justify-content:space-between;"><img src="https://iddsi.org/wp-content/uploads/2018/08/IDDSI-Triangle-Icon-Labels-Triangles-6.jpg" style="display:inline-block;text-align:left;width:150px;"><img src="https://iddsi.org/wp-content/uploads/2018/08/IDDSI-Triangle-Icon-Labels-Triangles-6.jpg" style="display:inline-block;width:150px;"></div></td>*}
		<td colspan="2">
			<div style="position:absolute;width:33.3%;padding-right:.3in;padding-left:-.15in;">
{if !empty($item->iddsi_food)}
				<img src="{$IMAGES}/iddsi/{$item->iddsi_food}" style="display:block;height:72px;float:left;">
{/if}
{if !empty($item->iddsi_liqu)}
				<img src="{$IMAGES}/iddsi/{$item->iddsi_liqu}" style="display:block;height:72px;float:right;">
{/if}
			</div>
		</td>
      </tr> 
      <tr>
        <td colspan="2" class="text-center text-18">{$selectedDate|date_format}</td>
      </tr>
      <tr class="spacer">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr class="spacer">
        <td colspan="2" class="text-center"><img src="{$IMAGES}/logos_black_reduced/{$location->logo}" alt=""></td>
      </tr>
      <tr class="spacer">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="text-red text-italics text-strong tc-label">Allergies:</td>
        <td class="text-red text-italics text-strong tc-label">{$item->allergies}</td>
      </tr>
	  <tr>
        <td class="text-strong tc-label">Do Not Serve:</td>
        <td>{$item->dislikes|capitalize}</td>
      </tr>

      <tr>
        <td class="text-strong" colspan="2">Percent Consumed:
        <span style="padding-top:5px;font-weight:normal;">0-25 &nbsp;&nbsp; 26-50 &nbsp;&nbsp; 51-75 &nbsp;&nbsp; 76-100</span></td>
      </tr>
      {* if ($location->id == 9) *}
      <tr>
        <td class="text-strong" colspan="2">mL Intake:
        <span style="padding-top:5px; font-weight:normal;">120 mL &nbsp;&nbsp; 240 mL &nbsp;&nbsp; 360 mL &nbsp;&nbsp; 480 mL</td>
      </tr>
      {* /if *}
      {if ($location->id == 21)}
      <tr>
        <td>&nbsp;</td>
        <td class="text-center text-strong">Table: {$item->table_number}</td>
      </tr>
      {/if}
     </tablebody>
    </table>
  </div>
  
