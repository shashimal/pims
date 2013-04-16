<?php
$rights = $_SESSION['arrRights'];
$modNames = $_SESSION['modName'];
if (!empty($patient)) {
    //$patient = $patient[0];
    $patientNo = $patient->getPatientNo();
    foreach ($patient->getContactMode() as $mode) {
        $contactMode = $mode;
    }

    foreach ($patient->getClinicReason() as $clinicReason) {
        $reason = $clinicReason;
    }
} else {

    $patient = NULL;
}
?>
<div id="tabs" class="div-tabs" style="width:960px">
    <ul>
        <li><a href="#tabs-1">STD Patient Episode of Care</a></li>
    </ul>
    <div class="message">
        <?php if ($sf_user->hasFlash('notice')): ?>
            <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
        <?php endif ?> <?php if ($sf_user->hasFlash('error')): ?>
                <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
        <?php endif ?>
            </div>

    <?php if (isset($patient)) {
 ?>

                    <div >
                        <table width="693"  cellspacing="0" style="margin-left: 3px;margin-top: 10px;">
                            <thead>
                            <th width="124" ></th>
                            <th width="170" ></th>
                            <th width="99" ></th>
                            <th width="268" ></th>

                            <td width="20"></thead>
                            <tr>
                                <td>Patient No</td>
                                <td colspan="3"><?php echo $patient->getPatientNo(); ?></td>
                            </tr>
                            <tr>
                                <td >Name</td>
                                <td><?php echo base64_decode($patient->getFirstName()) . " " . base64_decode($patient->getLastName()); ?></td>
                                <td >Address</td>
                                <td ><?php echo base64_decode($patient->getContactAddress()) ?></td>
                            </tr>
                            <tr>
                                <td >Date Of Birth</td>
                                <td ><?php echo $patient->getDateOfBirth(); ?></td>
                                <td >Gender</td>
                                <td><?php
                    if ($patient->getSex() == '1') {
                        echo "Male";
                    } else {
                        echo "Female";
                    }
    ?></td>
            </tr>
        </table>

    </div>
</div>
<div id="tabs1" class="div-tabs" style="width:960px">
    <ul>
        <li><a href="#tabs-1">Episodes History</a></li>
    </ul>

    <table class="normal-table" cellspacing="0" style="margin-left: 3px;margin-top: 10px; ">
        <thead>
        <th scope="col"   class="normal-list-head">Episode No</th>
        <th scope="col"   class="normal-list-head">Start Date</th>
        <th scope="col"   class="normal-list-head">End Date</th>
        <th scope="col"   class="normal-list-head">Status</th>
        <th scope="col"   class="normal-list-head"></th>
        </thead>
        <?php
                    foreach ($patient->getEpisode() as $episode) {
        ?>
                        <tr>
                            <td class="normal-lsit-data"><?php echo $episode->getEpisodeNo(); ?></td>
                    <td class="normal-lsit-data"><?php
                        $startDate = $episode->getStartDate();
                        if (!empty($startDate)) {
                            echo $startDate;
                        } else {
                            echo "---";
                        }
        ?></td>
                    <td class="normal-lsit-data"><?php
                        $endDate = $episode->getEndDate();
                        if (!empty($endDate)) {
                            echo $episode->getEndDate();
                        } else {
                            echo "---";
                        } ?></td>
                    <td class="normal-lsit-data">
                <?php
                        if ($episode->getStatus() == "0") {
                            echo "Open";
                        } else if ($episode->getStatus() == "1") {
                            echo "Completed";
                        } else if ($episode->getStatus() == "2") {
                            echo "Referred";
                        } else if ($episode->getStatus() == "3") {
                            echo "Default";
                        } else if ($episode->getStatus() == "4") {
                            echo "Episode to be con";
                        } else if ($episode->getStatus() == "5") {
                            echo "Canceled";
                        }
                ?>
                    </td>

                    <td class="normal-lsit-data"><a href="<?php echo url_for('consultancy/viewEpisodeHistory?mode=view&eid=' . $episode->getEpisodeNo() . '&pid=' . $patientNo) ?>" class="td-link">Show STD Episode Care</a> </td>
                        </tr>
    <?php } ?>
                    </table>
    <?php
                } else {
                    echo "No records found.";
                }
    ?>

                </div>
