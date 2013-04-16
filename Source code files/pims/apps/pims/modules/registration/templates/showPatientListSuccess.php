<?php 
$rights = $_SESSION['arrRights'];
$modNames = $_SESSION['modName'];
?>
<div class="lsit-heading">Patient Registration</div>
<table border="0">
    <tr>
        <td width="185">
            <div class="demo">
                 <?php if(!empty($rights[$modNames['Registration']]['add'])) { ?><button id="btnAddNewPatient">Add New</button><?php }?>
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
<div id="divConfirmationDialog" title="Confirm Deletion">
    Are you sure you want to delete this record/s ?
</div>
<div >
    <form name="standardView" id="standardView" method="post" action="<?php echo url_for('registration/deletePatientInformation') ?>">
        <table id="patients" class="display" border="0"cellpadding="0" cellspacing="0" >
            <thead>
                <tr>
                    <th align="left" style="font-size: 20px;"><input type="checkbox" class="checkbox" name="allCheck" value="" id="allCheck" /></th>
                    <th width="20%" class="list-table-head">Patient No</th>
                    <th width="20%" class="list-table-head">First Name</th>
                    <th width="20%" class="list-table-head">Last Name</th>
                    <th width="25%" class="list-table-head">Gender</th>
                     <th width="25%" class="list-table-head">Occupation</th>
                    <th width="25%" class="list-table-head">Registered Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($patients)) {
                    foreach($patients as $patient) { ?>
                <tr class="gradeA">
                    <td class="list-table-data"><input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo $patient->getPatientNo();?>' /></td>
                    <td class="list-table-data"><a class="td-link" href="<?php echo url_for('registration/showPatientDetails?id='.$patient->getPatientNo())?>"><?php echo  $patient->getPatientNo();?></a></td>
                    <td class="list-table-data"><?php echo base64_decode($patient->getFirstName());?></td>
                    <td class="list-table-data"><?php echo base64_decode($patient->getLastName());?></td>
                    <td class="list-table-data"><?php  if($patient->getSex() == "1" ) {
            echo "Male";
        }else {
                            echo "Female";
                        };?></td>
                        <td class="list-table-data"><?php echo$patient->getOccupation();?></td>
                    <td class="list-table-data"><?php echo $patient->getRegisteredDate();?></td>
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
        oTable = $('#patients').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 0, "asc" ]],
            "aoColumns": [
                { "sType": "numeric" },
                { "sType": "html" },
                null,
                null,
                null,
                null,
                null
            ],
            "iDisplayLength": 25,
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
            location.href = "<?php echo url_for('admin/addUser') ?>";

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

        $("#btnAddNewPatient").click(function() {
            location.href = "<?php echo url_for('registration/registerPatient') ?>";
        });

    });
</script>