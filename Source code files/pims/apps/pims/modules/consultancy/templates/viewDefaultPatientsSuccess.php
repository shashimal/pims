<div id="tabs" class="div-tabs" style="width:750px;">
    <br />


    <ul>
        <li><a href="#tabs-1">Default Patients from <?php echo $fromDate. " to ". $toDate?> </li>
    </ul>

    <?php if ($sf_user->hasFlash('notice')): ?>
    <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
    <?php endif ?>

    <?php if ($sf_user->hasFlash('error')): ?>
    <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
    <?php endif ?>
    <?php
    if(count($defaultPatients) >0) {?>

    <form name="frmAppoinmentDet" id="frmAppoinmentDet" method="post" action="<?php echo url_for('registration/printAppoinmentDetails') ?>/">

        <input type="hidden" name="txtDate" value="<?php echo $appoinmentDate; ?>" />
        <input type="hidden" name="txtGender" value="<?php echo $gender; ?>" />
        <div id="appointment">
        <table class="normal-table" cellspacing="0" style="margin-left: 3px;margin-top: 10px; " >
            <tr>
                <th scope="col"   class="normal-list-head">Patient No</th>
                <th scope="col" class="normal-list-head">Patient Name</th>
                <th scope="col" class="normal-list-head">Gender</th>
                 <th scope="col" class="normal-list-head">Episode No</th>
                <th scope="col" class="normal-list-head">Visit No</th>
                 <th scope="col" class="normal-list-head">Appointment Date</th>
                  <th scope="col" class="normal-list-head">Delayed Days</th>
            </tr>
                <?php
                foreach ($defaultPatients  as $defaultPatient) {
                    ?>
            <tr>
                <td class="normal-lsit-data"><?php echo  $defaultPatient[0]; ?></td>
                <td class="normal-lsit-data"><?php echo  $defaultPatient[1]; ?></td>
                <td class="normal-lsit-data"><?php echo  $defaultPatient[2]; ?></td>
                <td class="normal-lsit-data"><?php echo  $defaultPatient[3]; ?></td>
                <td class="normal-lsit-data"><?php echo  $defaultPatient[4]; ?></td>
                <td class="normal-lsit-data"><?php echo  $defaultPatient[5]; ?></td>
                 <td class="normal-lsit-data"><?php echo  $defaultPatient[6]; ?></td>
            </tr>
                    <?php

                }
                ?>
        </table>
        </div>
    </form>
   
    <table border="0">
            <tr>
                <td width="147"></td>
                <td width="465" valign="top">
                    <div class="demo">                       
                        <button id="btnExport-PDF" style="height:25px;">Export PDF</button>
                        <button id="btnExport-CSV" style="height:25px;">Export CSV</button>
                        <button id="btnBack">Back</button>
                    </div>
              </td>
            </tr>
        </table>
        <?php } else {

        echo "No records found";

    }?>

</div>

<script type="text/javascript">
    function printpage() {
        window.print();
    }

    $(document).ready(function() {

        //Validate the form
        $("#frmInputCategory").validate({

            rules: {
                txtInputCategory: { required: true }
            },
            messages: {
                txtInputCategory: "Category name is required"
            }
        });

        // When click edit button
        $("#saveBtn").click(function() {
            $('#frmVisit').submit();
        });

        $("#btnExport-CSV").click(function() {
            location.href = "<?php echo url_for('consultancy/exportDefaultPatients?fromDate='.$fromDate."&toDate=".$toDate."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('consultancy/exportDefaultPatients?fromDate='.$fromDate."&toDate=".$toDate."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
		
		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('consultancy/traceDefaultPatients') ?>";
        });

    });
</script>