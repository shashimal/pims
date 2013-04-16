<?php 
$rights = $_SESSION['arrRights'];
$modNames = $_SESSION['modName'];
?>
<div class="lsit-heading">Patient Category</div>
<table border="0">
    <tr>
        <td width="185">
            <div class="demo">
                <?php if(!empty($rights[$modNames['Registration']]['add'])) { ?><button id="btnAddNew">Add New</button><?php }?>
                <?php if(!empty($rights[$modNames['Registration']]['delete'])) { ?><button id="btnOpenDialog">Delete</button><?php }?>
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
<div id="divConfirmationDialog" title="Please answer this question">
    Are you sure you want to delete this record/s ?
</div> 
<div >
     <form name="standardView" id="standardView" method="post" action="<?php echo url_for('registration/deletePatientCategory') ?>">
        <table id="patientCategory" class="display" border="0"cellpadding="0" cellspacing="0" >
            <thead>
                <tr>
                    <th align="left" style="font-size: 20px;"><input type="checkbox" class="checkbox" name="allCheck" value="" id="allCheck" /></th>
                    <th width="20%" class="list-table-head">Id</th>
                    <th width="20%" class="list-table-head">Name</th>
                    <th width="25%" class="list-table-head">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($patientCategories)) {
                   foreach($patientCategories as $patientategory) { ?>
                <tr class="gradeA">
                    <td class="list-table-data"><input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo $patientategory->getCategoryId();?>'  /></td>
                    <td class="list-table-data"><a class="td-link" href="<?php echo url_for('registration/viewPatientCategory?id='.$patientategory->getCategoryId())?>"><?php echo $patientategory->getCategoryId();?></a></td>
                    <td class="list-table-data"><?php echo $patientategory->getPatientCategory();?></td>
                    <td class="list-table-data"><?php echo $patientategory->getDescription();?></td>
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
        oTable = $('#patientCategory').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 0, "asc" ]],
            "aoColumns": [
                { "sType": "numeric" },
                { "sType": "html" },
                null,
                null
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sSearch": "Search all columns:"
            }
        });

        /* Filter on the column (the index) of this element */
        $("thead input").keyup( function () {
            oTable.fnFilter( this.value, $("thead input").index(this) );
        } );

        //When click add button
        $("#btnAddNew").click(function() {
           location.href = "<?php echo url_for('registration/addPatientCategory') ?>";

        });

        // When Click Main Tick box
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

         $(function() {
                $("button", ".demo").button();

            });

        $('#btnOpenDialog').click(function() {
            $('#divConfirmationDialog').dialog('open');
        });

    });
</script>

