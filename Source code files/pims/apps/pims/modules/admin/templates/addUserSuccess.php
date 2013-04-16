<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">User  </a></li>
        </ul>
        <br />
        <form name="frmUser" id="frmUser" method="post" action="<?php echo url_for('admin/saveUser') ?>/">
            <table width="487" >
                <tr>
                    <td width="125">User Name<span class="required">*</span></td>
                    <td width="350"><input type="text" id="txtUserName" name="txtUserName" tabindex="1" /></td>
                </tr>
                <tr>
                    <td>Password<span class="required">*</span></td>
                    <td><input type="password" id="txtPassword" name="txtPassword"  tabindex="2" /></td>
                </tr>
                <tr>
                    <td>Confirm Password<span class="required">*</span>&nbsp;</td>
                    <td><input type="password" id="txtConfirmPassword" name="txtConfirmPassword"  tabindex="3" /></td>
                </tr>
                <tr>
                    <td>User Group</td>
                    <td><select id="cmbUserGroup" name="cmbUserGroup" tabindex="4" >
                            <option value="0">---Select User Group---</option>
                            <?php
                            if (isset($groups)) {
                                foreach ($groups as $userGroup) {
                            ?>
                                    <option value="<?php echo $userGroup->getUserGroupId(); ?>"><?php echo $userGroup->getUserGroupName(); ?></option>

                            <?php
                                }
                            }
                            ?>
                        </select></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <select id="cmbUserStatus" name="cmbUserStatus"  tabindex="5" >
                            <option value="Enabled">Enabled</option>
                            <option value="Disabled">Disabled</option>
                        </select>			</td>
                </tr>             
            </table>

        </form>
        <table border="0">
            <tr>
                <td width="127"></td>
                <td width="350" valign="top">
                    <div class="demo">
                        <button id="btnSave">Save</button>
                        <button id="btnClear">Clear</button>
                        <button id="btnBack">Back</button>
                    </div>
                </td>
            </tr>
        </table>

    </div>
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
    "Please select an user group"
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
            $('#frmUser').submit();
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for(public_path('admin/showUserList/')) ?>";
        });
		
        $(function() {
            $("button", ".demo").button();
		
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
        //When click reset buton
        $("#btnClear").click(function() {
            document.forms[0].reset('');
        });
    });
</script>