<?php 
//$rights = $_SESSION['arrRights'];
//$mod = $_SESSION['arrMod'];

?>
<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Change Password</a></li>
        </ul>
        <br />
		<div class="message">
        <?php if ($sf_user->hasFlash('notice')): ?>
        <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
        <?php endif ?>

        <?php if ($sf_user->hasFlash('error')): ?>
        <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
        <?php endif ?>
    </div>
        <form name="frmChangePassword" id="frmChangePassword" method="post" action="<?php echo url_for('auth/changePassword') ?>">
            <table width="476" >
                <?php if(isset($rights[$mod['Admin']]['edit'])) {?>
                <tr>
                    <td width="171">User Name<span class="required">*</span></td>
                    <td width="293" align="left"> <select id="cmbUserId" name="cmbUserId" >
                            <option value="0">---Select The User---</option>
                                <?php
                                if(isset($users)) {
                                    foreach($users as $user) {
                                        ?>
                            <option value="<?php echo $user['user_id']; ?>"><?php echo  $user['user_name']; ?></option>

                                        <?php }
                                }
                                ?>
                  </select>                  </td>
                </tr>
                    <?php } else {?>
                <tr>
                    <td>User Name</td>
                  <td align="left"><input  type="text" id="txtUserName" name="txtUserName" value = "<?php echo $users[0]['user_name']; ?>" readonly="readonly" tabindex="1" /></td>
                </tr>
                    <?php } ?>
                <?php if(!isset($rights[$mod['Admin']]['edit'])) {?>
                <tr>
                    <td>Old Password<span class="required">*</span></td>
                  <td align="left"><input  type="password" id="txtOldPassword" name="txtOldPassword"  tabindex="1" /></td>
                </tr>
                    <?php } ?>
                <tr>
                    <td>New Password<span class="required">*</span></td>
                    <td><input  type="password" id="txtPassword" name="txtPassword"  tabindex="1" /></td>
                </tr>
                <tr>
                    <td>Confirm Password<span class="required">*</span></td>
                  <td align="left"> <input  type="password" id="txtConfirmPassword" name="txtConfirmPassword"  tabindex="1" /></td>
                </tr>             
          </table>
<input type="hidden" id="cmbUserId" name="cmbUserId" value="<?php echo $users[0]['user_id']; ?>" />
        </form>
		<table border="0">
            <tr>
                <td width="190"></td>
                <td width="276" valign="top">
                    <div class="demo">
                        <button id="btnSave">Save</button>
                        <button id="btnClear">Clear</button>                       
                    </div>
              </td>
            </tr>
        </table>
    </div>
</div>
<div id="divConfirmationDialog" title="Change Password">
   Passwords are mis matching. Please enter correct password
</div> 
<div id="divConfirmationDialog1" title="Change Password">
   Password should be at least 4 characters
</div> 
<script type="text/javascript">

    jQuery.validator.addMethod(
    "selectNone",
    function(value, element) {
        if (element.value == "0")
        {
            return false;
        }
        else return true;
    },
    "Please select an user"
);


    $(document).ready(function() {

        //Validate the form
        $("#frmChangePassword").validate({

            rules: {

                txtUserName: {
                    required: true
                },
                txtOldPassword: {
                    required: true
                },
                txtPassword: {
                    required: true
                },
                txtConfirmPassword: {
                    required: true
                },
                cmbUserId: {
                    selectNone: true
                }

            },
            messages: {
                txtUserName: "Name is required",
                txtOldPassword:"Old password is required",
                txtPassword: "New Password is required",
                txtConfirmPassword: "Confirm password is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {

       	if ($('#txtPassword').val().length < 4)  {
       		$('#divConfirmationDialog1').dialog('open');
			return;
        }
        	
       	 if ($('#txtPassword').val() != $('#txtConfirmPassword').val()){
        		$('#divConfirmationDialog').dialog('open');
             return false;
         }
         
            $('#frmChangePassword').submit();
        });

        //When click reset buton
        $("#resetBtn").click(function() {
            document.forms[0].reset('');
        });

        function clearFormElements() {

            $("#frmChangePassword").find(':input').each(function() {

                switch(this.type) {
                    case 'text':
                    case 'password':
                    case 'textarea':
                        $(this).val('');
                        break;
                }

            });
        }
	
		 $(function() {
            $("button", ".demo").button();

        });
		
        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
        //When click reset buton
        $("#btnClear").click(function() {
            clearFormElements();
        });

        $('#divConfirmationDialog').dialog({
            autoOpen: false,
            resizable: false,
            width: 350,
            buttons: {
                "Ok": function() {
        			$(this).dialog("close");
                }
                
            }

        });     

        $('#divConfirmationDialog1').dialog({
            autoOpen: false,
            resizable: false,
            width: 350,
            buttons: {
                "Ok": function() {
        			$(this).dialog("close");
                }
                
            }

        });       
        
        
    });
</script>