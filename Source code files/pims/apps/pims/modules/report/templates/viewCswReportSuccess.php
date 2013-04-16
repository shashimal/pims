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
    <h4 align="center">Clinic Attendees of Commercial Sex Workers in <?php echo $year; ?></h4>
    <table width="800"  border="1"  class="report-table" >

		<?php 
       
        for($m = 1;$m <= 12; $m++){ 
            $month =  date("F", mktime(0, 0, 0, $m));
         ?> 
          
            <th width="15%" class="report-th"><?php echo $month;?></th>
           <?php 
        } 
       
        ?>
        <th width="15%" class="report-th">Total</th>
		<tr class="tr-even" >
        <?php 
        for($a=1; $a<=count($arrData);$a++) {
            
         ?>
        
            <td><?php echo $arrData[$a]; ?></td>
          
        <?php 
        }               
        ?>
        <td><?php echo $total;?></td>
	        
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
            location.href = "<?php echo url_for('report/exportCswReport?cmbYear='.$year."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportCswReport?cmbYear='.$year."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });

		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createCswtReport') ?>";
        });

    });
</script>
