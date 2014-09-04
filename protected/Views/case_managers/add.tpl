	{include file="data/add.tpl"}

	<tr>
		<td>Healthcare Facility:</td>	
		<td>
			<input type="text" name="healthcare_facility" id="healthcare-facility-search" style="width: 250px" />
			<input type="hidden" name="healthcare_facility_id" id="healthcare-facility-id" value="" />
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="button" value="Cancel" onClick="history.go(-1);return true;"></td>
		<td><input class="right" type="submit" value="Save" /></td>
	</tr>
	</table>
</form>