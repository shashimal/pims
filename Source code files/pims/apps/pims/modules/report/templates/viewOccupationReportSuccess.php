
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
    <h4 align="center">Occupations - Quarter <?php echo $quarter . " in ". $year; ?></h4>
    <table width="800"  border="1"  class="report-table">

        <th width="15%" class="report-th">&nbsp;</th>
        <th width="15%" align="center" class="report-th">Males</th>
        <th width="15%" align="center" class="report-th">Females</th>
        <th width="15%" align="center" class="report-th">Total</th>

        <tr class="tr-even">
            <td><strong>Unemployed</strong></td>
            <td align="center"><?php echo $occupations[0][0] !="0" ? $occupations[0][0] : ""; ?></td>
            <td align="center"><?php echo $occupations[0][1] !="0" ? $occupations[0][1] : ""; ?></td>
            <td align="center"><?php echo $occupations[0][2] !="0" ? $occupations[0][2] : ""; ?></td>
        </tr>
        <tr class="tr-odd">
            <td><strong>Student</strong></td>
            <td align="center"><?php echo $occupations[1][0] !="0" ? $occupations[1][0] : "";?></td>
            <td align="center"><?php echo $occupations[1][1] !="0" ? $occupations[1][1] : ""; ?></td>
            <td align="center"><?php echo $occupations[1][2] !="0" ? $occupations[1][2] : ""; ?></td>
        </tr>
        <tr class="tr-even">
            <td><strong>CSW </strong></td>
            <td align="center"><?php echo $occupations[2][0] !="0" ?$occupations[2][0]: ""; ?></td>
            <td align="center"><?php echo $occupations[2][1] !="0" ?$occupations[2][1]: "";  ?></td>
            <td align="center"><?php echo $occupations[2][2] !="0" ?$occupations[2][2]: "";  ?></td>
        </tr>
        <tr class="tr-odd">
            <td><strong>Retired</strong></td>
            <td align="center"><?php echo $occupations[3][0] != "0" ? $occupations[3][0] :  ""; ?></td>
            <td align="center"><?php echo $occupations[3][1] != "0" ?$occupations[3][1] :"";?></td>
            <td align="center"><?php echo $occupations[3][2] !="0" ?$occupations[3][2] :""?></td>
        </tr>
        <tr class="tr-even">
            <td><strong>Other </strong></td>
            <td align="center"><?php echo $occupations[4][0] != "0" ?$occupations[4][0] : ""; ?></td>
            <td align="center"><?php echo $occupations[4][1] !="0" ? $occupations[4][1] : "" ;?></td>
            <td align="center"><?php echo $occupations[4][2] != "0" ? $occupations[4][2] : "";?></td>
        </tr>
        <tr class="tr-odd">
            <td><strong>Not Known </strong></td>
            <td align="center"><?php echo $occupations[5][0] != "0" ?$occupations[5][0] : ""; ?></td>
            <td align="center"><?php echo $occupations[5][1] != "0" ?$occupations[5][1] : "";?></td>
            <td align="center"><?php echo $occupations[5][2] != "0" ?$occupations[5][2] : ""; ?></td>
        </tr>
        <tr class="tr-even">
            <td><strong>Total</strong></td>
            <td align="center"><strong><?php echo $totalMales !="0" ?$totalMales : "";?></strong></td>
            <td align="center"><strong><?php echo $totalFemales !="0" ?$totalFemales : "";?></strong></td>
            <td align="center"><strong><?php echo $total !="0" ? $total : "";?></strong></td>
        </tr>
    </table>

</div>

<?php  if(count($occupations)>0){?>
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
            location.href = "<?php echo url_for('report/exportOccupationReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportOccupationReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
		
		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createOccupationReport') ?>";
        });

    });
</script>

