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
        <li><a href="#tabs-1">Attendacne Details of <?php if($gender == "1") {
                    echo "Male Patients";
                } else {
                    echo "Female Patients";
                } ?> on <?php echo $date;  ?> </a></li>
    </ul>

    <?php if ($sf_user->hasFlash('notice')): ?>
    <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
    <?php endif ?>

    <?php if ($sf_user->hasFlash('error')): ?>
    <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
    <?php endif ?>
    <?php
    if(count($attendacneRecords) >0) {?>

    <form name="frmAppoinmentDet" id="frmAppoinmentDet" method="post" action="<?php echo url_for('registration/printAppoinmentDetails') ?>/">

        <input type="hidden" name="txtDate" value="<?php echo $date; ?>" />
        <input type="hidden" name="txtGender" value="<?php echo $gender; ?>" />
        <div id="appointment">
        <table class="normal-table" cellspacing="0" style="margin-left: 3px;margin-top: 10px; " >
            <tr>
                <th scope="col"   class="normal-list-head">Patient No</th>
                <th scope="col" class="normal-list-head">Patient Name</th>
                <th scope="col" class="normal-list-head">Address</th>
                <th scope="col" class="normal-list-head">Occupation</th>
                <th scope="col" class="normal-list-head">Registered Date</th>
            </tr>
                <?php
                foreach ($attendacneRecords  as $attendacneRecord) {
                    ?>
            <tr>
                <td class="normal-lsit-data"><?php echo  $attendacneRecord['patient_no']; ?></td>
                <td class="normal-lsit-data"><?php echo  base64_decode($attendacneRecord['first_name'])." ".base64_decode($attendacneRecord['last_name']); ?></td>
                <td class="normal-lsit-data"><?php echo   base64_decode($attendacneRecord['contact_address']) ?></td>
                <td class="normal-lsit-data"><?php echo   $attendacneRecord['occupation'] ?></td>
                <td class="normal-lsit-data"><?php echo  $attendacneRecord['registered_date'] ?></td>
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
            location.href = "<?php echo url_for('registration/exportAttendanceReport?date='.$date."&gender=".$gender."&rep=CSV&selection1=".$selection1."&selection2=".$selection2) ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('registration/exportAttendanceReport?date='.$date."&gender=".$gender."&rep=PDF&selection1=".$selection1."&selection2=".$selection2) ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
		
		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('registration/showAttendanceReport') ?>";
        });

    });
</script>