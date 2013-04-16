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
</script>
<div class="form-heading"><h1>Patient Registration</h1></div>
<br />
<div>
    <form name="frmPatient" id="frmPatient" method="post" action="<?php echo url_for('registration/savePatientInformation') ?>">
        <div id="tabs">

            <ul>
                <li><a href="#tabs-1">Registration Information</a></li>
                <li><a href="#tabs-2">Personal Information</a></li>
                <li><a href="#tabs-3">Contact Information</a></li>
                <li><a href="#tabs-4">Reason for Attendance Information</a></li>
                <li><a href="#tabs-5">Episode History</a></li>

            </ul>

            <div id="tabs-1">

              <table width="818" border="0">
                    <tr>
                        <td width="150"> <label for="cmbPatientCategory"  class="">Patient Category<span class="required">*</span></label></td>
                        <td width="120">
                            <select id="cmbPatientCategory" name="cmbPatientCategory"   tabindex="5">
                                <option value="0">---Select---</option>
                                <?php foreach ($patientCategories as $patientategory) { ?>

                                <option value="<?php echo $patientategory['category_id'];  ?>"><?php echo $patientategory['patient_category'];  ?></option>

                                    <?php } ?>
                            </select>
                        </td>
                        <td width="120"><label for="txtRegDate" class="">Patient No<</label></td>
                        <td width="180"><input type="text" id="txtPatientNo" name="txtPatientNo" tabindex="6"  value="<?php echo $patient->getPatientNo() ; ?>" /></td>
                        <td width="120"><label for="txtRegDate" class="">Registered Date</label></td>
                        <td width="180"><input type="text" id="txtRegDate" name="txtRegDate"  tabindex="7"  value="<?php echo $regDate; ?>" /></td>
                    </tr>
              </table>
            </div>

             <div id="tabs-2">

                <table width="818" border="0">
                  
                    <tr>
                        <td width="119"><label for="txtFirstName" >First Name</label></td>
                        <td width="300"><input type="text" id="txtFirstName" name="txtFirstName" class="formInputText" tabindex="8"  value="" /></td>
                        <td width="123"><label for="txtLastName">Last Name<span class="required">*</span></label></td>
                        <td width="300"><input type="text" id="txtLastName" name="txtLastName" class="formInputText" tabindex="9"  value="" /></td>
                    </tr>
                    <tr>
                        <td><label for="txtDateOfBirth">Date Of Birth</label></td>
                        <td width="300"> <input type="text" id="txtDateOfBirth" name="txtDateOfBirth" class="formInputText" tabindex="10"  value="" /></td>
                        <td><label for="cmbSex">Sex<span class="required">*</span></label></td>
                        <td width="300">
                            <select id="cmbSex" name="cmbSex" class="formSelect" tabindex="11">
                                <option value="0">---Select---</option>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="cmbMaritalStatus">Marital Status</label></td>
                        <td width="300">
                            <select id="cmbMaritalStatus" name="cmbMaritalStatus" class="formSelect" tabindex="12">
                                <option value="0">---Select---</option>
                                <option value="1">Married</option>
                                <option value="2">Unmarried</option>

                            </select></td>
                        <td><label for="cmbNationality">Nationality</label></td>
                        <td width="300">
                            <select id="cmbNationality" name="cmbNationality" class="formSelect" tabindex="13">
                                <option value="0">---Select---</option>
                                <option value="SL">SL-</option>
                                <option value="IND">IND</option>
                            </select>    </td>
                    </tr>
                    <tr>
                        <td><label for="cmbEducation">Education Level</label></td>
                        <td width="300">
                            <select id="cmbEducation" name="cmbEducation" class="formSelect" tabindex="14">
                                <option value="0">---Select---</option>
                                <option value="AL">AL</option>
                                <option value="OL">OL</option>
                            </select>	</td>
                        <td><label for="cmbOccupation">Occupation<span class="required">*</span></label></td>
                        <td width="300">
                            <select id="cmbOccupation" name="cmbOccupation" class="formSelect" tabindex="15">
                                <option value="0">---Select---</option>
                                <option value="SE">SE</option>
                                <option value="ASE">ASE</option>
                            </select></td>
                    </tr>
                </table>
            </div>

            <div id="tabs-2">
                <table width="818" border="0">
                    <tr>
                        <td width="111"><label for="txtCurrentAddress">Current Address</label></td>
                        <td width="285"> <textarea name="txtCurrentAddress" id="txtCurrentAddress" rows="2" cols="30"  class="formTextArea" tabindex="16"   ></textarea></td>
                        <td width="115"><label for="txtTel1">Telephone 1<span class="required">*</span></label></td>
                        <td colspan="4"><input type="text" name="txtTel1" id="txtTel1" class="formInputText" tabindex="17"  value="" /></td>
                    </tr>
                    <tr>
                        <td> <label for="txtPermanentAddress">Permanent Address</label></td>
                        <td width="285"><textarea name="txtPermanentAddress" id="txtPermanentAddress" rows="2" cols="30" tabindex="18" class="formTextArea"   ></textarea></td>
                        <td><label for="txtTel2">Telephone 2</label></td>
                        <td colspan="4"><input type="text" name="txtTel2" id="txtTel2" class="formInputText" tabindex="19"  value="" /></td>
                    </tr>
                    <tr>
                        <td><label for="cmbMaritalStatus"><label for="txtContactAddress">Contact Address</label></label></td>
                        <td width="285"><textarea name="txtContactAddress" id="txtContactAddress" rows="2" cols="30" tabindex="20" class="formTextArea"   ></textarea></td>
                        <td><label for="cmbNationality"><label for="txtMobile">Mobile</label></label></td>
                        <td colspan="4"> <input type="text" name="txtMobile" id="txtMobile" class="formInputText" tabindex="21"  value="" /></td>
                    </tr>
                    <tr>
                        <td><label for="txtEmail">Email</label></td>
                        <td width="285"><input type="text" name="txtEmail" id="txtEmail" class="formInputText" tabindex="22"  value="" /></td>
                        <td><label for="txtMoc">Mode of Contact</label></td>
                        <td width="55"><input type="radio"name="contactMode" id="optContactMode1" value="no"  class="formRadio" tabindex="23" onclick="changeContactMode();" />&nbsp;</td>
                        <td width="85"><label for="optContactMode1" class="optionlabel">No Contact</label></td>
                        <td width="30"><input type="radio"name="contactMode" id="optContactMode2" value="yes"  class="formRadio" tabindex="24" onclick="changeContactMode();" checked/>&nbsp;</td>
                        <td width="107"><label for="optContactMode2" class="optionlabel">Contact Through</label>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="4"> <div >
                                <label for="chkLetter" class="optionlabel">Letter</label>
                                <input type="checkbox" name="method[<?php echo 'letter'; ?>]" value="Letter" id="chkLetter" class="formCheckbox columncheckbox" tabindex="25" />

                                <label for="chkEmail" class="optionlabel">Email</label>
                                <input type="checkbox" name="method[<?php echo 'email'; ?>]" value="Email" id="chkEmail" class="formCheckbox columncheckbox" tabindex="26" />

                                <label for="chkPhone" class="optionlabel">Phone</label>
                                <input type="checkbox" name="method[<?php echo 'phone'; ?>]" value="Phone" id="chkPhone" class="formCheckbox columncheckbox" tabindex="27"/>

                                <label for="chkVisit" class="optionlabel">Visit</label>
                                <input type="checkbox" name="method[<?php echo 'visit'; ?>]" value="Visit" id="chkVisit" class="formCheckbox columncheckbox" tabindex="28" />
                            </div></td>
                    </tr>
                </table>
            </div>

            <div id="tabs-3">
                <table width="834" border="0">
                    <tr>
                        <td width="25"><input type="checkbox" name="reason[<?php echo 'opd'; ?>]" value="OPD" id="r1" /></td>
                        <td width="70">Ref.OPD</td>
                        <td width="144"> <input type="text" name="txtOpd" id="txtOpd" class="formInputText" tabindex="29"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'ward'; ?>]" value="Ward" id="r2" /></td>
                        <td width="70">Ref.Ward</td>
                        <td width="144"> <input type="text" name="txtWard" id="txtWard" class="formInputText" tabindex="30"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'gp'; ?>]" value="GP" id="r3"  /></td>
                        <td width="70">Ref.GP</td>
                        <td width="144"> <input type="text" name="txtGp" id="txtGp" class="formInputText" tabindex="31"  value="" /></td>
                    </tr>
                    <tr>
                        <td width="25"><input type="checkbox" name="reason[<?php echo 'court'; ?>]" value="Courts" id="r4" /></td>
                        <td width="70">Ref.Courts</td>
                        <td width="144"> <input type="text" name="txtCourt" id="txtCourt" class="formInputText" tabindex="32"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'bb'; ?>]" value="Blood Bank" id="r5" /></td>
                        <td width="70">Ref.Blood Bank</td>
                        <td width="144"> <input type="text" name="txtBlood" id="txtBlood" class="formInputText" tabindex="33"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'contact'; ?>]" value="Contact" id="r6"  /></td>
                        <td width="70">Contact</td>
                        <td width="144"> <input type="text" name="txtRContact" id="txtRContact" class="formInputText" tabindex="34"  value="" /></td>
                    </tr>
                    <tr>
                        <td width="25"><input type="checkbox" name="reason[<?php echo 'voluntary'; ?>]" value="Voluntary" id="r7" /></td>
                        <td width="70">Voluntary</td>
                        <td width="144"> <input type="text" name="txtVoluntary" id="txtVoluntary" class="formInputText" tabindex="35"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'clinic'; ?>]" value="Clinic Followup" id="r8" /></td>
                        <td width="70">Clinic Followup</td>
                        <td width="144"> <input type="text" name="txtClinic" id="txtClinic" class="formInputText" tabindex="36"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'other'; ?>]" value="Other" id="r9"  /></td>
                        <td width="70">Other</td>
                        <td width="144"> <input type="text" name="txtOther" id="txtOther" class="formInputText" tabindex="37"  value="" /></td>
                    </tr>
                </table>
            </div>

            <div class="form-actionbar" >
                <a class="form-button" style="clear:none; width: 75px;" id="btnSave" tabindex="6"  ><img class="image-button" src="/images/apply2.png" alt="Save"  />Register</a>
                <a class="form-button" style="clear:none;"id="btnClear"tabindex="7"><img class="image-button"  src="/images/cross.png" alt="Clear" />Clear</a>
                <a class="form-button" style="clear:none;"id="btnBack"tabindex="8"><img class="image-button"  src="/images/back-button.jpg" alt="Back" />Back</a>
            </div>
        </div>
        <br/>

    </form>
</div>

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

        //        $(function() {
        //            $("#accordion").accordion({ autoHeight: false, active: false });
        //        });

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
                txtTel1: {
                    required: true
                }
            },
            messages: {
                txtLastName: "Last name is required.",
                txtTel1: "Telephone number is required."
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmPatient').submit();
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('registration/showPatientCategoryList') ?>";
        });


        $(function() {
            $("#txtDateOfBirth").datepicker({
                showOn: 'button',
                buttonImage: '/images/calendar.gif',
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd'
            });
        });

        $(function() {
            $("#txtRegDate").datepicker({
                showOn: 'button',
                buttonImage: '/images/calendar.gif',
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd'
            });
        });

    });
</script>