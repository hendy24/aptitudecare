<h1>Edit Diet</h1>
<h2>for {$patient->fullName()}</h2>

<form action="{$SITE_URL}" method="post" id="edit-diet">
	<input type="hidden" name="page" value="saveDiet" />
	<br>
	<table class="form">
		<tr>
			<th colspan="3">Patient Info</th>
		</tr>
		<tr>
			<td><strong>First:</strong></td>
			<td><strong>Middle:</strong></td>
			<td><strong>Last:</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="first_name" value="{$patient->first_name}"></td>
			<td><input type="text" name="middle_name" value="{$patient->middle_name}"></td>
			<td><input type="text" name="last_name" value="{$patient->last_name}" size="35"></td>
		</tr>
		<tr>
			<td><strong>Birthdate:</strong></td>
			<td><strong>Height:</strong></td>
			<td><strong>Weight:</strong></td>
		</tr>
		<tr>
			<td><input type="text" name="date_of_birth" value="{display_date($patient->date_of_birth)}"></td>
			<td><input type="text" name="height" value="{$patientInfo->height}"  size="8"></td>
			<td><input type="text" name="weight" value="{$patientInfo->weight}" size="8"></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<th colspan="3">Diet Info</th>
		</tr>


		<tr>
			<td colspan="3"><strong>Restrictions:</strong></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="restrictions" value="none"> None/Regular</td>
			<td><input type="checkbox" name="restrictions" value="general_diabetic"> General Diabetic</td>
			<td><input type="checkbox" name="restrictions" value="aha_cardiac"> AHA / Cardiac</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="restrictions" value="no_added_salt"> No Added Salt</td>
			<td><input type="checkbox" name="restrictions" value="low_sodium"> Low Sodium</td>
			<td><input type="checkbox" name="restrictions" value="renal"> Renal</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="restrictions" value="renal_diabetic"> Renal Diabetic</td>
			<td><input type="checkbox" name="restrictions" value="fortified_high_calorie"> Fortified / High Calorie</td>
			<td><input type="checkbox" name="restrictions" value="other"> Other</td>
		</tr>


		<tr>
			<td colspan="3"><strong>Texture:</strong></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="texture" value="regular"> Regular</td>
			<td><input type="checkbox" name="texture" value="mechanical_soft"> Mechanical Soft</td>
			<td><input type="checkbox" name="texture" value="puree"> Puree</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="texture" value="full_liquid"> Full Liquid</td>
			<td><input type="checkbox" name="texture" value="clear_liquid"> Clear Liquid</td>
			<td><input type="checkbox" name="texture" value="tube_feeding"> Tube Feeding</td>
		</tr>
		<tr>
			<td colspan="3"><input type="checkbox" name="texture" value="other"> Other</td>
		</tr>


		<tr>
			<td colspan="3"><strong>Orders:</strong></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="orders" value="isolation"> Isolation</td>
			<td><input type="checkbox" name="orders" value="fluid_restriction"> Fluid Restriction</td>
			<td><input type="checkbox" name="orders" value="food_allergies_intolerances"> Food Allergies / Intolerances</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="orders" value="clear_liquid"> Clear Liquid</td>
			<td colspan="2"><input type="checkbox" name="orders" value="adaptive_equipment"> Adaptive Equipment</td>
		</tr>


		<tr>
			<td colspan="3"><strong>Portion Size:</strong></td>
		</tr>
		<tr>
			<td><input type="radio" name="portion_size" value="small"> Small</td>
			<td><input type="radio" name="portion_size" value="medium"> Medium</td>
			<td><input type="radio" name="portion_size" value="large"> Large</td>
		</tr>
	
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

		<tr>
			<td colspan="3"><strong>Breakfast:</strong></td>
		</tr>
		<tr>
			<td colspan="3">Cereal:</td>
		</tr>
		<tr>
			<td><input type="radio" name="cereal" value="hot"> Hot</td>
			<td><input type="radio" name="cereal" value="cold"> Cold</td>
			<td><input type="radio" name="cereal" value="none"> None</td>
		</tr>
		<tr>
			<td colspan="3">Bread:</td>
		</tr>
		<tr>
			<td><input type="radio" name="cereal" value="hot"> White</td>
			<td colspan="2"><input type="radio" name="cereal" value="cold"> Wheat</td>
		</tr>
		<tr>
			<td colspan="3">
				Eggs:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="egg_type">
					<option value="">Select...</option>
					<option value="scrambled">Scrambled</option>
					<option value="over_easy">Over Easy</option>
				</select>
			</td>
		</tr>

		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

		<tr>
			<td colspan="3"><strong>Lunch &amp; Dinner</strong></td>
		</tr>
		<tr>
			<td colspan="3">
				Avoided Vegetables:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<input type="text" name="avoided_vegetables" size="30"> 
			</td>
		</tr>
	</table>
	
</form>