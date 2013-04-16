<?php 
$rights = $_SESSION['arrRights'];
$modNames = $_SESSION['modName'];
?>
<?php if(!empty ($user)) { ?>
<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">User  </a></li>
        </ul>
        <br />
        <form name="frmUser" id="frmUser" method="post" action="<?php echo url_for('admin/updateUser') ?>">
            <table width="487" >
                <tr>
                    <td width="125">User Name<span class="required">*</span></td>
                    <td width="350"><input type="text" id="txtUserName" name="txtUserName"  value="<?php echo $user[0]['user_name' ];?>" tabindex="1" /></td>
                </tr>
                <tr>
                    <td>Password<span class="required">*</span></td>
                    <td><input type="password" id="txtPassword" name="txtPassword"  tabindex="2" /></td>
                </tr>
                <tr>
                    <td>Confirm Password<span class="required">*</span>&nbsp;</td>
                    <td><input type="password" id="txtConfirmPassword" name="txtConfirmPassword" tabindex="3" /></td>
                </tr>
                <tr>
                    <td>User Group</td>
                    <td><select id="cmbUserGroup" name="cmbUserGroup"  tabindex="4" >
                            <option value="0">---Select User Group---</option>
                                <?php
                                if(isset($groups)) {
                                    foreach($groups as $userGroup) {
                                        $user[0]['user_group_id' ];
                                        ?>
                            <option value="<?php echo $userGroup->getUserGroupId(); ?>" <?php if($userGroup->getUserGroupId() == $user[0]['user_group_id' ] ) {
                                            echo 'selected';
                                                }?>><?php echo $userGroup->getUserGroupName(); ?></option>

                                        <?php }
                                }
                                ?>
                        </select></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <select id="cmbUserStatus" name="cmbUserStatus"  tabindex="5" >
                            <option value="Enabled" <?php if( $user[0]['status' ] == "Enabled" ) {
                                    echo 'selected';
                                        }?>>Enabled</option>
                            <option value="Disabled" <?php if( $user[0]['status' ] == "Disabled" ) {
                                    echo 'selected';
                                        }?>>Disabled</option>
                        </select></td>
                </tr>

            </table>
            <input type="hidden" id="txtUserId" name="txtUserId" value="<?php echo $user[0]['user_id' ]; ?>">
        </form>
        <table border="0">
            <tr>
                <td width="127"></td>
                <td width="350" valign="top">
                    <div class="demo">
                        <?php if(!empty($rights[$modNames['Admin']]['edit'])) { ?><button id="btnSave">Save</button><?php }?>
                        <button id="btnClear">Clear</button>
                        <button id="btnBack">Back</button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
    <?php } ?>
    
    <div id="divConfirmationDialog" title="Confirm Deletion">
   Passwords are mis matching. Please enter correct password
</div> 
<div id="divConfirmationDialog1" title="Confirm Deletion">
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
        else {
            return true;
        }
    },
    "Please select an user group."
);

    $(document).ready(function() {

        //Validate the form
        $("#frmUser").validate({

            rules: {

                txtUserName: {
                    required: true
                },

                txtPassword: {
                    required: true
                },

                txtConfirmPassword: {
                    required: true
                },

                cmbUserGroup: {
                    selectNone: true
                }


            },
            messages: {
                txtUserName: "Name is required",
                txtPassword: "Password is required",
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
            
            $('#frmUser').submit();
        });

        //When click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for(public_path('admin/showUserList/')) ?>";
        });

        //Clear the form fields
        function clearFormElements() {

            $("#frmUser").find(':input').each(function() {
                switch(this.type) {
                    case 'text':
                    case 'password':
                    case 'textarea':                   
                        $(this).val('');
                        break;
                }

            });
        }

        //When click reset buton
        $("#btnClear").click(function() {
            clearFormElements();
        });

        $(function() {
            $("button", ".demo").button();

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

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
    });
</script>