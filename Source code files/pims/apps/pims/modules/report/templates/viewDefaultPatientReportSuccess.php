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
    <h4 align="center">View Defaulted Patients from <?php echo $fromDate . " to ". $toDate; ?></h4>
    <table width="990"  border="1" align="center"  class="report-table" >
        
        <th width="10%" align="center" class="report-th">Patient No</th>
        <th width="35%" align="center" class="report-th">Name</th>
        <th width="8%" align="center" class="report-th">Gender</th>       
        <th width="30%" align="center" class="report-th">Contact Address</th>    
        <th width="15%" align="center" class="report-th">Telephone</th>           
        <th width="15%" align="center" class="report-th">Episode No</th>
        <th width="15%" align="center" class="report-th">Appointment Date</th>
        <th width="15%" align="center" class="report-th">Delayed Days</th>

	<?php $class = "";
	    $i =0;
	?>


	<?php foreach ($defaultPatients as $objDefaultPatient)  {
	
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
            <td align="center"><?php echo $objDefaultPatient[0]; ?></td>
            <td align="center"><?php echo $objDefaultPatient[1];?></td>
            <td align="center"><?php echo $objDefaultPatient[2]; ?></td>
            <td align="center"><?php echo $objDefaultPatient[3]; ?></td>
            <td align="center"><?php echo $objDefaultPatient[4]; ?></td>
            <td align="center"><?php echo $objDefaultPatient[5]; ?></td>
            <td align="center"><?php echo $objDefaultPatient[6]; ?></td>
            <td align="center"><?php echo $objDefaultPatient[7]; ?></td>
            
        </tr>
    <?php $i++; }?>
  </table>
</div>
 <?php  if(count($defaultPatients)>0){?>
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
            location.href = "<?php echo url_for('report/exportDefaultPatients?fromDate='.$fromDate."&toDate=".$toDate."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportDefaultPatients?fromDate='.$fromDate."&toDate=".$toDate."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });

		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createDefaultPatientReport') ?>";
        });

    });
</script>
