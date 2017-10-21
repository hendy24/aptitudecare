{if $JQUERY_URL != ''}
<script type="text/javascript" src="{$JQUERY_URL}"></script>
{/if}
{javascript}
var ENGINE_URL = '{$ENGINE_URL}';
var SITE_URL = '{$SITE_URL}';
var CDN_URL = '{$CDN_URL}';
var SECURE_URL = '{$SECURE_URL}';
{/javascript}
{jQueryReady}
	{*
	TODO(bcohen) Revisit this.
	var secureForms = $(".secure-form");
	$.each(secureForms, function(i, elem) {
		$.get(SITE_URL + "/?page=secure_form", function(txt) {
			$(elem).append('<input type="hidden" name="_secure_form_timestamp" value="'+txt+'" />');
			$(elem).append('<input type="hidden" name="_secure_form" value="1" />');
		});
	});
	*}
{/jQueryReady}
<script type="text/javascript" src="{$CDN_ENGINE_URL}/js/helpers.js"></script>
{include file="$cms_template_dir/_functions.tpl"}
<_TITLE_>
<meta name="keywords" content="{$metatags->meta_keywords}" />
<meta name="description" content="{$metatags->meta_description}" />
<meta name="robots" content="{$metatags->meta_robots}" />
<_HEAD_>