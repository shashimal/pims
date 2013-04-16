<div class="report-div">
    <table width="798" border="0">
        <tr>
            <td width="160" rowspan="2" align="right" class="report-header-img" >&nbsp;</td>
            <td width="628" class="report-header-text"><strong class="report-header-text-sec1">National STD/AIDS Control Program</strong><br />
                Central STD Clinic<br />
                Colombo 10<br /></td>
        </tr>
        <tr>
            <td height="21">&nbsp;</td>
        </tr>
    </table>
    <hr class="report-hr" />
    <h4 align="center">HIV Positive of STD Clinic Attendees - Quarter <?php echo $quarter . " in ". $year; ?></h4>
    <table width="990"  border="1" align="center"  class="report-table" >
        
        <th width="10%" align="center" class="report-th">Patient No</th>
        <th width="35%" align="center" class="report-th">Name</th>
        <th width="8%" align="center" class="report-th">Gender</th>
        <th width="30%" align="center" class="report-th">Current Address</th>
         <th width="30%" align="center" class="report-th">Permanent Address</th>
        <th width="30%" align="center" class="report-th">Contact Address</th>    
        <th width="15%" align="center" class="report-th">Telephone</th>   
        <th width="15%" align="center" class="report-th">Occupation</th>

	<?php foreach ($hivPositivePatients as $hivPositivePatient) {?>
		
        <tr class="tr-even">            
            <td align="center"><?php echo $hivPositivePatient[0]; ?></td>
            <td align="center"><?php echo $hivPositivePatient[1]. " ".$hivPositivePatient[2]; ?></td>
            <td align="center"><?php echo $hivPositivePatient[3]; ?></td>
            <td align="center"><?php echo $hivPositivePatient[4]; ?></td>
            <td align="center"><?php echo $hivPositivePatient[5]; ?></td>
            <td align="center"><?php echo $hivPositivePatient[6]; ?></td>
            <td align="center"><?php echo $hivPositivePatient[7]; ?></td>
            <td align="center"><?php echo $hivPositivePatient[8]; ?></td>
        </tr>
    <?php }?>
  </table>
</div>
 <?php  if(count($hivPositivePatients)>0){?>
<table border="0">
            <tr>
                <td width="401"></td>
                <td width="578" valign="top">
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

        //When Click back button
        $("#btnExport-CSV").click(function() {
            location.href = "<?php echo url_for('report/exportHivPositiveDetailedReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportHivPositiveDetailedReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });

		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createHivPositiveDetailedReport') ?>";
        });

    });
</script>
