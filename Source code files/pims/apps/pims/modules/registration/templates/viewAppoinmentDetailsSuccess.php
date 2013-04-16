<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

$currentVisits = $currentVisits[0];
$visits = $currentVisits['Visit'];

?>


<div id="tabs" class="div-tabs" style="width:750px;">
    <br />


    <ul>
        <li><a href="#tabs-1">Appointments Details of <?php if($gender == "1") {
                    echo "Male Patients";
                } else {
                    echo "Female Patients";
                } ?> on <?php echo $appoinmentDate;  ?> </a></li>
    </ul>

    <?php if ($sf_user->hasFlash('notice')): ?>
    <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
    <?php endif ?>

    <?php if ($sf_user->hasFlash('error')): ?>
    <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
    <?php endif ?>
    <?php
    if(count($appoinments) >0) {?>

    <form name="frmAppoinmentDet" id="frmAppoinmentDet" method="post" action="<?php echo url_for('registration/printAppoinmentDetails') ?>/">

        <input type="hidden" name="txtDate" value="<?php echo $appoinmentDate; ?>" />
        <input type="hidden" name="txtGender" value="<?php echo $gender; ?>" />
        <div id="appointment">
        <table class="normal-table" cellspacing="0" style="margin-left: 3px;margin-top: 10px; " >
            <tr>
                <th scope="col"   class="normal-list-head">Patient No</th>
                <th scope="col" class="normal-list-head">Patient Name</th>
                <th scope="col" class="normal-list-head">Visit No</th>
            </tr>
                <?php
                foreach ($appoinments  as $appoinment) {
                    ?>
            <tr>
                <td class="normal-lsit-data"><?php echo  $appoinment['patient_no']; ?></td>
                <td class="normal-lsit-data"><?php echo  base64_decode($appoinment['first_name'])." ".base64_decode($appoinment['last_name']); ?></td>
                <td class="normal-lsit-data"><?php echo  $appoinment['Visit'][0]['visit_no']; ?></td>
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
                <td width="411" valign="top">
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
            location.href = "<?php echo url_for('registration/exportAppointmentReport?appDate='.$appoinmentDate."&gender=".$gender."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('registration/exportAppointmentReport?appDate='.$appoinmentDate."&gender=".$gender."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
		
		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('registration/showAppoinments') ?>";
        });

    });
</script>