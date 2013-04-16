<div>		
    <div id="tabs" class="div-tabs">
        <div >
            <ul>
                <li><a href="#tabs-1">User Group Details</a></li>
            </ul>
            <div class="message">
                <?php if ($sf_user->hasFlash('notice')): ?>
                    <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
                <?php endif ?>

                <?php if ($sf_user->hasFlash('error')): ?>
                        <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
                <?php endif ?>
                    </div>
                    <br />
                    <table width="331" border="0" >
                        <tr>
                            <td width="164">Group ID </td>
                            <td width="151"><?php echo $group->getUserGroupId(); ?></td>
                        </tr>
                        <tr>
                            <td>Group Name </td>
                            <td><?php echo $group->getUserGroupName(); ?></td>
                        </tr>
                    </table>
                </div>
                <div id="divConfirmationDialog" title="Confirm Deletion">
                    Are you sure you want to reset the assigned rights ?
                </div>
            </div>

            <div id="tabs1" class="div-tabs">
                <div >
                    <ul>
                        <li><a href="#tabs-1">Assign Rights to User Group</a></li>
                    </ul>

                    <br />
                    <form action="<?php echo url_for('admin/saveUserGroupRights') ?>" method="post" id="frmSave" name="frmSave">
                        <table width="443" border="0" >
                            <tr>
                                <td>Module</td>
                                <td colspan="4" ><select  id="cmbModuleID" name="cmbModuleID">
                                        <option value=""><?php echo "--Select Module--"; ?></option>
                                <?php foreach ($moduleList as $module) {
 ?>
                                    <option value="<?php echo $module->getModuleId(); ?>"><?php echo $module->getName(); ?></option>
<?php } ?>
                            </select>              &nbsp;</td>
                    </tr>
                    <tr>
                        <td>Add</td>
                        <td width="20"><input type="checkbox" value="1" id="chkAdd" name="chkAdd"/></td>
                        <td width="60">&nbsp;</td>
                        <td width="60">Edit</td>
                        <td width="179"><input type="checkbox"  value="1" id="chkEdit" name="chkEdit"/></td>
                    </tr>
                    <tr>
                        <td width="102">Delete</td>
                        <td><input type="checkbox"  value="1" id="chkDelete" name="chkDelete"/></td>
                        <td>&nbsp;</td>
                        <td>View</td>
                        <td><input type="checkbox"  value="1" id="chkView" name="chkView"/></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <input type="hidden" name="id" value="<?php echo $group->getUserGroupId(); ?>">			</td>
                    </tr>

                </table>
            </form>
            <table border="0">
                <tr>
                    <td width="127"></td>
                    <td width="391" valign="top">
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

    <div id="tabs2" class="div-tabs">
        <div >
            <ul>
                <li><a href="#tabs-1">Assigned Rights</a></li>
            </ul>

            <br />
            <form action="<?php echo url_for('admin/resetUserGroupRights') ?>" method="post" id="frmDelete" name="frmDelete">
                <input type="hidden" name="txtUserGroupId" id="txtUserGroupID" value="<?php echo $group->getUserGroupId(); ?>">

                <table cellspacing="0" cellpadding="5" border="0" width="100%" class="">
                    <tbody>
                        <tr>
                            <td ><strong><?php echo "Module"; ?></strong></td>
                            <td ><strong><?php echo "Add"; ?></strong></td>
                            <td ><strong><?php echo "Edit"; ?></strong></td>
                            <td ><strong><?php echo "Delete"; ?></strong></td>
                            <td ><strong><?php echo "View"; ?></strong></td>
                            <td></td>
                        </tr>
<?php foreach ($assignedModuleRights as $right) { ?>
                                    <tr>
                                        <td class="list-table-data"><a class="td-link" href="<?php echo url_for('admin/viewUserGroupRights?mid=' . $right['SysModule']['module_id'] . "&gid=" . $group->getUserGroupId()) ?>"><?php echo $right['SysModule']['name']; ?></a></td>
                                        <td class="list-table-data"><?php
                                    if ($right['adding'] == 1) {
                                        echo "Yes";
                                    } else {
                                        echo "No";
                                    } ?></td>
                                <td class="list-table-data"><?php
                                    if ($right['editing'] == 1) {
                                        echo "Yes";
                                    } else {
                                        echo "No";
                                    } ?></td>
                                <td class="list-table-data"><?php
                                    if ($right['deleting'] == 1) {
                                        echo "Yes";
                                    } else {
                                        echo "No";
                                    } ?></td>
                                <td class="list-table-data"><?php
                                    if ($right['viewing'] == 1) {
                                        echo "Yes";
                                    } else {
                                        echo "No";
                                    }
?></td>
                                        <td><input type="hidden" name="module_id[]" id="module_id" value="<?php echo $right['SysModule']['module_id']; ?>"></td>
                                    </tr>
<?php } ?>
                            </tbody>
                        </table>

                    </form>
                    <table border="0">
                        <tr>
                            <td width="127"></td>
                            <td width="310" valign="top">
                                <div class="demo">
                                    <button id="btnReset">Reset All</button>

                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
        <script type="text/javascript">

            $(document).ready(function() {

                //Validate the form
                $("#frmSave").validate({

                    rules: {
                        cmbModuleID: { required: true},
                        chkView: { required: true }
                    },
                    messages: {
                        cmbModuleID: "<?php echo "Module is required" ?>",
                        chkView: "<?php echo "View is required" ?>"
                    }
                });

                //When click reset buton
                $("#btnSave").click(function() {
                    $('#frmSave').submit();
                });

                //When click reset buton
               // $("#btnReset").click(function() {
                  //  $('#frmDelete').submit();
                //});

                $(function() {
                    $("#tabs").tabs({ selected: 0 });
                });

                $(function() {
                    $("#tabs1").tabs({ selected: 0 });
                });

                $(function() {
                    $("#tabs2").tabs({ selected: 0 });
                });

                $(function() {
                    $("button", ".demo").button();

                });

                $("#btnClear").click(function() {
                    document.forms[0].reset('');
                });

                        //When click on the delete button
        $('#divConfirmationDialog').dialog({
            autoOpen: false,
            resizable: false,
            width: 350,
            buttons: {
                "Ok": function() {
                    $("#frmDelete").submit();
                },
                "Cancel": function() {
                    $(this).dialog("close");
                }
            }

        });

        $('#btnReset').click(function() {
            $('#divConfirmationDialog').dialog('open');
        });
                //When Click back button
                $("#btnBack").click(function() {
                    location.href = "<?php echo url_for("admin/showUserGroupList") ?>";
        });

    });
</script>