<h1>Report a Problem</h1>

<form method="post" action="{$SITE_URL}">
<input type="hidden" name="page" value="reportproblem" />
<input type="hidden" name="action" value="submit" />

<table>

	<tr>
	
		<td valign="top"><strong>I'm having trouble...</strong></td>
		<td valign="top">
			<input type="radio" name="category" value="it_printing" /> Printing<br />
			<input type="radio" name="category" value="it_computer" /> With my computer hardware<br />
			<input type="radio" name="category" value="mdi" /> With MDI<br />
			<input type="radio" name="category" value="it_email" /> Sending or receiving email <br />
			<input type="radio" name="category" value="it_fax" /> Sending or receiving faxes<br />
			<input type="radio" name="category" value="webapp_admissions" /> With the admissions program<br />
			<input type="radio" name="category" value="suggestion" /> Actually, I just have a suggestion<br />
		</td>
	</tr>
	<tr>
		<td valign="top"><strong>This is what I <i>DID</i>:</strong>
		<td valign="top">
			<textarea name="what_i_did" cols="40" rows="10"></textarea>
		</td>
	</tr>
	<tr>
		<td valign="top"><strong>This is what I <i>expected to have happen</i>:</strong>
		<td valign="top">
			<textarea name="what_i_expected" cols="40" rows="10"></textarea>
		</td>
	</tr>
	<tr>
		<td valign="top"><strong>This is what <i>actually happened</i>:</strong>
		<td valign="top">
			<textarea name="what_happened" cols="40" rows="10"></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<input type="submit" value="Submit" />
		</td>
	</tr>
</table>

</form>