<?php 
$rights = $_SESSION['arrRights'];
$modNames = $_SESSION['modName'];
?>
<div class="lsit-heading">Users</div>
<table border="0">
    <tr>
        <td width="185">
            <div class="demo">
                <?php if(!empty($rights[$modNames['Admin']]['add'])) { ?><button id="btnAddNew">Add New</button> <?php }?>
                <?php if(!empty($rights[$modNames['Admin']]['delete'])) { ?><button id="btnOpenDialog">Delete</button><?php }?>
            </div>
        </td>
        <td width="1028" valign="bottom">	 <?php if ($sf_user->hasFlash('notice')): ?>
            <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
            <?php endif ?>
            <?php if ($sf_user->hasFlash('error')): ?>
            <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
            <?php endif ?>
        </td>
    </tr>
</table>
<div id="divConfirmationDialog" title="Confirm Deletion">
    Are you sure you want to delete this record/s ?
</div> 
<div >
    <form name="standardView" id="standardView" method="post" action="<?php echo url_for('admin/deleteUser') ?>">
        <table id="users" class="display" border="0"cellpadding="0" cellspacing="0" >
            <thead>
                <tr>
                    <th align="left" style="font-size: 20px;"><input type="checkbox" class="checkbox" name="allCheck" value="" id="allCheck" /></th>
                    <th width="20%" class="list-table-head">Id</th>
                    <th width="20%" class="list-table-head">Name</th>
                    <th width="20%" class="list-table-head">User Group</th>
                    <th width="25%" class="list-table-head">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($users)) {
                    foreach($users as $user) { ?>
                <tr class="gradeA">
                    <td class="list-table-data"><input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo $user['user_id']?>' /></td>
                    <td class="list-table-data"><a class="td-link" href="<?php echo url_for('admin/viewUser?id='.$user['user_id'])?>"><?php echo $user['user_id']?></a></td>
                    <td class="list-table-data"><?php echo $user['user_name'];?></td>                   
                    <td class="list-table-data"><?php echo $user['UserGroup']['user_group_name'];?></td>
                    <td class="list-table-data"><?php echo $user['status'];?></td>
                </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </form>
</div>

<script type="text/javascript">

    $(document).ready(function() {

        //Fill the table
        oTable = $('#users').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 0, "asc" ]],
            "aoColumns": [
                { "sType": "numeric" },
                { "sType": "html" },
                null,
                null,
                null
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sSearch": "Search all columns:"
            }
        });

        //Filter on the column (the index) of this element
        $("thead input").keyup( function () {
            oTable.fnFilter( this.value, $("thead input").index(this) );
        } );

        //When click add button
        $("#btnAddNew").click(function() {
            location.href = "<?php echo url_for('admin/addUser') ?>";

        });

        // When click main tick box
        $("#allCheck").change(function() {
            if ($('#allCheck').attr('checked')) {
                $('.innercheckbox').attr('checked','checked');
            }else{
                $('.innercheckbox').removeAttr('checked');
            }

        });

        //When click on the delete button
        $('#divConfirmationDialog').dialog({
            autoOpen: false,
            resizable: false,
            width: 350,
            buttons: {
                "Ok": function() {
                    $("#standardView").submit();
                },
                "Cancel": function() {
                    $(this).dialog("close");
                }
            }

        });       

        $('#btnOpenDialog').click(function() {
            $('#divConfirmationDialog').dialog('open');
        });

        $(function() {
            $("button", ".demo").button();

        });

    });
</script>