<?php if (!empty($stdInputs)) { ?>
                    <div id="tabs2" class="div-tabs" style="width:960px">
                        <ul>
                            <li><a href="#tabs-1">STD Episode of Care</a></li>
                        </ul>
<?php //if(!empty ($stdInputs)) {   ?>

        <?php } ?>
            <form name="frmEpisode" id="frmEpisode" method="post" action="<?php echo url_for('consultancy/saveEpisodeDetails') ?>">
                <input type="hidden" name="txtPatientId" id="txtPatientId" value="<?php
                if (isset($patientNo)) {
                    echo $patientNo;
                }
        ?>" />
         <input type="hidden" name="txtEpisodeId" id="txtEpisodeId" value="<?php echo $episodeNo ?>" />

         <div id="accordion" style="width:960px">

            <?php
                foreach ($stdInputs as $key => $stdCategories) {

                    foreach ($avilableStdCategories as $value) {
                        if ($value['input_category_code'] == $key) {
                            $section = $value['name'];
                        }
                    }
            ?>
                <h3><a href='#'><strong><?php echo $section; ?></strong></a></h3>
                <div>
                <?php
                    foreach ($stdCategories as $stdInput) {
                        if (count($stdInput['StdInputResult']) > 0) {
                ?>

                            <table style="width: 900px;">
                                <tr>
                                    <td width="250px" style="color: #000066;"><?php echo $stdInput['input_name']; ?></td>
                                    <td><input type="hidden" name="Q<?php echo $stdInput['input_code']; ?>" /></td>
                        <td></td>
                        <td width="300px">
                            <?php
                            $stdResults = $stdInput['StdInputResult'];
                            $noOfAnswers = count($stdResults);
                            if ($noOfAnswers % 4 != 0) {
                                $tblRwos = (4 - ($noOfAnswers % 4)) + $noOfAnswers;
                            } else {
                                $tblRwos = $noOfAnswers;
                            }
                            ?>
                            <table  border="1" cellpadding="0" cellspacing="0" width="600px">
                                <?php
                                $a = 0;
                                foreach ($stdResults as $result) {
                                    $checked = "";

                                    foreach ($episodeHistory as $record) {
                                        if ($result['input_result_code'] == $record['result_code']) {
                                            $checked = "'checked'";
                                        }
                                    }

                                    if ($stdInput['no_of_input'] == 1) {

                                        $input = "<input type='radio' name='res" . $result['input_code'] . "[]' value='" . $result['input_result_code'] . "' $checked />";
                                    } else {

                                        $input = "<input type='checkbox' name='res" . $result['input_code'] . "[]' value='" . $result['input_result_code'] . "' $checked  />";
                                    }

                                    if ($a % 4 != 0) {

                                        echo "<td>" . $input . $result['result_description'] . "</td>";
                                        if ($a == count($stdResults) - 1) {

                                            $empty = ($tblRwos ) - (count($stdResults) - 1);
                                            for ($i = 1; $i < $empty; $i++) {
                                ?>
                                                <td></td>
                                <?php
                                            }
                                        }
                                    } else {
                                        echo "</tr>";
                                        echo "<tr>";

                                        echo "<td>" . $input . $result['result_description'] . "</td>";
                                        if ($a == count($stdResults) - 1) {

                                            $empty = ($tblRwos ) - (count($stdResults) - 1);
                                            for ($i = 1; $i < $empty; $i++) {
                                ?>
                                                <td></td>
                                <?php
                                            }
                                        }
                                    }

                                    $a++;
                                }
                                ?>

                            </table>

                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

                <?php
                            }
                        }
                ?>
                        </div>

<?php
                    }
?>
                </div>
            </form>
            <br />
<?php if (!empty($stdInputs)) { ?>
						  <?php if(!empty($rights[$modNames['Consultancy']]['edit'])) { ?>
                        <table border="0">
                            <tr>
                                <td width="283"></td>
                                <td width="324" valign="top">
                                    <div class="demo">
                                        <button id="btnSave" >Save</button>                                        
                                        <button id="btnClear" >Back</button>
                                    </div>
                              </td>
                            </tr>
                        </table>
                        <?php }?>
<?php } ?>
</div>
<script type="text/javascript">

    $(document).ready(function() {

        //Validate the form
        $("#frmSearchBox").validate({

            rules: {
                txtPatientNo: { required: true }
            },
            messages: {
                txtPatientNo: "Patient no is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmEpisode').submit();
        });

        // When click edit button
        $("#btnSearch").click(function() {
            $('#frmSearchBox').submit();
        });

        //When click reset buton
        $("#btnClear").click(function() {
            document.forms[0].reset('');
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
		
        $(function() {
            $("#tabs1").tabs({ selected: 0 });
        });
		
        $(function() {
            $("#tabs2").tabs({ selected: 0 });
        });

        $(function() {
            $("button", ".demo").button();

        });

        $(function() {
            $("#accordion").accordion({ active: false,autoHeight: false,collapsible: true });
        });

    });
</script>