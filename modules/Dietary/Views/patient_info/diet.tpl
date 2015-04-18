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
			<td><strong>Food Allergies:</strong></td>
			<td colspan="2" class="text-right"><input type="text" name="food_allergies" size="64" value=""></td>
		</tr>
		<tr>
			<td><strong>Food dislikes or intolerances:</strong></td>
			<td colspan="2" class="text-right"><input type="text" name="food_dislikes" size="64" value=""></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>


		<tr>
			<td colspan="3"><strong>Diet Info:</strong></td>
		</tr>
		<tr>
		{foreach from=$dietOrder item="diet" name="dietItem"}
			<td><input type="checkbox" name="diet_order" value="{$diet}">{$diet}</td>
		{if $smarty.foreach.dietItem.iteration is div by 3}
		</tr>
		<tr>
		{/if}
		{/foreach}
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>



		<tr>
			<td colspan="3"><strong>Texture:</strong></td>
		</tr>
		<tr>
			{foreach from=$texture item="diet" name="dietItem"}
				<td><input type="checkbox" name="texture" value="{$diet}">{$diet}</td>
			{if $smarty.foreach.dietItem.iteration is div by 3}
			</tr>
			<tr>
			{/if}
			{/foreach}
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>



		<tr>
			<td colspan="3"><strong>Orders:</strong></td>
		</tr>
		<tr>
			{foreach from=$orders item="diet" name="dietItem"}
				<td><input type="checkbox" name="texture" value="{$diet}">{$diet}</td>
			{if $smarty.foreach.dietItem.iteration is div by 3}
			</tr>
			<tr>
			{/if}
			{/foreach}
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>



		<tr>
			<td colspan="3"><strong>Lunch &amp; Dinner Portion Size:</strong></td>
		</tr>
		<tr>
			{foreach from=$portionSize item="diet" name="dietItem"}
				<td><input type="radio" name="portion_size" value="{$diet}"> {$diet}</td>
			{/foreach}
		</tr>
		<tr>
			<td><strong>Special Requests:</strong></td>
			<td colspan="2" class="text-right"><input type="text" name="food_allergies" size="64" value=""></td>
		</tr>
		<tr>
			<td colspan="3"><strong>Snacks</strong></td>
		</tr>
		<tr>
			<td>AM<input type="text" name="am_snack"></td>
			<td>PM<input type="text" name="pm_snack"></td>
			<td>Bedtime<input type="text" name="bedtime_snack"></td>
		</tr>

		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

		<tr>
			<td colspan="3" class="text-right"><input type="submit" value="Save"></td>
		</tr>
	</table>

</form>
