
<SCRIPT src="/js/MindMeister_files/core.js" type="text/javascript"></SCRIPT>
<SCRIPT src="/js/MindMeister_files/en.js" type="text/javascript"></SCRIPT>
<SCRIPT src="/js/MindMeister_files/idselector.js" type="text/javascript"></SCRIPT>
<LINK href="/js/MindMeister_files/common.css" media="screen" rel="stylesheet" type="text/css">
 <STYLE>
    * {
    margin: 0;
    padding: 0;
  }
  html {
    background-color: #717a80;
    font: 13px/160% Helvetica, Arial, Verdana, sans-serif;
    color: #082f42;
    height: 100%;
  }
  #login_wrapper {
    width: 500px;
    position: absolute;
    left: 50%;
    top: 50%;
    margin-left: -265px;
    margin-top: -160px;
  }
  .b-ie #login_wrapper,
  .b-op #login_wrapper {
    border: 1px solid #25292b;
  }
  </STYLE>
</HEAD><BODY class="b-lin b-ch b-css3 l-en">

      <DIV id="login_wrapper" class="dialog">
<DIV id="signin">




  <DIV id="customlogo">

      <IMG src="nscp1.jpg" height="63">  </DIV>
  <DIV id="logins">

    <FORM action="" id="signin_standard" method="post" name="signin_standard" onsubmit="MindMeister.ui.disableButton(&#39;sign_in_button_standard&#39;);" style="">


      <DIV class="group">
        <FIELDSET>
          <LABEL for="login">Username:</LABEL>
          <INPUT id="login" name="login" tabindex="1" type="text" value="">
        </FIELDSET>
        <FIELDSET>
          <LABEL for="password">Password:</LABEL>
          <INPUT id="password" name="password" tabindex="2" type="password" value="">



        </FIELDSET>

      </DIV>
      <P class="buttons">
        <A class="mmbutton" id="sign_in_button_standard" >
          <SPAN>Sign In</SPAN>
        </A>
      </P>
      <INPUT id="id" name="id" type="hidden">      <INPUT id="token" name="token" type="hidden">      <DIV style="position:absolute;width:1px; height:1px; z-index:-10; overflow: hidden;"><BUTTON type="submit">&nbsp;</BUTTON></DIV>
    </FORM>

    <FORM action="https://www.mindmeister.com/users/do_open_id_authentication" id="signin_openid" method="post" name="signin_openid" onsubmit="MindMeister.ui.disableButton(&#39;sign_in_button_openid&#39;);" style="display: none; ">

      <DIV class="group">
        <FIELDSET>
          <LABEL for="openid_url">OpenID:</LABEL>
          <INPUT id="openid_identifier" name="openid_identifier" style="width: 250px;" type="text">
        </FIELDSET>
        <DIV class="indent" style="line-height: 120%;">
          <INPUT class="checkbox" id="remember_me_openid" name="remember_me_openid" type="checkbox" value="1">
          <LABEL for="remember_me_openid" class="remember">Remember me on this computer</LABEL>
        </DIV>
      </DIV>
      <P class="buttons">
        <A class="mmbutton" id="sign_in_button_openid" onclick="MindMeister.ui.disableButton(&#39;sign_in_button_openid&#39;);document.getElementById(&#39;signin_openid&#39;).submit(); return false;"><SPAN>Sign In</SPAN></A>
        <A href="https://www.mindmeister.com/users/login#" class="switcher" onclick="return top.switchLogin(&#39;standard&#39;, &#39;login&#39;)">Back to normal login</A>
      </P>
      <DIV style="width:0; height:0; overflow: hidden;"><BUTTON type="submit">&nbsp;</BUTTON></DIV>
    </FORM>

      <FORM action="https://www.mindmeister.com/sso/do_gmail_login" id="signin_gmail" method="post" name="signin_gmail" onsubmit="MindMeister.ui.disableButton(&#39;sign_in_button_gmail&#39;);" style="display: none; ">

        <DIV class="group">
          <DIV class="indent">
            <IMG alt="Logo_gmail_small" src="./MindMeister_files/logo_gmail_small.gif">
          </DIV>
          <FIELDSET>
            <DIV class="indent">
              <INPUT id="gmail" name="gmail" style="width: 140px;" type="text">
              <SPAN class="postfix">@gmail.com</SPAN>
            </DIV>
            <DIV class="indent">
              <SPAN class="hint">Your Gmail address</SPAN>
            </DIV>
          </FIELDSET>
        </DIV>
        <P class="buttons">
          <A class="mmbutton" id="sign_in_button_gmail" onclick="MindMeister.ui.disableButton(&#39;sign_in_button_gmail&#39;);document.getElementById(&#39;signin_gmail&#39;).submit(); return false;"><SPAN>Sign In</SPAN></A>
          <A href="https://www.mindmeister.com/users/login#" class="switcher" onclick="return top.switchLogin(&#39;standard&#39;, &#39;login&#39;)">Back to normal login</A>
        </P>
        <DIV style="width:0; height:0; overflow: hidden;"><BUTTON type="submit">&nbsp;</BUTTON></DIV>
      </FORM>

      <FORM action="https://www.mindmeister.com/sso/start" id="signin_gapps" method="post" name="signin_gapps" onsubmit="MindMeister.ui.disableButton(&#39;sign_in_button_gapps&#39;);" style="display: none; ">

        <DIV class="group">
          <DIV class="indent">
            <IMG alt="Logo_googleapps_small" src="./MindMeister_files/logo_googleapps_small.gif" style="margin-top:10px">
          </DIV>
          <FIELDSET>
            <LABEL class="prefix">www.</LABEL>
            <INPUT id="domain" name="domain" type="text" xstyle="width:180px">
            <DIV class="indent">
              <SPAN class="hint">e.g. yourdomain.com</SPAN>
            </DIV>
          </FIELDSET>
        </DIV>
        <P class="buttons">
          <A class="mmbutton" id="sign_in_button_gapps" onclick="MindMeister.ui.disableButton(&#39;sign_in_button_gapps&#39;); _(&#39;signin_gapps&#39;).submit(); return false;">
            <SPAN>Sign In</SPAN>
          </A>
          <A href="https://www.mindmeister.com/users/login#" class="switcher" onclick="return top.switchLogin(&#39;standard&#39;, &#39;login&#39;)">Back to normal login</A>
        </P>
        <DIV style="width:0; height:0; overflow: hidden;"><BUTTON type="submit">&nbsp;</BUTTON></DIV>
      </FORM>

  </DIV>
  <UL class="dlg_footer">

      <LI class="text"></LI>

        <LI></LI>
        <LI></LI>

      <LI></LI>
    </UL>

    <SCRIPT>

      top.switchLogin = function(login_el, focus_el) {
        $('logins').childElements().each(function(el) { el.hide(); });
        $('signin_' + login_el).show();
        if (focus_el) $(focus_el).activate();
        if (login_el == 'openid' && !document.getElementById('__idselector_button'))
          MindMeister.utils.loadScript("/javascripts/idselector.js?1280608449", function() { gen_selector(); });

        return false;
      }


      top.switchLogin('standard', 'login');

    </SCRIPT>
</DIV>


</DIV>




<link rel="stylesheet" type="text/css" href="/css/main.css" />
<p><br />
    <br />
    <br />
</p>
<p><br />
    <br />

</p>
<div class="login-form">

</div>


<div id="stylized" class="myform">
    <div id="message" class="<?php echo $messageType ?>"><?php echo $message ?></div>
    <form id="form1" name="form1" method="post"
          action="<?php echo url_for('auth/login') ?>">
        <h2 class="stylized_h1">PIMS Login</h2>
        <p></p>
        <label>Username</label> <input type="text" name="txtUsername"
                                       id="txtUsername" /> <label>Password</label> <input type="password"
                                       name="txtPassword" id="" txtPassword"" />
                                       <button type="submit">Log In</button>
        <div class="spacer"></div>

    </form>
</div>