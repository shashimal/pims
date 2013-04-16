
<div class="report-div">
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
    <h4 align="center">Reasons For Clinic Attendance - Quarter <?php echo $quarter . " in ". $year; ?></h4>
    <table width="800"  border="1"  class="report-table">

        <th width="15%" class="report-th">&nbsp;</th>
        <th width="15%" align="center" class="report-th">Males</th>
        <th width="15%" align="center" class="report-th">Females</th>
        <th width="15%" align="center" class="report-th">Total</th>

        <tr class="tr-even">
            <td><strong>Contact of Patient</strong></td>
            <td align="center"><?php echo $reason[0][0] !="" ? $reason[0][0] : ""; ?></td>
            <td align="center"><?php echo $reason[0][1] !="" ? $reason[0][1] : ""; ?></td>
            <td align="center"><?php echo $reason[0][2] !="" ? $reason[0][2] : ""; ?></td>
        </tr>
        <tr class="tr-odd">
            <td><strong>Voluntary</strong></td>
            <td align="center"><?php echo $reason[1][0] !="" ? $reason[1][0] : "";?></td>
            <td align="center"><?php echo $reason[1][1] !="" ? $reason[1][1] : ""; ?></td>
            <td align="center"><?php echo $reason[1][2] !="" ? $reason[1][2] : ""; ?></td>
        </tr>
        <tr class="tr-even">
            <td><strong>Referral from magistrate / Court </strong></td>
            <td align="center"><?php echo $reason[2][0] !="" ?$reason[2][0]: ""; ?></td>
            <td align="center"><?php echo $reason[2][1] !="" ?$reason[2][1]: "";  ?></td>
            <td align="center"><?php echo $reason[2][2] !="" ?$reason[2][2]: "";  ?></td>
        </tr>
        <tr class="tr-odd">
            <td><strong>Blood Bank</strong></td>
            <td align="center"><?php echo $reason[3][0] != "" ? $reason[3][0] :  ""; ?></td>
            <td align="center"><?php echo $reason[3][1] != "" ?$reason[3][1] :"";?></td>
            <td align="center"><?php echo $reason[3][2] !="" ?$reason[3][2] :""?></td>
        </tr>
        <tr class="tr-even">
            <td><strong>Other </strong></td>
            <td align="center"><?php echo $reason[4][0] != "" ?$reason[4][0] : ""; ?></td>
            <td align="center"><?php echo $reason[4][1] !="" ? $reason[4][1] : "" ;?></td>
            <td align="center"><?php echo $reason[4][2] != "" ?$reason[4][2] : "";?></td>
        </tr>
        
        <tr class="tr-odd" >
            <td><strong>Total</strong></td>
            <td align="center"><strong><?php echo $totalMales !="" ?$totalMales : "";?></strong></td>
            <td align="center"><strong><?php echo $totalFemales !="" ?$totalFemales : "";?></strong></td>
            <td align="center"><strong><?php echo $total !="" ? $total : "";?></strong></td>
        </tr>
    </table>

</div>

<?php  if(count($reason)>0){?>
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
            location.href = "<?php echo url_for('report/exportReasonsForAttendanceReport?year='.$year."&qtr=".$quarter."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportReasonsForAttendanceReport?year='.$year."&qtr=".$quarter."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
		
		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createReasonForAttendanceReports') ?>";
        });

    });
</script>