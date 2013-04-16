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
    <table width="800"  border="1" align="center"  class="report-table" >
        
        <th width="15%" align="center" class="report-th">Males</th>
        <th width="15%" align="center" class="report-th">Females</th>
        <th width="15%" align="center" class="report-th">Total</th>

        <tr class="tr-even">            
            <td align="center"><?php echo $arrHiv['m'] !="0" ? $arrHiv['m'] : ""; ?></td>
            <td align="center"><?php echo $arrHiv['f'] !="0" ?$arrHiv['f'] : ""; ?></td>
            <td align="center"><?php echo $arrHiv['tot'] !="0" ? $arrHiv['tot'] : ""; ?></td>
        </tr>
        
  </table>
</div>
 <?php  if(count($arrHiv)>0){?>
<table border="0" align="left">
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

        //When Click back button
        $("#btnExport-CSV").click(function() {
            location.href = "<?php echo url_for('report/exportHivPositiveReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportHivPositiveReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });

		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createHivPositiveReport') ?>";
        });

    });
</script>
