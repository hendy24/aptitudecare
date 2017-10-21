<html>
  <head>
    <title>Google Auto-Login Page</title>
    <style>
      body { font-family: Helvetica,Arial,sans-serif; }
    </style>
  </head>
  <body>
    <h1>Google Auth Bouncer</h1>
    <form id="openid_form" action="<?php echo $_REQUEST['modauthopenid_referrer'];?>" method="GET">
      <input type="hidden" name="openid_identifier" value="https://my-dev.ahcfacilities.com/xrds"/>
      <input type="hidden" name="openid.ns.ext1" value="http://openid.net/srv/ax/1.0" />
      <input type="hidden" name="openid.ext1.mode" value="fetch_request" />
      <input type="hidden" name="openid.ext1.type.email" value="http://axschema.org/contact/email" />
      <input type="hidden" name="openid.ext1.required" value="email" />
    </form>
    <script>
      function submit_form() {
	document.getElementById("openid_form").submit();
      }
    </script>
    <?php if ($_REQUEST['modauthopenid_error'] != "") { ?>
      <font style="color: red;">There was an error:</font> <b><?php echo $_REQUEST['modauthopenid_error']; ?></b>.  
      <br /><br />
      <input type="button" value="Try Again..." onClick="submit_form();">
      <br /><br />
      Here are the error definitions:
        <ul>
          <li><strong>no_idp_found</strong>:  This is returned when the there was no identity provider URL found on the identity page given by the user, or if the page could not be downloaded.  The user probably just mistyped her identity URL.</li>
          <li><strong>invalid_id_url</strong>: This is returned when the identity URL given is not syntactically valid.</li>
          <li><strong>idp_not_trusted</strong>: This is returned when the identity provider of the user is not trusted.  This will only occur if you have at least one of <strong>AuthOpenIDTrusted</strong> or <strong>AuthOpenIDDistrusted</strong> set.</li>
          <li><strong>invalid_nonce</strong>: This is a security error.  It generally means that someone is attempting a replay attack, though more innocuous reasons are possible (such as a user who doesn't have cookies enabled refreshing the page).</li>
          <li><strong>canceled</strong>: This is returned if a user cancels the authentication process.</li>
          <li><strong>unspecified</strong>: This error can occur for a number of reasons, such a bad signature of the query parameters returned from a user's identity provider.  Most likely, the user should simply be instructed to attempt again.</li>
      </ul>
    <?php } else { ?>
      Automatically sending you to Google...
      <script>
        submit_form();
      </script>
    <?php } ?>
  </body>
  </html>