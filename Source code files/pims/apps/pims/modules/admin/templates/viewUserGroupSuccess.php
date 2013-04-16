<?php 
$rights = $_SESSION['arrRights'];
$modNames = $_SESSION['modName'];
?>
<?php if(!empty($group)) {?>
<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">User Group</a></li>
        </ul>
        <br />
        <form name="frmGroup" id="frmGroup" method="post" action="<?php echo url_for('admin/updateUserGroup') ?>/">
            <input type="hidden" id="id" name="id" value="<?php echo isset($group)? $group->getUserGroupId(): "";?>" />
            <table width="550" >
                <tr>
                    <td width="125">Group Name<span class="required">*</span></td>
                    <td width="413"><input type="text" id="txtName" name="txtName" value="<?php echo isset($group)?  $group->getUserGroupName():"" ?>"  tabindex="1"/></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name="txtDescription" id="txtDescription" rows="3" cols="30"  tabindex="2"><?php echo isset($group)? $group->getDescription() : ""; ?></textarea></td>
                </tr>
              
            </table>
        </form>
		<table border="0">
			<tr>
				<td width="127"></td>
			    <td width="416" valign="top">
				<div class="demo">
					<?php if(!empty($rights[$modNames['Admin']]['edit'])) { ?><button id="btnSave">Save</button><?php }?>
					<button id="btnClear">Clear</button>
					<?php if(!empty($rights[$modNames['Admin']]['edit'])) { ?><button id="btnAssign">Assign Rights</button><?php }?>
					<button id="btnBack">Back</button>
				</div>
			  </td>
			</tr>
		</table>
    </div>
</div>
<?php }?>

<script type="text/javascript">

    $(document).ready(function() {

        //Validate the form
        $("#frmGroup").validate({
            rules: {
                txtName: { required: true }
            },
            messages: {
                txtName: "Name is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmGroup').submit();
        });

        //When click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('admin/showUserGroupList/') ?>";
        });

        //When Click back button
        $("#btnAssign").click(function() {
            location.href = "<?php echo url_for('admin/addUserGroupRights?id='.$group->getUserGroupId()); ?>";
        });

        //Clear the form fields
        function clearFormElements() {

            $("#frmGroup").find(':input').each(function() {
                switch(this.type) {
                    case 'text':
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

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });      

    });
</script>