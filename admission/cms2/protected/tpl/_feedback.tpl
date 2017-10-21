<div class="cms-feedback">
{if $feedback->wasWarning()}
<ul class="feedback-msg feedback-warn">
{foreach $feedback->getVals("warning") as $msg}
	<li>{$msg nofilter}</li>
{/foreach}
</ul>
<br />
{/if}
{if $feedback->wasError()}
<ul class="feedback-msg feedback-error">
{foreach $feedback->getVals("error") as $msg}
	<li>{$msg nofilter}</li>
{/foreach}
</ul>
<br />
{/if}
{if $feedback->wasConf()}
<ul class="feedback-msg feedback-conf">
{foreach $feedback->getVals("conf") as $msg}
	<li>{$msg nofilter}</li>
{/foreach}
</ul>
{/if}
{$feedback->clear()}
</div>