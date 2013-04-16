<?php 
$totMale = 0;
$totFemale = 0;
$grandTotal = 0;
?>
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
    <h4 align="center">HIV Distribution - From <?php echo $from . " To ". $to; ?></h4>
    <table width="990"  border="1"  class="report-table" >
        
        <th width="35%" align="center" class="report-th"></th>   
        <th width="10%" align="center" class="report-th">Male</th>
        <th width="35%" align="center" class="report-th">Female</th>
        <th width="35%" align="center" class="report-th">Total</th>        

	<?php foreach ($arrYears as $key=>$arrYear) {
	    $totMale = $totMale + $arrYear['m'];
	    $totFemale = $totFemale + $arrYear['f'];
	    $grandTotal = $grandTotal + $arrYear['tot'];
	    
	?>
		
        <tr class="tr-even">            
            <td align="center"><?php echo $key; ?></td>
            <td align="center"><?php echo $arrYear['m']; ?></td>
            <td align="center"><?php echo $arrYear['f']; ?></td>
            <td align="center"><?php echo $arrYear['tot']; ?></td>
        </tr>
    <?php }?>
     <tr class="tr-even">            
            <td align="center" bgcolor="#3d7de5"><strong>Total</strong></td>
            <td align="center" bgcolor="#3d7de5"><strong><?php echo $totMale; ?></strong></td>
            <td align="center" bgcolor="#3d7de5"><strong><?php echo $totFemale; ?></strong></td>
            <td align="center" bgcolor="#3d7de5"><strong><?php echo $grandTotal; ?></strong></td>
      </tr>
  </table>
</div>
 <?php  if(count($arrYears)>0){?>
<table border="0">
            <tr>
                <td width="448"></td>
                <td width="527" valign="top">
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
            location.href = "<?php echo url_for('report/exportHivDistributionReport?cmbFromYear='.$from."&cmbToYear=".$to."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportHivDistributionReport?cmbFromYear='.$from."&cmbToYear=".$to."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });

		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createHivDistributionReport') ?>";
        });

    });
</script>
