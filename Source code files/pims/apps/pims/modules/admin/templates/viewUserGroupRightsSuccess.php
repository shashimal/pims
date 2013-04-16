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
            <td width="151"><?php echo $groupRights[0]['user_group_id'];?></td>
          </tr>
		  <tr>
            <td>Group Name </td>
            <td><?php echo $groupRights[0]['user_group_name'];?></td>
          </tr>
        </table>
             
  </div>
</div>
<div id="tabs1" class="div-tabs">
  <div >
        <ul>
            <li><a href="#tabs-1">Assign Rights to User Group</a></li>
        </ul>
		
		<br />
		 <form action="<?php echo url_for('admin/updateUserGroupRights')?>" method="post" id="frmSave" name="frmSave">
		 <input type="hidden" name="id" value="<?php echo $groupRights[0]['user_group_id'];?>">
		  <input type="hidden" value="<?php echo $groupRights[0]['user_group_id'];?>" name="txtUserGroupID" id="txtUserGroupID"/>
                <input type="hidden" value="<?php echo $groupRights[0]['Rights'][0] ['module_id'];?>" name="txtModId" id="txtModId"/>
        <table width="443" border="0" >
          <tr>
            <td>Module</td>
            <td colspan="4" ><?php echo $groupRights[0]['Rights'][0] ['SysModule']['name']?></td>
          </tr>
		  <tr>
            <td>Add</td>
            <td width="20"><input type="checkbox" class="formCheckboxWide" value="1" id="chkAdd" name="chkAdd"  <?php echo $groupRights[0]['Rights'][0] ['adding'] == 1 ? 'checked' : ''?>/></td>
            <td width="60">&nbsp;</td>
            <td width="60">Edit</td>
            <td width="179"><input type="checkbox" class="formCheckboxWide" value="1" id="chkEdit" name="chkEdit" <?php echo $groupRights[0]['Rights'][0] ['editing'] == 1 ? 'checked' : ''?> /></td>
          </tr>
		  <tr>
		    <td width="102">Delete</td>
		    <td> <input type="checkbox" class="formCheckboxWide" value="1" id="chkDelete" name="chkDelete" <?php echo $groupRights[0]['Rights'][0] ['deleting'] == 1 ? 'checked' : ''?> /></td>
		    <td>&nbsp;</td>
		    <td>View</td>
		    <td> <input type="checkbox" class="formCheckboxWide" value="1" id="chkView" name="chkView"  <?php echo $groupRights[0]['Rights'][0] ['viewing'] == 1 ? 'checked' : ''?> /></td>
          </tr>
		  <tr>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
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
<script type="text/javascript">

    $(document).ready(function() {

        //Validate the form
        $("#frmSave").validate({

            rules: {
                cmbModuleID: { required: true},
                chkView: { required: true }
            },
            messages: {
                cmbModuleID: "<?php echo "Module is required"?>",
                chkView: "<?php echo "View is required"?>"
            }
        });

        //When click reset buton
        $("#btnSave").click(function() {
            $('#frmSave').submit();
        });

        //When click reset buton
        $("#btnClear").click(function() {
            $('#frmDelete').submit();
        });

		 $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
		
		$(function() {
            $("#tabs1").tabs({ selected: 0 });
        });
			
		$(function() {
			$("button", ".demo").button();	
		
		});
			
        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for("admin/showUserGroupList")?>";
        });

    });
</script>