<?php
$rights = $_SESSION['arrRights'];
$modNames = $_SESSION['modName'];

if(isset ($patient[0])) {
    $patient = $patient[0];
    $patientNo = $patient->getPatientNo();
    foreach ($patient->getContactMode() as $mode) {
        $contactMode = $mode;
    }

    foreach ($patient->getClinicReason() as $clinicReason) {
        $reason = $clinicReason;
    }

}

$selctedEducationValue= "";
$selctedOccupationValue = "";
$selctedNationality = "";

if($patient->getNationality() == "Srilankan") {
    $selctedNationality = "Srilankan";
}else {
    $selctedNationality = "Other";
}

if($patient->getEducation() == "1-5 Grade" ) {
    $selctedEducationValue = "1-5 Grade";
    $selectedEducation = "selected";
}else if($patient->getEducation() == "6-10 Grade") {
    $selctedEducationValue = "6-10 Grade";
    $selectedEducation = "selected";
}else if($patient->getEducation() == "G.C.E O/L") {
    $selctedEducationValue = "G.C.E O/L";
}else if($patient->getEducation() == "G.C.E A/L") {
    $selctedEducationValue = "G.C.E A/L";
    $selectedEducation = "selected";
}else if($patient->getEducation() == "No schooling / NA") {
    $selctedEducationValue = "No schooling / NA";
    $selectedEducation = "selected";
}else {
    $selctedEducationValue = "Other";
    $selectedEducation = "selected";
}

if($patient->getOccupation() == "CSW" ) {
    $selctedOccupationValue = "CSW";
}else if($patient->getOccupation() == "Student") {
    $selctedOccupationValue = "Student";
}else if($patient->getOccupation() == "Retired") {
    $selctedOccupationValue = "Retired";
}else if($patient->getOccupation() == "Unemployed") {
    $selctedOccupationValue = "Unemployed";
}else {
    $selctedOccupationValue = "Other";
}


?>


<script type="text/javascript">

    function changeContactMode() {

        if(document.getElementById('optContactMode1').checked) {

            document.getElementById('chkLetter').disabled = true;
            document.getElementById('chkPhone').disabled = true;
            document.getElementById('chkVisit').disabled = true;
            document.getElementById('chkEmail').disabled = true;

            document.getElementById('chkLetter').checked = false;
            document.getElementById('chkPhone').checked = false;
            document.getElementById('chkVisit').checked = false;
            document.getElementById('chkEmail').checked = false;

        }else {

            document.getElementById('chkLetter').disabled = false;
            document.getElementById('chkPhone').disabled = false;
            document.getElementById('chkVisit').disabled = false;
            document.getElementById('chkEmail').disabled = false;
        }

    }

    function clearReasonFields() {

        if(document.getElementById('r1').checked == false && document.getElementById('txtOpd').value != "") {
            document.getElementById('txtOpd').value = "";
        }

        if(document.getElementById('r2').checked == false &&  document.getElementById('txtWard').value != "") {
            document.getElementById('txtWard').value = "";
        }

        if(document.getElementById('r3').checked == false &&  document.getElementById('txtGp').value != "") {
            document.getElementById('txtGp').value = "";
        }

        if(document.getElementById('r4').checked == false &&  document.getElementById('txtCourt').value != "") {
            document.getElementById('txtCourt').value = "";
        }

        if(document.getElementById('r5').checked == false && document.getElementById('txtBlood') != "") {
            document.getElementById('txtBlood').value = "";
        }

        if(document.getElementById('r6').checked == false && document.getElementById('txtRContact').value != "") {
            document.getElementById('txtRContact').value = "";
        }

        if(document.getElementById('r7').checked == false && document.getElementById('txtVoluntary').value != "") {
            document.getElementById('txtVoluntary').value = "";
        }

        if(document.getElementById('r8').checked == false && document.getElementById('txtClinic').value != "") {
            document.getElementById('txtClinic').value = "";
        }

        if(document.getElementById('r9').checked == false && document.getElementById('txtOther').value != "") {
            document.getElementById('txtOther').value = "";
        }
    }

    function showOtherOccupation(occupation) {
        if(occupation=="Other") {
            document.getElementById('txtOtherOccupation').style.display = "block";
        }else {
            document.getElementById('txtOtherOccupation').style.display = "none";
        }
    }

    function showOtherNationallity(occupation) {
        if(occupation=="Other") {
            document.getElementById('txtOtherNationallity').style.display = "block";
        }else {
            document.getElementById('txtOtherNationallity').style.display = "none";
        }
    }

    function showOtherEducation(occupation) {
        if(occupation=="Other") {
            document.getElementById('txtOtherEducation').style.display = "block";
        }else {
            document.getElementById('txtOtherEducation').style.display = "none";
        }
    }
