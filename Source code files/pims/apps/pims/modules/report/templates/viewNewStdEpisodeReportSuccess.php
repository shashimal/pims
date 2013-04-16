<style type="text/css">
<!--
.style1 {
	color: #D4D0C8;
	font-weight: bold;
}
.style2 {color: #D4D0C8}
-->
</style>

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
    <h4 align="center">New Episodes of STD Recorded From <?php echo $startDate . " to " . $endDate . " in  Quarter " . $quarter ?></h4>


    <table width="900"  border="1"  class="report-table">
        <tr>
            <td width="61" bgcolor="#0066FF">&nbsp;</td>
            <td colspan="2" align="center" bgcolor="#0066FF"><span class="style1">0-14</span></td>
            <td colspan="2" align="center" bgcolor="#0066FF"><span class="style1">15-19</span></td>
            <td colspan="2" align="center" bgcolor="#0066FF"><span class="style1">20-24</span></td>
            <td colspan="2" align="center" bgcolor="#0066FF"><span class="style1">25-29</span></td>
            <td colspan="2" align="center" bgcolor="#0066FF"><span class="style1">30-34</span></td>
            <td colspan="2" align="center" bgcolor="#0066FF"><span class="style1">35-39</span></td>
            <td colspan="2" align="center" bgcolor="#0066FF"><span class="style1">40-44</span></td>
            <td colspan="2" align="center" bgcolor="#0066FF"><span class="style1">45-49</span></td>
            <td colspan="2" align="center" bgcolor="#0066FF"><span class="style1">50+</span></td>
            <td colspan="2" align="center" bgcolor="#0066FF"><span class="style1">Total</span></td>
            <td width="92" align="center" bgcolor="#0066FF"><span class="style1">Grand Total </span></td>
        </tr>
        <tr>
            <td width="61" bgcolor="#0066FF">&nbsp;</td>
            <td width="30" bgcolor="#0066FF"><span class="style1">M</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">F</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">M</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">F</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">M</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">F</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">M</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">F</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">M</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">F</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">M</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">F</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">M</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">F</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">M</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">F</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">M</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">F</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">M</span></td>
            <td width="30" bgcolor="#0066FF"><span class="style1">F</span></td>
            <td width="92" bgcolor="#0066FF"><span class="style2"></span></td>
        </tr>
        <?php
        $a = 0;
        $arr = array();
        foreach ($repRow as $id => $row) {
            //$temp[0] = $row[$a]['m'];
            //$temp[1] = $row[$a]['f'];
            $arr [$id][0] = $arr [$id][0] + $row[$a]['m'];
            $arr [$id][1] = $arr [$id][0] + $row[$a]['f'];
            $totalMale = $row[0]['m'] + $row[1]['m'] + $row[2]['m'] + $row[3]['m'] + $row[4]['m'] + $row[5]['m'] + $row[6]['m'] + $row[7]['m'] + $row[8]['m'];
            $totalFemale = $row[0]['f'] + $row[1]['f'] + $row[2]['f'] + $row[3]['f'] + $row[4]['f'] + $row[5]['f'] + $row[6]['f'] + $row[7]['f'] + $row[8]['f'];
            $grandTotal = $totalMale + $totalFemale;
        ?>
            <tr>
                <td width="300"><?php echo $inputResult[$a]; ?></td>
                <td width="30" align="center"><?php echo $row[0]['m'] != "" ? $row[0]['m'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[0]['f'] != "" ? $row[0]['f'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[1]['m'] != "" ? $row[1]['m'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[1]['f'] != "" ? $row[1]['f'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[2]['m'] != "" ? $row[2]['m'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[2]['f'] != "" ? $row[2]['f'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[3]['m'] != "" ? $row[3]['m'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[3]['f'] != "" ? $row[3]['f'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[4]['m'] != "" ? $row[4]['m'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[4]['f'] != "" ? $row[4]['f'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[5]['m'] != "" ? $row[5]['m'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[5]['f'] != "" ? $row[5]['f'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[6]['m'] != "" ? $row[6]['m'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[6]['f'] != "" ? $row[6]['f'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[7]['m'] != "" ? $row[7]['m'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[7]['f'] != "" ? $row[7]['f'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[8]['m'] != "" ? $row[8]['m'] : ""; ?></td>
                <td width="30" align="center"><?php echo $row[8]['f'] != "" ? $row[8]['f'] : ""; ?></td>
                <td width="30" align="center"><?php echo $totalMale != "" ? $totalMale : ""; ?></td>
                <td width="30" align="center"><?php echo $totalFemale != "" ? $totalFemale : ""; ?></td>
                <td width="30" align="center"><?php echo $grandTotal != "" ? $grandTotal : ""; ?></td>
            </tr>

<?php
            $a++;
        }

        //print_r($arr);
?>
        <tr>
            <td bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
            <td align="center" bgcolor="#0066FF">&nbsp;</td>
        </tr>
        <tr>
            <td bgcolor="#0066FF"><span class="style1">Total Venereal </span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[0]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[0]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[1]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[1]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[2]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[2]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[3]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[3]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[4]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[4]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[5]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[5]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[6]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[6]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[7]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[7]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[8]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[8]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrColNetTot['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrColNetTot['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrColNetTot['cgot'];?></span></td>
        </tr>
        <tr>
            <td bgcolor="#0066FF"><span class="style1">Total Non Venereal </span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[0]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[0]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[1]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[1]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[2]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[2]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[3]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[3]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[4]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[4]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[5]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[5]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[6]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[6]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[7]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[7]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[8]['m']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVeneral[8]['f']?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVerNetTot['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVerNetTot['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrNonVerNetTot['cgot'];?></span></td>
        </tr>
        <tr>
            <td bgcolor="#0066FF"><span class="style1">Grand Total </span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[0]['m'] + $arrNonVeneral[0]['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[0]['f'] + $arrNonVeneral[0]['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[1]['m'] + $arrNonVeneral[1]['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[1]['f'] +$arrNonVeneral[1]['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[2]['m'] + $arrNonVeneral[2]['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[2]['f'] +$arrNonVeneral[2]['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[3]['m'] + $arrNonVeneral[3]['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[3]['f'] +$arrNonVeneral[3]['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[4]['m'] + $arrNonVeneral[4]['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[4]['f'] +$arrNonVeneral[4]['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[5]['m'] + $arrNonVeneral[5]['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[5]['f'] +$arrNonVeneral[5]['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[6]['m'] + $arrNonVeneral[6]['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[6]['f'] +$arrNonVeneral[6]['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[7]['m'] + $arrNonVeneral[7]['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[7]['f'] +$arrNonVeneral[7]['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[8]['m'] + $arrNonVeneral[8]['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $totCol[8]['f'] +$arrNonVeneral[8]['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrColNetTot['m']+$arrNonVerNetTot['m'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrColNetTot['f']+$arrNonVerNetTot['f'];?></span></td>
            <td align="center" bgcolor="#0066FF"><span class="style1"><?php echo $arrColNetTot['cgot']+$arrNonVerNetTot['cgot'];?></span></td>
        </tr>
<?php ?>
  </table>
 
</div>
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
            location.href = "<?php echo url_for('report/exportNewStdEpisodeReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportNewStdEpisodeReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=PDF") ?>";
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
  