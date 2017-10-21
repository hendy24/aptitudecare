{setTitle title="Home Health Discharges Report"}
{include file="patient/export_icons.tpl"}
<br />
<h1 class="text-center">Home Health Discharges</h1>
{include file="report/index.tpl"}

<table>
	<tr>
		<td>&nbsp;</td>
		<th>Service Type</th>
		<th>Number of Discharges</th>
		<th>Percentage of Discharges</th>
		<th>Home Health Referral Percentage</th>
	</tr>
	{foreach $data as $k => $d}
		<tr>
			<td rowspan="6" style="text-align: center; vertical-align: top; padding-top: 5px;">
				{if $view == "year"}
					<h2>{$k|date_format:"%Y"}</h2>
				{else}
					<h2>{$k|date_format:"%b %Y"}</h2>
				{/if}
			</td>
		</tr>
		

		{foreach $d as $i}
		{$obj = CMS_Schedule::generate()}
			<tr>
				<td><a href="{$SITE_URL}/?page=report&action=home_health_discharges&facility={$facility->pubid}&view={$view}&year={$year}&date_start={$k|date_format:"%Y-%m-%d"}&type=ahc_home_health">AHC Home Health</td>
				<td align="center">{$i->AhcHomeHealth}</td>			
				<td align="center">{(($i->AhcHomeHealth/$i->discharges) * 100)|number_format:2}%</td>
				<td align="center">{($i->AhcHomeHealth/($i->AhcHomeHealth + $i->OtherHomeHealth)*100)|number_format:2}%</td>
			</tr>
			<tr>
				<td><a href="{$SITE_URL}/?page=report&action=home_health_discharges&facility={$facility->pubid}&view={$view}&year={$year}&type=other_home_health">Other Home Health</td>
				<td align="center">{$i->OtherHomeHealth}</td>			
				<td align="center">{(($i->OtherHomeHealth/$i->discharges) * 100)|number_format:2}%</td>
				<td align="center">{($i->OtherHomeHealth/($i->AhcHomeHealth + $i->OtherHomeHealth)*100)|number_format:2}%</td>
			</tr>
			<tr>
				<td><a href="{$SITE_URL}/?page=report&action=home_health_discharges&facility={$facility->pubid}&view={$view}&year={$year}&type=no_service">Declined Home Health / Outpatient Therapy</td>
				<td align="center">{$i->discharges - ($i->AhcHomeHealth + $i->OtherHomeHealth)}</td>			
				<td align="center">{((($i->discharges - ($i->AhcHomeHealth + $i->OtherHomeHealth))/$i->discharges)*100)|number_format:2}%</td>
				<td align="center">&ndash;</td>
			</tr>
			<tr>
				<td style="padding-top: 8px"><strong>Total</strong></td>
				<td style="padding-top: 8px" align="center"><strong>{$i->discharges}</strong></td>
				<td align="center">&ndash;</td>
				<td align="center">&ndash;</td>
			</tr>
			{$totalDischarges[] = $i->discharges}
		{/foreach}
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	{/foreach}
	<tr>
		<td>&nbsp;</td>
		<td><strong>Total # of Discharges:</strong></td>
		<td align="center"><strong>{$totalDischarges|@array_sum}</strong></td>
	</tr>
</table>