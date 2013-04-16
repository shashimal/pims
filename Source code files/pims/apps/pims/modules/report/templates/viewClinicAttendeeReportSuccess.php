
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
    <h4 align="center">Clinic Attendees - Quarter <?php echo $quarter . " in ". $year; ?></h4>
    <table width="800"  border="1"  class="report-table" >

        <th width="15%" class="report-th">&nbsp;</th>
        <th width="15%" align="center" class="report-th">Males</th>
        <th width="15%" align="center" class="report-th">Females</th>
        <th width="15%" align="center" class="report-th">Total</th>

        <tr class="tr-even">
            <td><strong>Number of Newly Registered persons for the Quarter</strong></td>
            <td align="center"><?php echo $registerdPatients['male'] !="0" ? $registerdPatients['male'] : ""; ?></td>
            <td align="center"><?php echo $registerdPatients['female'] !="0" ? $registerdPatients['female'] : ""; ?></td>
            <td align="center"><?php echo $registerdPatients['tot'] !="0" ? $registerdPatients['tot'] : ""; ?></td>
        </tr>
        <tr class="tr-odd">
            <td><strong>Number of Newly registered persons  with STD</strong></td>
            <td align="center"><?php echo $stdPatients['male']!="0" ? $stdPatients['male'] : "";?></td>
            <td align="center"><?php echo $stdPatients['female'] !="0" ? $stdPatients['female'] : ""; ?></td>
            <td align="center"><?php echo $stdPatients['tot'] !="0" ? $stdPatients['tot'] : ""; ?></td>
        </tr>
        <tr class="tr-even">
            <td><strong>Total number of visits for the quarter</strong></td>
            <td align="center"><?php echo $visits['male']!="0" ? $visits['male'] : "";?></td>
            <td align="center"><?php echo $visits['female'] !="0" ? $visits['female'] : ""; ?></td>
            <td align="center"><?php echo $visits['tot'] !="0" ? $visits['tot'] : ""; ?></td>
        </tr>        
       
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
            location.href = "<?php echo url_for('report/exportClinicAttendeeReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=CSV") ?>";
        });
        
        $("#btnExport-PDF").click(function() {
            location.href = "<?php echo url_for('report/exportClinicAttendeeReport?cmbYear='.$year."&cmbQuarter=".$quarter."&rep=PDF") ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });

		$(function() {
                $("button", ".demo").button();

            });

        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createClinicAttendeeReport') ?>";
        });

    });
</script>
