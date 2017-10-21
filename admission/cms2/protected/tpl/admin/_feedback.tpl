{jQueryReady}
var why = "";
{if $feedback->wasWarning()}
{foreach $feedback->getVals("warning") as $msg}
	why += "- {$msg nofilter}\n\n";
{/foreach}
{/if}
{if $feedback->wasError()}
{foreach $feedback->getVals("error") as $msg}
	why += "- {$msg nofilter}\n\n";
{/foreach}
{/if}
{if $feedback->wasConf()}
{foreach $feedback->getVals("conf") as $msg}
	why += "- {$msg nofilter}\n\n";
{/foreach}
{/if}
if (why != "") {
	jAlert(why, "CMS :: Attention Required");
}
{/jQueryReady}
{$feedback->clear()}
