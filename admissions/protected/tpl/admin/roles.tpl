{$objFacility = CMS_Facility::generate()}
{$facilities = $objFacility->fetch()}

{jQueryReady}
$("#facility").change(function(e) {
    window.location.href = SITE_URL + '/?page=admin&action=roles&facility=' + $("option:selected", this).val();
});
{/jQueryReady}
<strong>Set roles for facility:</strong>
<select id="facility">
    <option value=""></option>
{foreach $facilities as $f}
    <option value="{$f->pubid}"{if $f->id == $facility->id} selected{/if}>{$f->name}</option>
{/foreach}
</select>

{$users = $facility->getUsers()}
{$objRole = CMS_Role::generate()}
{$roles = $objRole->fetch()}

{if count($users) == 0}
    There are no users assigned to this facility.
{else}
<form method="post" action="{$SITE_URL}">
<input type="hidden" name="page" value="admin" />
<input type="hidden" name="action" value="submitRoles" />
<input type="hidden" name="facility" value="{$facility->pubid}" />
<br />
<br />
<input type="submit" value="Save Changes &rarr;" />
<br />
<br />
<table border="1">
    
    <tr>
        <td></td>
    {foreach $roles as $r}
        <td>{$r->description}</td>
    {/foreach}
    </tr>
    {foreach $users as $u}
    <tr>
        <td>{$u->getFullName()}</td>
        {foreach $roles as $r}
            <td><input type="checkbox" value="1" name="setrole[{$u->pubid}][{$r->pubid}]"{if $u->hasRole($r, $facility)} checked{/if} /></td>
        {/foreach}
    </tr>
    {/foreach}
    
</table>
</form>
{/if}