</script>
<?php


?>
<div class="form-heading"><h1>Patient Registration</h1>

    <?php if ($sf_user->hasFlash('notice')): ?>
    <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
    <?php endif ?>

    <?php if ($sf_user->hasFlash('error')): ?>
    <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>

    <?php endif ?>

    <div id="divConfirmationDialog" title="Mark / Create Visit">
        Are you sure want to mark this record ?
    </div>

</div>


<?php  if(($search == true) && ($recordCount >0) ) { ?>
<div>

    <div id="tabs">
        <form name="frmPatient" id="frmPatient" method="post" action="<?php echo url_for('registration/updatePatientInformation') ?>">
            <ul>
                <li><a href="#tabs-1">Registration Information</a></li>
                <li><a href="#tabs-2">Personal Information</a></li>
                <li><a href="#tabs-3">Contact Information</a></li>
                <li><a href="#tabs-4">Reason for Attendance Information</a></li>
                <li><a href="#tabs-5">Episode History</a></li>

            </ul>

            <div id="tabs-1">

                <table width="926" border="0">
                    <tr>
                      <td width="125"> <label for="cmbPatientCategory"  class="">Patient Category<span class="required">*</span></label></td>
                        <td width="114">
                            <select id="cmbPatientCategory" name="cmbPatientCategory"   tabindex="5">
                                <option value="0">---Select---</option>
                                    <?php foreach ($patientCategories as $patientategory) {
                                        ?>


                                <option value="<?php echo $patientategory['category_id'];  ?>" <?php if($patientategory['category_id'] == $patient->getCategory()) {
                                            echo 'selected';
                                                } ?> ><?php echo $patientategory['patient_category'];  ?></option>

                                        <?php


                                    } ?>
                            </select>
                      </td>
                        <td width="98"><label for="txtPatientNo" class="">Patient No</label></td>
                        <td width="169"><input type="text" id="txtPatientNo" name="txtPatientNo" tabindex="6"  value="<?php echo $patient->getPatientNo() ; ?>" readonly="readonly" /></td>
                        <td width="105"><label for="txtRegDate" class="">Registered Date</label></td>
                        <td width="289"><input type="text" id="txtRegDate" name="txtRegDate"  tabindex="7"  value="<?php echo $patient->getRegisteredDate() ; ?>" /></td>
                    </tr>
              </table>
            </div>

            <div id="tabs-2">

                <table width="818" border="0">

                    <tr>
                        <td width="119"><label for="txtFirstName" >First Name</label></td>
                        <td width="300" colspan="2"><input type="text" id="txtFirstName" name="txtFirstName" class="formInputText" tabindex="8"  value="<?php echo base64_decode($patient->getFirstName()); ?>" /></td>
                        <td width="123"><label for="txtLastName">Last Name<span class="required">*</span></label></td>
                        <td width="300" colspan="2"><input type="text" id="txtLastName" name="txtLastName" class="formInputText" tabindex="9"  value="<?php echo base64_decode($patient->getLastName()); ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="txtDateOfBirth">Date Of Birth</label></td>
                        <td width="300" colspan="2"><input type="text" id="txtDateOfBirth" name="txtDateOfBirth" class="formInputText" tabindex="10"  value="<?php echo $patient->getDateOfBirth(); ?>"  /></td>
                        <td><label for="cmbSex">Sex<span class="required">*</span></label></td>
                        <td width="300" colspan="2">
                            <select id="cmbSex" name="cmbSex" class="formSelect" tabindex="11">
                                <option value="0">---Select---</option>
                                <option value="0">---Select---</option>
                                <option <?php if($patient->getSex() == "1") {
                                        echo 'selected';
                                        } ?> value="1">Male</option>
                                <option <?php if($patient->getSex() == "2") {
                                        echo 'selected';
                                        } ?> value="2">Female</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="cmbMaritalStatus">Marital Status</label></td>
                        <td width="300" colspan="2">
                            <select id="cmbMaritalStatus" name="cmbMaritalStatus" class="formSelect" tabindex="12">
                                <option value="0">---Select---</option>
                                <option value="1" <?php if($patient->getMaritalStatus() == "1" ) {
                                        echo 'selected';
                                            } ?>>Married</option>
                                <option  value="2" <?php if($patient->getMaritalStatus() == "2" ) {
                                        echo 'selected';
                                             } ?>>Single</option>
                                <option value="3" <?php if($patient->getMaritalStatus() == "3" ) {
                                        echo 'selected';
                                            } ?>>Sep / Divo</option>
                                <option value="4" <?php if($patient->getMaritalStatus() == "4" ) {
                                        echo 'selected';
                                            } ?>>Widowed</option>
                                <option value="5" <?php if($patient->getMaritalStatus() == "5" ) {
                                        echo 'selected';
                                            } ?>>Living Together</option>
                                <option value="6" <?php if($patient->getMaritalStatus() == "6" ) {
                                        echo 'selected';
                                            } ?>>Not Known</option>
                            </select></td>
                        <td><label for="cmbNationality">Nationality</label></td>
                        <td width="149">
                            <select id="cmbNationality" name="cmbNationality" class="formSelect" tabindex="13" onchange="showOtherNationallity(this.value);">
                                <option value="0" >---Select---</option>
                                <option value="Srilankan" <?php if($selctedNationality == "Srilankan") {
                                        echo "selected";
                                            } ?>>Srilankan</option>
                                <option value="Other"<?php if($selctedNationality == "Other") {
                                        echo "selected";
                                            } ?> >Other</option>
                            </select>  </td>
                        <td width="149"><input type="text" name="txtOtherNationallity" id="txtOtherNationallity" value="<?php if($selctedNationality == "Other") {
                                    echo $patient->getNationality();
                                                   } ?>" class="formInputText" <?php if($selctedEducationValue=="Other" ) { ?> style="display: block" <?php } else { ?>  style="display: none" <?php }?>/></td>
                    </tr>
                    <tr>
                        <td><label for="cmbEducation">Education Level</label></td>
                        <td width="149">
                            <select id="cmbEducation" name="cmbEducation" class="formSelect" tabindex="14" onchange="showOtherEducation(this.value);">
                                <option value="0">---Select---</option>
                                <option value="1-5 Grade" <?php if($selctedEducationValue == "1-5 Grade") {
                                        echo 'selected';
                                            } ?>>1-5 Grade</option>
                                <option value="6-10 Grade" <?php if($selctedEducationValue == "6-10 Grade" ) {
                                        echo 'selected';
                                            } ?>>6-10 Grade</option>
                                <option value="G.C.E O/L" <?php if($selctedEducationValue == "G.C.E O/L" ) {
                                        echo 'selected';
                                            } ?>>G.C.E O/L</option>
                                <option value="G.C.E A/L" <?php if($selctedEducationValue == "G.C.E A/L" ) {
                                        echo 'selected';
                                            } ?>>G.C.E A/L</option>
                                <option value="No schooling / NA" <?php if($selctedEducationValue == "No schooling / NA" ) {
                                        echo 'selected';
                                            } ?>>No schooling / NA</option>
                                <option value="Other" <?php if($selctedEducationValue == "Other" ) {
                                        echo 'selected';
                                            } ?>>Other</option>
                            </select></td>
                        <td width="149"><input type="text" name="txtOtherEducation" id="txtOtherEducation" value="<?php if($selctedEducationValue == "Other") {
                                    echo $patient->getEducation();
                                                   } ?>"  <?php if($selctedEducationValue=="Other" ) { ?> style="display: block" <?php } else { ?>  style="display: none" <?php }?>/></td>
                        <td><label for="cmbOccupation">Occupation<span class="required">*</span></label></td>
                        <td width="149">
                            <select id="cmbOccupation" name="cmbOccupation" class="formSelect" tabindex="15" onchange="showOtherOccupation(this.value);">
                                <option value="0" >---Select---</option>
                                <option value="CSW" <?php if($selctedOccupationValue =="CSW") {
                                        echo "selected";
                                            } ?>>CSW</option>
                                <option value="Student" <?php if($selctedOccupationValue =="Student") {
                                        echo "selected";
                                            } ?>>Student</option>
                                <option value="Retired" <?php if($selctedOccupationValue =="Retired") {
                                        echo "selected";
                                            } ?>>Retired</option>
                                <option value="Unemployed" <?php if($selctedOccupationValue =="Unemployed") {
                                        echo "selected";
                                            } ?>>Unemployed</option>
                                <option value="Other" <?php if($selctedOccupationValue =="Other") {
                                        echo "selected";
                                            } ?>>Other</option>
                            </select></td>
                        <td width="149"><input type="text" name="txtOtherOccupation" id="txtOtherOccupation" value="<?php if($selctedOccupationValue == "Other") {
                                    echo $patient->getOccupation();
                                                   } ?>"  <?php if($selctedOccupationValue=="Other" ) { ?> style="display: block" <?php } else { ?>  style="display: none" <?php }?>/></td>
                    </tr>
                     <tr>
                        <td>NIC /Passport No </td>
                        <td colspan="2"><input type="text" name="txtNic" id="txtNic" class="formInputText" tabindex=""  value="<?php echo $patient->getNicPpNo()?>" />&nbsp;</td>
                        <td>Comment</td>
                        <td colspan="2"><input type="text" name="txtComment" id="txtComment" class="formInputText" tabindex=""  value="<?php echo $patient->getComment()?>"/></td>
                    </tr>
                </table>
            </div>

            <div id="tabs-3">
                <table width="818" border="0">
                    <tr>
                        <td width="111"><label for="txtCurrentAddress">Current Address</label></td>
                        <td width="285"> <textarea name="txtCurrentAddress" id="txtCurrentAddress" rows="2" cols="30"  class="formTextArea" tabindex="16"   ><?php echo base64_decode($patient->getCurrentAddress()); ?></textarea></td>
                        <td width="115"><label for="txtTel1">Telephone 1</label></td>
                        <td colspan="4"><input type="text" name="txtTel1" id="txtTel1" class="formInputText" tabindex="17"  value="<?php echo base64_decode($patient->getTelephone1()); ?>"  /></td>
                    </tr>
                    <tr>
                        <td> <label for="txtPermanentAddress">Permanent Address</label></td>
                        <td width="285"><textarea name="txtPermanentAddress" id="txtPermanentAddress" rows="2" cols="30" tabindex="18" class="formTextArea"   ><?php echo base64_decode($patient->getPermanentAddress()); ?></textarea></td>
                        <td><label for="txtTel2">Telephone 2</label></td>
                        <td colspan="4"><input type="text" name="txtTel2" id="txtTel2" class="formInputText" tabindex="19"  value="<?php echo base64_decode($patient->getTelephone2()); ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="txtContactAddress">Contact Address</label></td>
                        <td width="285"><textarea name="txtContactAddress" id="txtContactAddress" rows="2" cols="30" tabindex="20" class="formTextArea"   ><?php echo base64_decode($patient->getContactAddress()); ?></textarea></td>
                        <td><label for="cmbNationality"><label for="txtMobile">Mobile</label></label></td>
                        <td colspan="4"> <input type="text" name="txtMobile" id="txtMobile" class="formInputText" tabindex="21"   value="<?php echo base64_decode($patient->getMobile()); ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="txtEmail">Email</label></td>
                        <td width="285"><input type="text" name="txtEmail" id="txtEmail" class="formInputText" tabindex="22"  value="<?php echo base64_decode($patient->getEmail()); ?>" /></td>
                        <td><label for="txtMoc">Mode of Contact</label></td>
                        <td width="55"><input type="radio"name="contactMode" id="optContactMode1" value="no"  class="formRadio" tabindex="23" <?php if(isset($contactMode) && $contactMode->getNoContact() == "1" ) {
                                    echo "checked";
                                                  } ?> onclick="changeContactMode();"/></td>
                        <td width="85"><label for="optContactMode1" class="optionlabel">No Contact</label></td>
                        <td width="30"><input type="radio"name="contactMode" id="optContactMode2" value="yes"  class="formRadio" tabindex="24"  <?php if(isset($contactMode) && $contactMode->getNoContact() == "0" ) {
                                    echo "checked";
                                                  } ?>  onclick="changeContactMode();"/></td>
                        <td width="107"><label for="optContactMode2" class="optionlabel">Contact Through</label>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="4"> <div >
                                <label for="chkLetter" class="optionlabel">Letter</label>
                                <input <?php if(isset($contactMode) && $contactMode->getLetter() == "1") {
                                        echo "checked";
                                        } ?> type="checkbox" name="method[<?php echo 'letter'; ?>]" value="Letter" id="chkLetter" class="formCheckbox columncheckbox" tabindex="25"  <?php if(isset($contactMode) && $contactMode->getNoContact() == "1" ) {
                                            echo "disabled";
                                        } ?>   />

                                <label for="chkEmail" class="optionlabel">Email</label>
                                <input <?php if(isset($contactMode) && $contactMode->getEmail() == "1") {
                                        echo "checked";
                                        } ?> type="checkbox" name="method[<?php echo 'email'; ?>]" value="Email" id="chkEmail" class="formCheckbox columncheckbox" tabindex="26" <?php if(isset($contactMode) && $contactMode->getNoContact() == "1" ) {
                                            echo "disabled";
                                        } ?> />

                                <label for="chkPhone" class="optionlabel">Phone</label>
                                <input <?php if(isset($contactMode) && $contactMode->getTelephone() == "1") {
                                        echo "checked";
                                        } ?> type="checkbox" name="method[<?php echo 'phone'; ?>]" value="Phone" id="chkPhone" class="formCheckbox columncheckbox" tabindex="27" <?php if(isset($contactMode) && $contactMode->getNoContact() == "1" ) {
                                            echo "disabled";
                                        } ?>/>

                                <label for="chkVisit" class="optionlabel">Visit</label>
                                <input <?php if(isset($contactMode) && $contactMode->getVisit() == "1") {
                                        echo "checked";
                                        } ?> type="checkbox" name="method[<?php echo 'visit'; ?>]" value="Visit" id="chkVisit" class="formCheckbox columncheckbox" tabindex="28"<?php if(isset($contactMode) && $contactMode->getNoContact() == "1" ) {
                                            echo "disabled";
                                        } ?> />
                            </div></td>
                    </tr>
                </table>
            </div>

            <div id="tabs-4">
                <table width="834" border="0">
                    <tr>
                        <td width="25"><input type="checkbox" name="reason[<?php echo 'opd'; ?>]" value="OPD" id="r1" <?php if(isset($reason) && $reason->getOpd() != NULL) {
                                    echo "checked";
                                                  } ?> onclick="clearReasonFields();" /></td>
                        <td width="70">Ref.OPD</td>
                        <td width="144"> <input type="text" name="txtOpd" id="txtOpd" class="formInputText" tabindex="29"  value="<?php if(isset($reason) && $reason->getOpd() != NULL) {
                                    echo  $reason->getOpd();
                                                    }?>"  /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'ward'; ?>]" value="Ward" id="r2" <?php if(isset($reason) && $reason->getWard() != NULL) {
                                    echo "checked";
                                                  } ?> onclick="clearReasonFields();"/></td>
                        <td width="70">Ref.Ward</td>
                        <td width="144"> <input type="text" name="txtWard" id="txtWard" class="formInputText" tabindex="30"  value="<?php if(isset($reason) && $reason->getWard() != NULL) {
                                    echo $reason->getWard();
                                                    }?>"  /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'gp'; ?>]" value="GP" id="r3" <?php if(isset($reason) && $reason->getGp() != NULL) {
                                    echo "checked";
                                                  } ?> onclick="clearReasonFields();" /></td>
                        <td width="70">Ref.GP</td>
                        <td width="144"> <input type="text" name="txtGp" id="txtGp" class="formInputText" tabindex="31"  value="<?php if(isset($reason) && $reason->getGp() != NULL) {
                                    echo $reason->getGp();
                                                    }?>"  /></td>
                    </tr>
                    <tr>
                        <td width="25"><input type="checkbox" name="reason[<?php echo 'court'; ?>]" value="Courts" id="r4" <?php if(isset($reason) && $reason->getCourt() != NULL) {
                                    echo "checked";
                                                  } ?> onclick="clearReasonFields();"/></td>
                        <td width="70">Ref.Courts</td>
                        <td width="144"> <input type="text" name="txtCourt" id="txtCourt" class="formInputText" tabindex="32"  value="<?php if(isset($reason) && $reason->getCourt() != NULL) {
                                    echo $reason->getCourt();
                                                    }?>"   /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'bb'; ?>]" value="Blood Bank" id="r5"  <?php if(isset($reason) && $reason->getBb() != NULL) {
                                    echo "checked";
                                                  } ?> onclick="clearReasonFields();"/></td>
                        <td width="70">Ref.Blood Bank</td>
                        <td width="144"> <input type="text" name="txtBlood" id="txtBlood" class="formInputText" tabindex="33"  value="<?php if(isset($reason) && $reason->getBb() != NULL) {
                                    echo $reason->getBb();
                                                    }?>" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'contact'; ?>]" value="Contact" id="r6"  <?php if(isset($reason) && $reason->getContact() != NULL) {
                                    echo "checked";
                                                  } ?> onclick="clearReasonFields();"/></td>
                        <td width="70">Contact</td>
                        <td width="144"> <input type="text" name="txtRContact" id="txtRContact" class="formInputText" tabindex="34" value="<?php if(isset($reason) && $reason->getContact() != NULL) {
                                    echo $reason->getContact();
                                                    }?>" /></td>
                    </tr>
                    <tr>
                        <td width="25"><input type="checkbox" name="reason[<?php echo 'voluntary'; ?>]" value="Voluntary" id="r7"  <?php if(isset($reason) && $reason->getVolantary() != NULL) {
                                    echo "checked";
                                                  } ?> onclick="clearReasonFields();"/></td>
                        <td width="70">Voluntary</td>
                        <td width="144"> <input type="text" name="txtVoluntary" id="txtVoluntary" class="formInputText" tabindex="35"  value="<?php if(isset($reason) && $reason->getVolantary() != NULL) {
                                    echo $reason->getVolantary();
                                                    }?>" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'clinic'; ?>]" value="Clinic Followup" id="r8" <?php if(isset($reason) && $reason->getCf() != NULL) {
                                    echo "checked";
                                                  } ?> onclick="clearReasonFields();"/></td>
                        <td width="70">Clinic Followup</td>
                        <td width="144"> <input type="text" name="txtClinic" id="txtClinic" class="formInputText" tabindex="36"  value="<?php if(isset($reason) && $reason->getCf() != NULL) {
                                    echo $reason->getCf();
                                                    }?>" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'other'; ?>]" value="Other" id="r9"  <?php if(isset($reason) && $reason->getOther() != NULL) {
                                    echo "checked";
                                                  } ?> onclick="clearReasonFields();" /></td>
                        <td width="70">Other</td>
                        <td width="144"> <input type="text" name="txtOther" id="txtOther" class="formInputText" tabindex="37"  value="<?php if(isset($reason) && $reason->getOther() != NULL) {
                                    echo $reason->getOther();
                                                    }?>" /></td>
                    </tr>
                </table>
            </div>

            <div id="tabs-5">
                <table width="800px">
                    <thead>
                    <th>Episode No</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <td></td>
                    </thead>
                        <?php foreach ($patient->getEpisode() as $episode) {
                            ?>
                    <tr>
                        <td align="center"><?php echo $episode->getEpisodeNo(); ?></td>
                        <td align="center"><?php $startDate = $episode->getStartDate();
                                    if(!empty($startDate)) {
                                        echo $startDate;
                                    }else {
                                        echo "---";
                                    } ?></td>
                        <td align="center"><?php $endDate = $episode->getEndDate();
                                    if(!empty($endDate)) {
                                        echo $episode->getEndDate();
                                    }else {
                                        echo "---";
                                    } ?></td>
                        <td align="center">
                                    <?php
                                    if($episode->getStatus() == "0") {
                                        echo "Open";
                                    }else if($episode->getStatus() == "1") {
                                        echo "Closed";
                                    }else if($episode->getStatus() == "2") {
                                        echo "Default";
                                    }else if($episode->getStatus() == "3") {
                                        echo "";
                                    }else if($episode->getStatus() == "4") {
                                        echo "";
                                    }else if($episode->getStatus() == "5") {
                                        echo "Canceled";
                                    }

                                    ?>
                        </td>
                        <td align="center">
                        <?php if($episode->getStatus() != "5"){?>
                        <a href="<?php echo url_for('consultancy/cancelEpisode?pid='.$patient->getPatientNo()."&eid=".$episode->getEpisodeNo()); ?>">Cancel</a> <?php }?></td>
                       
                    </tr>
                            <?php } ?>

                </table>
            </div>           
        </form>
        <table width="995" border="0">
            <tr>
                <td width="155"></td>
                <td width="830" valign="top">
                    <div class="demo">
                       <?php if(!empty($rights[$modNames['Registration']]['edit'])) { ?> <button id="btnSave" style="height:25px;">Save</button> <?php }?>                        
                        <?php if(!empty($rights[$modNames['Registration']]['edit'])) { ?> <button id="btnEpisode" style="height:25px;">New Episode</button><?php }?>   
                        <button id="btnClear" style="height:25px;">Clear</button>
                        <button id="btnBack">Back</button>
                    </div>
              </td>
            </tr>
      </table>
    </div>
    <br/>


