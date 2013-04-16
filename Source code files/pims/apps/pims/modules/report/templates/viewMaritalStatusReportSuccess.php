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
    <h4 align="center">Marital Status of STD Clinic Attendees - Quarter <?php echo $quarter . " in ". $year; ?></h4>
    <table width="800"  border="1"  class="report-table" >

        <th width="15%" class="report-th">&nbsp;</th>
        <th width="15%" align="center" class="report-th">Males</th>
        <th width="15%" align="center" class="report-th">Females</th>
        <th width="15%" align="center" class="report-th">Total</th>

        <tr class="tr-even">
            <td><strong>Single</strong></td>
            <td align="center"><?php echo $maritalStatuses['single'][0] !="0" ? $maritalStatuses['single'][0] : ""; ?></td>
            <td align="center"><?php echo $maritalStatuses['single'][1] !="0" ? $maritalStatuses['single'][1] : ""; ?></td>
            <td align="center"><?php echo $maritalStatuses['single'][2] !="0" ? $maritalStatuses['single'][2] : ""; ?></td>
        </tr>
        <tr class="tr-odd">
            <td><strong>Married</strong></td>
            <td align="center"><?php echo $maritalStatuses['married'][0] !="0" ? $maritalStatuses['married'][0] : "";?></td>
            <td align="center"><?php echo $maritalStatuses['married'][1] !="0" ? $maritalStatuses['married'][1] : ""; ?></td>
            <td align="center"><?php echo $maritalStatuses['married'][2] !="0" ? $maritalStatuses['married'][2] : ""; ?></td>
        </tr>
        <tr class="tr-even">
            <td><strong>Sep / Divo </strong></td>
            <td align="center"><?php echo $maritalStatuses['sep'][0] !="0" ?$maritalStatuses['sep'][0]: ""; ?></td>
            <td align="center"><?php echo $maritalStatuses['sep'][1] !="0" ?$maritalStatuses['sep'][1]: "";  ?></td>
            <td align="center"><?php echo $maritalStatuses['sep'][2] !="0" ?$maritalStatuses['sep'][2]: "";  ?></td>
        </tr>
        <tr class="tr-odd">
            <td><strong>Widowed</strong></td>
            <td align="center"><?php echo $maritalStatuses['widowed'][0] != "0" ? $maritalStatuses['widowed'][0] :  ""; ?></td>
            <td align="center"><?php echo $maritalStatuses['widowed'][1] != "0" ?$maritalStatuses['widowed'][1] :"";?></td>
            <td align="center"><?php echo $maritalStatuses['widowed'][2] !="0" ?$maritalStatuses['widowed'][2] :""?></td>
        </tr>
        <tr class="tr-even" >
            <td><strong>Living Together </strong></td>
            <td align="center"><?php echo $maritalStatuses['lt'][0] != "0" ?$maritalStatuses['lt'][0] : ""; ?></td>
            <td align="center"><?php echo $maritalStatuses['lt'][1] !="0" ? $maritalStatuses['lt'][1] : "" ;?></td>
            <td align="center"><?php echo $maritalStatuses['lt'][2] != "0" ? $maritalStatuses['lt'][2] : "";?></td>
        </tr>
        <tr class="tr-odd">
            <td><strong>Not Known </strong></td>
            <td align="center"><?php echo $maritalStatuses['non'][0] != "0" ?$maritalStatuses['non'][0] : ""; ?></td>
            <td align="center"><?php echo $maritalStatuses['non'][1] != "0" ?$maritalStatuses['non'][1] : "";?></td>
            <td align="center"><?php echo $maritalStatuses['non'][2] != "0" ?$maritalStatuses['non'][2] : ""; ?></td>
        </tr>
        <tr class="tr-even">
            <td><strong>Total</strong></td>
            <td align="center"><strong><?php echo $totalMales !="0" ?$totalMales : "";?></strong></td>
            <td align="center"><strong><?php echo $totalFemales !="0" ?$totalFemales : "";?></strong></td>
            <td align="center"><strong><?php echo $total !="0" ? $total : "";?></strong></td>
        </tr>
    </table>
</div>
 <?php  if(count($maritalStatuses)>0){?>
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

        //When Click back button
        $("#btnExport-CSV").click(function() {
            location.href = "<?php echo url_for('report/exportMaritalStatusReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportMaritalStatusReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });

		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createMaritalStatusReport') ?>";
        });

    });
</script>
