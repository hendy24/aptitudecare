<h1>Cannot find the page</h1>
<br>
<br>
<p class="text-center">We're sorry!  We are unable to find the page you are looking for.</p>

{if $auth->is_admin()}
	{if isset ($message)}
	<br>
	<br>
	<h2>Error Message:</h2>
	<p class="text-center">{$message}</p>
	{/if}
{/if}