</div>
    <?php } else {
    echo "No record found";
} ?>
<script type="text/javascript">

    jQuery.validator.addMethod(
    "selectNone",
    function(value, element) {
        if (element.value == "0")
        {
            return false;
        }
        else return true;
    },
    "Please select the patient category."
);
    jQuery.validator.addMethod(
    "selectSex",
    function(value, element) {
        if (element.value == "0")
        {
            return false;
        }
        else return true;
    },
    "Please select the gender."
);

    jQuery.validator.addMethod(
    "selectJob",
    function(value, element) {
        if (element.value == "0")
        {
            return false;
        }
        else return true;
    },
    "Please select the occupation."
);

    $(document).ready(function() {


        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
        //Validate the form
        $("#frmPatient").validate({

            rules: {

                cmbPatientCategory: {
                    selectNone: true
                },

                cmbSex: {
                    selectSex: true
                },
                cmbOccupation: {
                    selectJob: true
                },
                txtLastName: {
                    required: true
                },
               
                txtEmail: {
                    required: false,
                    email: true
                },
                txtRegDate: {
                	required: true,
                    date: true
                },
                txtDateOfBirth: {
                	required: true,
                    date: true
                }
            },
            messages: {
                txtLastName: "Last name is required",
                txtTel1: "Telephone number is required",
                txtEmail: "Valid email address is required",
                txtRegDate: "Valid date is required",
                txtDateOfBirth: "Valid date is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmPatient').submit();
        });

        $("#btnEpisode").click(function() {
<?php if(!empty ($patientNo)) { ?>
            location.href = "<?php echo url_for('registration/createNewEpisode?pid='.$patient->getPatientNo()) ?>";
    <?php }?>
            });


            //When Click back button
            $("#btnBack").click(function() {
                location.href = "<?php echo url_for('registration/showPatientList') ?>";
            });


            $(function() {
                $("#txtDateOfBirth").datepicker({
                    showOn: 'button',
                    buttonImage: '/images/calendar.gif',
                    buttonImageOnly: true,
                    dateFormat: 'yy-mm-dd',
                    yearRange: '1930:2100',
                    changeMonth: true,
                    changeYear: true
                    
                });
            });

            $(function() {
                $("#txtRegDate").datepicker({
                    showOn: 'button',
                    buttonImage: '/images/calendar.gif',
                    buttonImageOnly: true,
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '2000:2030'
                });
            });

            //When click on the delete button
            $('#divConfirmationDialog').dialog({
                autoOpen: false,
                resizable: false,
                width: 350,
                buttons: {
                    "Ok": function() {
                        location.href = '<?php echo url_for('registration/showCurrentVisit?pid='.$patient->getPatientNo()) ?>';
                    },
                    "Cancel": function() {
                        $(this).dialog("close");
                    }
                }

            });

            $(function() {
                $("button", ".demo").button();	

            });

            //When click reset buton
            $("#btnClear").click(function() {
                document.forms[0].reset('');
            });

            $('#btnVisit').click(function() {
                $('#divConfirmationDialog').dialog('open');
            });

        });
</script>