
<div id="login" style="width:500px; border-style:solid 1px; border-color:#999999;">

    <img src="/images/nscp12.jpg" class="login-img"/>
   
        <div class="login-form">
             <form action="<?php echo url_for('auth/login') ?>" name="frmLogin" method="post" id="frmLogin">
            <table width="483">
                <tr>
                    <td width="83">Username</td>
                    <td width="388"><input type="text" size="25" id="txtUsername" name="txtUsername" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" size="25" id="txtPassword"  name="txtPassword"  /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td> <div class="demo"><button id="btnSave">Login</button>

                        </div></td>
                </tr>
                <tr>
                  <td colspan="2" align="left" style="font-size:10px"><?php //if(isset($loginFaild)) { ?>
                    <?php if ($sf_user->hasFlash('notice')): ?>
                    <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
                    <?php endif ?>

                    <?php if ($sf_user->hasFlash('error')): ?>
                    <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
                    <?php endif ?>
                    <?php //} ?>                  </td>
                </tr>
            </table>
            </form>
            
               
            </div>
      
    

</div>
<script type="text/javascript"> 

    $(document).ready(function() {

        //Validate the form
        $("#frmLogin").validate({

            rules: {

                txtUsername: {
                required: true
                },

                txtPassword: {
                    required: true
                }

            },
            messages: {
                txtUsername: "Username is required",
                txtPassword: "Password is required"

            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmLogin').submit();
        });

        //When click reset buton
        $("#resetBtn").click(function() {
            document.forms[0].reset('');
        });
        $(function() {
            $("button", ".demo").button();

        });
        $("#login").corner("round 8px").parent().css('padding', '4px').corner("round 10px");
        
        
        $(function() {
            $("button", ".demo").button();

        });
        
        //When click reset buton
        $("#btnClear").click(function() {
            clearFormElements();
        });
    });
</script>