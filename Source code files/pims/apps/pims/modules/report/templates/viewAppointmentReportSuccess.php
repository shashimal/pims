<style>

</style>
<div class="report-div" style="width:920px;">
    <table width="798" border="0">
        <tr>
            <td width="160" rowspan="2" align="right" class="report-header-img" >&nbsp;</td>
            <td width="628" class="report-header-text"><strong class="report-header-text-sec1">National STD/AIDS Control Programme</strong><br />
                Central STD Clinic<br />
                Colombo 10<br /></td>
        </tr>
        <tr>
            <td height="21">&nbsp;</td>
        </tr>
    </table>
    <hr class="report-hr" />
    <h4 align="center">Appointments of <?php if($gender == "1") {
                    echo "Male Patients";
                } else {
                    echo "Female Patients";
                } ?> on <?php echo $appoinmentDate;  ?> </h4>
    <?php if(count($appoinments)>0) {?>
    <table width="800"  border="1"  class="report-table" cellpadding="0" cellspacing="0">

       
        <th width="15%" align="center" class="report-th" >Patient No</th>
        <th width="15%" align="center" class="report-th" >Name</th>
        <th width="15%" align="center" class="report-th" >Visit No</th>

        <?php    $i = 0;
                foreach ($appoinments  as $appoinment) {
                                if($i % 2 == 0)
                    {
                      $class = "tr-even";
                    }
                    else
                    {
                      $class = "tr-odd";
                    }
                    ?>
            <tr class="<?php echo $class;?>">
                <td align=""><?php echo  $appoinment['patient_no']; ?></td>
                <td align="left" ><?php echo  base64_decode($appoinment['first_name'])." ".base64_decode($appoinment['last_name']); ?></td>
                <td align=""><?php echo  $appoinment['Visit'][0]['visit_no']; ?></td>
            </tr>
                    <?php
            $i++;
                }
                ?>
        
    </table>
</div>
<?php }?>
<?php  if(count($appoinments)>0){?>
<table border="0">
            <tr>
                <td width="329"></td>
                <td width="455" valign="top">
                    <div class="demo">                       
                        <button id="btnExport-PDF" style="height:25px;">Export PDF</button>
                        <button id="btnExport-CSV" style="height:25px;">Export CSV</button>
                        <button id="btnBack">Back</button>
                    </div>
              </td>
            </tr>
        </table>
 <?php } else {
 ?>
     <span style="font-family: verdana, Arial, Helvetica, sans-serif; font-size: 12px;">No records found</span>
<?php  }?>
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
            location.href = "<?php echo url_for('report/exportAppointmentReport?appDate='.$appoinmentDate."&gender=".$gender."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportAppointmentReport?appDate='.$appoinmentDate."&gender=".$gender."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
		
		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createAppointmentReport') ?>";
        });

    });
</script>