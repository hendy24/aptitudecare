{setTitle title="Nursing Report"}
{jQueryReady}
{if $auth->getRecord()->canEditNursing() == false || $mode != "edit"}

$("#nursing-form input, #nursing-form select, #nursing-form textarea").attr("disabled", true).css("background", "none").css("border", "none");

{/if}
{/jQueryReady}

<style type="text/css">
.form-header-row td {
	padding-top: 20px;
}
</style>
<a href="{$SITE_URL}/?page=patient&action=printNursing&patient={$patient->pubid}&mode=edit" target="_blank" class="right"><img src="{$SITE_URL}/images/print.png" /></a>
<br />
<br />
<h1 class="text-center">Pre-Admission Nursing Report<br /><span class="text-18">for {$patient->fullName()}</span></h1> 

<br />
<br />
<form name="admissions" method="post" action="{$SITE_URL}" id="nursing-form"> 
	<input type="hidden" name="page" value="patient" />
	<input type="hidden" name="action" value="submitNursing" />
	<input type="hidden" name="patient_admit" value="{$patient->pubid}" />
	{if $nursing != ''}
		{$data = get_object_vars($nursing->getRecord())}
	{/if}
	<table width="100%" cellpadding="0" border="0"> 
	<tbody>
	<tr class="form-header-row">
		<td><strong>{$patient->fullName()}</strong></td>
		<td><strong>Room #</strong>{$patient->room_number}</td>
		<td><strong>Referring Nurse:</strong> {$patient->referral_nurse_name}</td>
		<td><strong>Nurse Phone:</strong> {$patient->nursing_report_phone}</td>
	</tr>
	<tr class="form-header-row">
		<td><strong>Height</strong></td>
		<td><strong>Weight</strong></td>
		<td colspan="2" rowspan="2"></td>
	</tr>
	<tr>
		<td><input type="text" size="10" name="height" value="{$data.height}" /></td>
		<td><input type="text" size="10" name="weight" value="{$data.weight}" /></td>
	</tr>
	<tr class="form-header-row">
		<td><strong>Transportation</strong></td>
		<td><strong>Provider</strong></td>
		<td><strong>Pick-Up Time</strong></td>
	</tr>
	<tr>
		<td>
			<td>{if $patient->trans == 'wheelchair'} Wheelchair{/if} {if $patient->trans == 'stretcher'} Stretcher{/if}{if $patient->o2 == 1}&nbsp;&nbsp;<strong>Oxygen:</strong> {$patient->o2_liters} liters{/if}</td>
			<td>{$patient->trans_provider}</td>
			<td>{if $patient->datetime_pickup != ''}{$patient->datetime_pickup}{/if}</td>
		</td>
	</tr>
	<tr class="form-header-row">
		<td colspan="4"><strong>Diagnosis</strong></td>
	</tr>
	<tr>
		<td valign="top" colspan="4">
			<textarea name="diagnosis" rows="8" style="width: 100%;">{$data.diagnosis}</textarea>
		</td>
	</tr>
	<tr class="form-header-row">
		<td colspan="4"><strong>Orientation</strong></td>
	</tr>
	<tr>
		<td valign="top">
			<input type="checkbox" name="orientation_alert" value="1"{if $data.orientation_alert == 1} checked{/if} /> Alert (Person, place, time)
			<br />
			<input type="checkbox" name="orientation_confused" value="1"{if $data.orientation_confused == 1} checked{/if} /> Confused
			<br />
			<input type="checkbox" name="orientation_disoriented" value="1"{if $data.orientation_disoriented == 1} checked{/if} /> Disoriented
			<br />
			<input type="checkbox" name="orientation_forgetful" value="1"{if $data.orientation_forgetful == 1} checked{/if} /> Forgetful
			<br />
		</td>
		<td valign="top">
			<input type="checkbox" name="orientation_fall_hx" value="1"{if $data.orientation_fall_hx == 1} checked{/if} /> Fall Hx: <input type="text" name="orientation_fall_hx_detail" size="15" value="{$data.orientation_fall_hx_detail}" />
		</td>
		<td colspan="2"></td>
		</tr>
	<tr class="form-header-row">
		<td><strong>Diet</strong></td>
		<td></td>
		<td><strong>Bowel</strong></td>
		<td><strong>Bladder</strong></td>	
	</tr>
	
	<tr>
		<td valign="top">
			Type: <input type="text" name="diet_type" size="15" value="{$data.diet_type}" />
			<br />
			<input type="checkbox" name="diet_swallowing_difficulty" value="1"{if $data.diet_swallowing_difficulty == 1} checked{/if} /> Swallowing Difficulty
			<br />
			<input type="checkbox" name="diet_feeding_tube" value="1"{if $data.diet_feeding_tube == 1} checked{/if} /> Feeding tube
		</td>
		
		<td valign="top">
			<strong>Appetite:</strong> <input type="text" name="diet_appetite" size="15" value="{$data.diet_appetite}" />
			<br />
			<input type="checkbox" name="diet_feeds_self" value="1"{if $data.diet_feeds_self == 1} checked{/if} /> Feeds self
			<br />
			<input type="checkbox" name="diet_must_be_fed" value="1"{if $data.diet_must_be_fed == 1} checked{/if} /> Must be fed
			
		</td>
		
		<td valign="top">
			<input type="checkbox" name="bowel_continent" value="1"{if $data.bowel_continent == 1} checked{/if} /> Continent
			<br />
			<input type="checkbox" name="bowel_incontinent" value="1"{if $data.bowel_incontinent == 1} checked{/if} /> Incontinent
			<br />
			<input type="checkbox" name="bowel_colostomy" value="1"{if $data.bowel_colostomy == 1} checked{/if} /> Colostomy
			<br />
			Last BM <input type="text" class="date-picker" name="bowel_last_bm" size="11" value="{$data.bowel_last_bm}" />
		</td>
		
		<td valign="top">
			<input type="checkbox" name="bladder_continent" value="1"{if $data.bladder_continent == 1} checked{/if} /> Continent
			<br />
			<input type="checkbox" name="bladder_incontinent" value="1"{if $data.bladder_incontinent == 1} checked{/if} /> Incontinent
			<br />
			<input type="checkbox" name="bladder_catheter" value="1"{if $data.bladder_catheter == 1} checked{/if} /> Catheter
		</td>
	</tr>	
	
	<tr class="form-header-row">
		<td><strong>ADL: Bathing</strong></td>
		<td><strong>ADL: Dressing</strong></td>
		<td><strong>ADL: Vision</strong></td>
		<td><strong>ADL: Hearing</strong></td>	
	</tr>
	
	<tr>
		<td valign="top">
			<input type="checkbox" name="bathing_total" value="1"{if $data.bathing_total == 1} checked{/if} /> Total
			<br />
			<input type="checkbox" name="bathing_moderate_assist" value="1"{if $data.bathing_moderate_assist == 1} checked{/if} /> Moderate Assist
			<br />
			<input type="checkbox" name="bathing_minimal_assist" value="1"{if $data.bathing_minimal_assist == 1} checked{/if} /> Minimal Assist
		</td>
		<td valign="top">
			<input type="checkbox" name="dressing_total" value="1"{if $data.dressing_total == 1} checked{/if} /> Total
			<br />
			<input type="checkbox" name="dressing_moderate_assist" value="1"{if $data.dressing_moderate_assist == 1} checked{/if} /> Moderate Assist
			<br />
			<input type="checkbox" name="dressing_minimal_assist" value="1"{if $data.dressing_minimal_assist == 1} checked{/if} /> Minimal Assist
		</td>
		<td valign="top">
			<input type="checkbox" name="vision_wnl" value="1"{if $data.vision_wnl == 1} checked{/if} /> WNL
			<br />
			<input type="checkbox" name="vision_blind" value="1"{if $data.vision_blind == 1} checked{/if} /> Blind
			<br />
			<input type="checkbox" name="vision_glasses" value="1"{if $data.vision_glasses == 1} checked{/if} /> Glasses
		</td>
		<td valign="top">
			<input type="checkbox" name="hearing_wnl" value="1"{if $data.hearing_wnl == 1} checked{/if} /> WNL
			<br />
			<input type="checkbox" name="hearing_deaf" value="1"{if $data.hearing_deaf == 1} checked{/if} /> Deaf
			<br />
			<input type="checkbox" name="hearing_hearingaids" value="1"{if $data.hearing_hearingaids == 1} checked{/if} /> Hearing Aids
		</td>
		
	</tr>
	
	<tr class="form-header-row">
		<td><strong>Skilled Nursing Services Needed</strong></td>
		<td><strong>S/S Infection</strong></td>
		<td><strong>Equipment</strong></td>
		<td><strong>Wheelchair</strong></td>	
	</tr>
	<tr>
		<td valign="top">
			<input type="checkbox" name="services_pt" value="1"{if $data.services_pt == 1} checked{/if} /> Physical Therapy
			<br />
			<input type="checkbox" name="services_ot" value="1"{if $data.services_ot == 1} checked{/if} /> Occupational Therapy
			<br />
			<input type="checkbox" name="services_st" value="1"{if $data.services_st == 1} checked{/if} /> Speech Therapy
			<br />
			<input type="checkbox" name="services_nivt" value="1"{if $data.services_nivt == 1} checked{/if} /> Nursing / IV Therapy
		</td>
		
		<td valign="top">
			<input type="radio" name="ssinfection_yesno" value="1"{if $data.ssinfection_yesno == 1} checked{/if} /> Yes
			<input type="radio" name="ssinfection_yesno" value="0"{if $data.ssinfection_yesno == 0} checked{/if} /> No
			<br />
			<input type="checkbox" name="ssinfection_cough" value="1"{if $data.ssinfection_cough == 1} checked{/if} /> Cough
			<br />
			<input type="checkbox" name="ssinfection_temp" value="1"{if $data.ssinfection_temp == 1} checked{/if} /> Temp
			<input type="text" size="4" name="ssinfection_temp_detail" value="{$data.ssinfection_temp_detail}" />
			<br />
			<input type="checkbox" name="ssinfection_mrsa" value="1"{if $data.ssinfection_mrsa == 1} checked{/if} /> MRSA
			<br />
			<input type="checkbox" name="ssinfection_vre" value="1"{if $data.ssinfection_vre == 1} checked{/if} /> VRE
			<br />
			<input type="checkbox" name="ssinfection_cdiff" value="1"{if $data.ssinfection_cdiff == 1} checked{/if} /> C-Diff
			<br />
		</td>
		<td valign="top">
			<input type="checkbox" name="equipment_cane" value="1"{if $data.equipment_cane == 1} checked{/if} /> Cane
			<br />
			<input type="checkbox" name="equipment_walker" value="1"{if $data.equipment_walker == 1} checked{/if} /> Walker
			<br />
			<input type="checkbox" name="equipment_other" value="1"{if $data.equipment_other == 1} checked{/if} /> Other
			<input type="text" name="equipment_other_detail" value="{$data.eqiupment_other_detail}" size="10" />
		
		</td>
		<td valign="top">
			<input type="checkbox" name="wheelchair_standard" value="1"{if $data.wheelchair_standard == 1} checked{/if} /> Standard
			<br />
			<input type="checkbox" name="wheelchair_bariatric" value="1"{if $data.wheelchair_bariatric == 1} checked{/if} /> Bariatric
			<br />
			<input type="checkbox" name="wheelchair_reclining" value="1"{if $data.wheelchair_reclining == 1} checked{/if} /> Reclining
		
		</td>
		
	</tr>
	
	<tr class="form-header-row">
		<td><strong>Vital Signs</strong></td>
		<td><strong>Transfers</strong></td>
		<td><strong>Weight Bearing Status</strong></td>
		<td></td>	
	</tr>
	<tr>
		<td valign="top">
			Temp <input type="text" name="vital_temp" value="{$data.vital_temp}" size="8" />
			<br />
			HR <input type="text" name="vital_hr" value="{$data.vital_hr}" size="8" />
			<br />
			B/P <input type="text" name="vital_bp" value="{$data.vital_bp}" size="8" />
			<br />
			Lungs <input type="text" name="vital_lungs" value="{$data.vital_lungs}" size="8" />
			<br />
			O<sub>2</sub> Sat <input type="text" name="vital_o2sat" value="{$data.vital_o2sat}" size="8" />
		
		</td>
		
		<td valign="top">
			<input type="checkbox" name="transfers_independent" value="1"{if $data.transfers_independent == 1} checked{/if} /> Independent
			<br />
			<input type="checkbox" name="transfers_assisted1" value="1"{if $data.transfers_assisted1 == 1} checked{/if} /> Assisted x1
			<br />
			<input type="checkbox" name="transfers_assisted2" value="1"{if $data.transfers_assisted2 == 1} checked{/if} /> Assisted x2
			<br />
			<input type="checkbox" name="transfers_slideboard" value="1"{if $data.transfers_slideboard == 1} checked{/if} /> Slide Board
			<br />
			<input type="checkbox" name="transfers_hoyer" value="1"{if $data.transfers_hoyer == 1} checked{/if} /> Hoyer
		
		</td>
		
		<td valign="top">
			<input type="checkbox" name="weightbearing_wbat" value="1"{if $data.weightbearing_wbat == 1} checked{/if} /> WBAT
			<br />
			<input type="checkbox" name="weightbearing_30lbwb" value="1"{if $data.weightbearing_30lbwb == 1} checked{/if} /> 30lb WB
			<br />
			<input type="checkbox" name="weightbearing_ttwb" value="1"{if $data.weightbearing_ttwb == 1} checked{/if} /> TTWB
			<br />
			<input type="checkbox" name="weightbearing_nwb" value="1"{if $data.weightbearing_nwb == 1} checked{/if} /> NWB
			<br />
			<input type="checkbox" name="weightbearing_cpm" value="1"{if $data.weightbearing_cpm == 1} checked{/if} /> CPM
		
		</td>
		
		<td valign="top">
			<input type="checkbox" name="weightbearing_teds" value="1"{if $data.weightbearing_teds == 1} checked{/if} /> TEDS
			<br />
			<input type="checkbox" name="weightbearing_pwb" value="1"{if $data.weightbearing_pwb == 1} checked{/if} /> PWB
			<input type="text" name="weightbearing_pwb_detail" size="10" value="{$data.weightbearing_pwb_detail}" />
			<br />
			<input type="checkbox" name="weightbearing_other" value="1"{if $data.weightbearing_other == 1} checked{/if} /> Other
			<input type="text" name="weightbearing_other_detail" size="10" value="{$data.weightbearing_other_detail}" />
		
		</td>
	</tr>
	
	<tr class="form-header-row">
		<td colspan="4"><strong>Pressure Ulcers / Wounds</strong></td>
	</tr>
	<tr>
		<td colspan="2" valign="top">
			<table>
				<tr>
					<td rowspan="3" valign="top">Location</td>
					<td rowspan="3"><textarea name="ulcers_wounds_location" cols="45" rows="8">{$data.ulcers_wounds_location}</textarea></td>
				</tr>
			</table>
		</td>
		<td colspan="2" valign="top">
			<table>
				<tr>
					<td>Stage</td>
					<td><input type="text" size="30" name="ulcers_wounds_stage" value="{$data.ulcers_wounds_stage}" /></td>
				</tr>
				<tr>
					<td>Size</td>
					<td><input type="text" size="30" name="ulcers_wounds_size" value="{$data.ulcers_wounds_size}" /></td>
				</tr>
				<tr>
					<td>Treatment</td>
					<td><input type="text" size="30" name="ulcers_wounds_treatment" value="{$data.ulcers_wounds_treatment}" /></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr class="form-header-row">
		<td colspan="4"><strong>Additional Info / Notes</strong></td>
	</tr>
	<tr>
		<td colspan="2" valign="top">
			<table>
				<tr>
					<td valign="top">Accuchecks</td>
					<td><input type="text" size="30" name="accuchecks" value="{$data.accuchecks}" /></td>
				</tr>
				<tr>
					<td valign="top">INR</td>
					<td><input type="text" size="30" name="inr" value="{$data.inr}" /></td>
				</tr>
				<tr>
					<td valign="top">Allergy</td>
					<td><input type="text" size="30" name="allergy" value="{$data.allergy}" /></td>
				</tr>
				<tr class="form-header-row">
					<td><strong>Oxygen</strong></td>
				</tr>
				<tr>
					<td colspan="2">
						Liters/Min <input type="text" size="8" name="o2_litersmin" value="{$data.o2_litersmin}" />
						&nbsp;&nbsp;
						Mask <input type="text" name="o2_mask" size="8" value="{$data.o2_mask}" />
						&nbsp;&nbsp;
						NC <input type="text" name="o2_nc" size="8" value="{$data.o2_nc}" />
					</td>
				</tr>
			</table>
		</td>
		<td colspan="2" valign="top">
			<table>
				<tr>
					<td>IV</td>
					<td><input type="text" size="30" name="iv" value="{$data.iv}" /></td>
				</tr>
				<tr>
					<td>Pharmacokinetics</td>
					<td><input type="text" size="30" name="pharmacokinetics" value="{$data.pharmacokinetics}" /></td>
				</tr>
				<tr>
					<td colspan="2">
						Heplock
						<input type="text" size="10" name="heplock" value="{$data.heplock}" />
						Peripheral
						<input type="text" size="10" name="peripheral" value="{$data.peripheral}" />
						Groshong
						<input type="text" size="10" name="groshong" value="{$data.groshong}" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						Porta Cath
						<input type="text" size="10" name="portacath" value="{$data.portacath}" />
						PICC Line
						<input type="text" size="10" name="picc_line" value="{$data.picc_line}" />
						Hickman
						<input type="text" size="10" name=hickman value="{$data.hickman}" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="form-header-row">
		<td colspan="4"><strong>Additional Notes</strong></td>
	</tr>
	<tr>
		<td colspan="4">
			<textarea name="additional_notes" rows="5" style="width: 100%">{$data.additional_notes}</textarea>
		</td>
	</tr>
	<tr>
		<td colspan="4" align="right"><input type="submit" value="Save" /></td>
	</tr>	
		
	</tbody>
	</table>
</form>