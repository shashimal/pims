<?php 
$rights = $_SESSION['arrRights'];
$modNames = $_SESSION['modName'];
//echo $rights[$modNames['Consultancy']]['edit'];
//echo $rights[$modNames['Consultancy']]['view'];
//echo $rights[$modNames['Consultancy']]['add'];
//echo $rights[$modNames['Consultancy']]['delete'];
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
<div class="form-heading"><h1>Patient Registration</h1></div>
<br />
<div>

    <div id="tabs">
        <form name="frmPatient" id="frmPatient" method="post" action="<?php echo url_for('registration/savePatientInformation') ?>">
            <ul>
                <li><a href="#tabs-1">Personal Information</a></li>
                <li><a href="#tabs-2">Contact Information</a></li>
                <li><a href="#tabs-3">Reason for Attendance Information</a></li>

            </ul>

            <div id="tabs-1">

                <table width="835" border="0">
                    <tr>
                        <td width="135"> <label for="cmbPatientCategory"  class="">Patient Category<span class="required">*</span></label></td>
                        <td width="274" colspan="2">
                            <select id="cmbPatientCategory" name="cmbPatientCategory"   tabindex="1">
                                <option value="0">---Select---</option>
                                <?php foreach ($patientCategories as $patientategory) { ?>

                                <option value="<?php echo $patientategory['category_id'];  ?>"><?php echo $patientategory['patient_category'];  ?></option>

                                    <?php } ?>
                            </select>                        </td>
                        <td width="115"><label for="txtRegDate" class="">Registered Date</label></td>
                        <td width="293" colspan="2"><input type="text" id="txtRegDate" name="txtRegDate"  tabindex="2"  value="<?php echo $regDate; ?>" /></td>
                    </tr>
                    <tr>
                        <td width="135"><label for="txtFirstName" >First Name</label></td>
                        <td width="274" colspan="2"><input type="text" id="txtFirstName" name="txtFirstName" class="formInputText" tabindex="3"  value="" /></td>
                        <td width="115"><label for="txtLastName">Last Name<span class="required">*</span></label></td>
                        <td width="293" colspan="2"><input type="text" id="txtLastName" name="txtLastName" class="formInputText" tabindex="4"  value="" /></td>
                    </tr>
                    <tr>
                        <td><label for="txtDateOfBirth">Date Of Birth</label></td>
                        <td width="274" colspan="2"> <input type="text" id="txtDateOfBirth" name="txtDateOfBirth" class="formInputText" tabindex="5"  value=""  /></td>
                        <td><label for="cmbSex">Sex<span class="required">*</span></label></td>
                        <td width="293" colspan="2">
                            <select id="cmbSex" name="cmbSex" class="formSelect" tabindex="6">
                                <option value="0">---Select---</option>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="cmbMaritalStatus">Marital Status</label></td>
                        <td width="274" colspan="2">
                            <select id="cmbMaritalStatus" name="cmbMaritalStatus" class="formSelect" tabindex="7">
                                <option value="0">---Select---</option>
                                <option value="1">Married</option>
                                <option value="2">Single</option>
                                <option value="3">Sep / Divo</option>
                                <option value="4">Widowed</option>
                                <option value="5">Living Together</option>
                                <option value="6">Not Known</option>
                            </select></td>
                        <td><label for="cmbNationality">Nationality</label></td>
                        <td width="145">
                            <select id="cmbNationality" name="cmbNationality" class="formSelect" tabindex="8" onchange="showOtherNationallity(this.value);">
                                <option value="0">---Select---</option>
                                <option value="Srilankan">Srilankan</option>
                                <option value="Other">Other</option>
                            </select>    </td>
                        <td width="146"> <input type="text" name="txtOtherNationallity" id="txtOtherNationallity"  style="display:none" class="formInputText" /></td>
                    </tr>
                    <tr>
                        <td><label for="cmbEducation">Education Level</label></td>
                        <td width="136">
                            <select id="cmbEducation" name="cmbEducation" class="formSelect" tabindex="9" onchange="showOtherEducation(this.value);">
                                <option value="0">---Select---</option>
                                <option value="1-5 Grade">1-5 Grade</option>
                                <option value="6-10 Grade">6-10 Grade</option>
                                <option value="G.C.E O/L">G.C.E O/L</option>
                                <option value="G.C.E A/L">G.C.E A/L</option>
                                <option value="No schooling / NA">No schooling / NA</option>
                                <option value="Other">Other</option>
                            </select>	</td>
                        <td width="136"><input type="text" name="txtOtherEducation" id="txtOtherEducation" style="display:none" class="formInputText"  /></td>
                        <td><label for="cmbOccupation">Occupation<span class="required">*</span></label></td>
                        <td width="145">
                            <select id="cmbOccupation" name="cmbOccupation" class="formSelect" tabindex="10" onchange="showOtherOccupation(this.value);">
                                <option value="0">---Select---</option>
                                <option value="CSW">CSW</option>
                                <option value="Student">Student</option>
                                <option value="Retired">Retired</option>
                                <option value="Unemployed">Unemployed</option>
                                <option value="Other">Other</option>
                            </select>                            </td>
                        <td width="146"><input type="text" name="txtOtherOccupation" id="txtOtherOccupation" class="formInputText" style="display:none;" />

                    </tr>
                    <tr>
                        <td>NIC /Passport No </td>
                        <td colspan="2"><input type="text" name="txtNic" id="txtNic" class="formInputText" tabindex="11"  value="" />&nbsp;</td>
                        <td>Comment</td>
                        <td colspan="2"><input type="text" name="txtComment" id="txtComment" class="formInputText" tabindex="12"  value="" /></td>
                    </tr>
                </table>
            </div>

            <div id="tabs-2">
                <table width="834" border="0">
                    <tr>
                        <td width="111"><label for="txtCurrentAddress">Current Address</label></td>
                        <td width="301"> <textarea name="txtCurrentAddress" id="txtCurrentAddress" rows="2" cols="30"  class="formTextArea" tabindex="13"   ></textarea></td>
                        <td width="99"><label for="txtTel1">Telephone 1</label></td>
                        <td colspan="4"><input type="text" name="txtTel1" id="txtTel1" class="formInputText" tabindex="14"  value="" /></td>
                    </tr>
                    <tr>
                        <td> <label for="txtPermanentAddress">Permanent Address</label></td>
                        <td width="301"><textarea name="txtPermanentAddress" id="txtPermanentAddress" rows="2" cols="30" tabindex="15" class="formTextArea"   ></textarea></td>
                        <td><label for="txtTel2">Telephone 2</label></td>
                        <td colspan="4"><input type="text" name="txtTel2" id="txtTel2" class="formInputText" tabindex="16"  value="" /></td>
                    </tr>
                    <tr>
                        <td><label for="cmbMaritalStatus"><label for="txtContactAddress">Contact Address</label></label></td>
                        <td width="301"><textarea name="txtContactAddress" id="txtContactAddress" rows="2" cols="30" tabindex="17" class="formTextArea"   ></textarea></td>
                        <td><label for="cmbNationality"><label for="txtMobile">Mobile</label></label></td>
                        <td colspan="4"> <input type="text" name="txtMobile" id="txtMobile" class="formInputText" tabindex="18"  value="" /></td>
                    </tr>
                    <tr>
                        <td><label for="txtEmail">Email</label></td>
                        <td width="301"><input type="text" name="txtEmail" id="txtEmail" class="formInputText" tabindex="19"  value="" /></td>
                        <td><label for="txtMoc">Mode of Contact</label></td>
                        <td width="55"><input type="radio"name="contactMode" id="optContactMode1" value="no"  class="formRadio" tabindex="20" onclick="changeContactMode();" />&nbsp;</td>
                        <td width="85"><label for="optContactMode1" class="optionlabel">No Contact</label></td>
                        <td width="30"><input type="radio"name="contactMode" id="optContactMode2" value="yes"  class="formRadio" tabindex="21" onclick="changeContactMode();" checked/>&nbsp;</td>
                        <td width="123"><label for="optContactMode2" class="optionlabel">Contact Through</label>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="4"> <div >
                                <label for="chkLetter" class="optionlabel">Letter</label>
                                <input type="checkbox" name="method[<?php echo 'letter'; ?>]" value="Letter" id="chkLetter" class="formCheckbox columncheckbox" tabindex="22" />

                                <label for="chkEmail" class="optionlabel">Email</label>
                                <input type="checkbox" name="method[<?php echo 'email'; ?>]" value="Email" id="chkEmail" class="formCheckbox columncheckbox" tabindex="23" />

                                <label for="chkPhone" class="optionlabel">Phone</label>
                                <input type="checkbox" name="method[<?php echo 'phone'; ?>]" value="Phone" id="chkPhone" class="formCheckbox columncheckbox" tabindex="24"/>

                                <label for="chkVisit" class="optionlabel">Visit</label>
                                <input type="checkbox" name="method[<?php echo 'visit'; ?>]" value="Visit" id="chkVisit" class="formCheckbox columncheckbox" tabindex="25" />
                            </div></td>
                    </tr>
                </table>
            </div>

            <div id="tabs-3">
                <table width="834" border="0">
                    <tr>
                        <td width="25"><input type="checkbox" name="reason[<?php echo 'opd'; ?>]" value="OPD" id="r1" /></td>
                        <td width="70">Ref.OPD</td>
                        <td width="144"> <input type="text" name="txtOpd" id="txtOpd" class="formInputText" tabindex="26"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'ward'; ?>]" value="Ward" id="r2" /></td>
                        <td width="70">Ref.Ward</td>
                        <td width="144"> <input type="text" name="txtWard" id="txtWard" class="formInputText" tabindex="27"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'gp'; ?>]" value="GP" id="r3"  /></td>
                        <td width="70">Ref.GP</td>
                        <td width="144"> <input type="text" name="txtGp" id="txtGp" class="formInputText" tabindex="28"  value="" /></td>
                    </tr>
                    <tr>
                        <td width="25"><input type="checkbox" name="reason[<?php echo 'court'; ?>]" value="Courts" id="r4" /></td>
                        <td width="70">Ref.Courts</td>
                        <td width="144"> <input type="text" name="txtCourt" id="txtCourt" class="formInputText" tabindex="29"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'bb'; ?>]" value="Blood Bank" id="r5" /></td>
                        <td width="70">Ref.Blood Bank</td>
                        <td width="144"> <input type="text" name="txtBlood" id="txtBlood" class="formInputText" tabindex="30"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'contact'; ?>]" value="Contact" id="r6"  /></td>
                        <td width="70">Contact</td>
                        <td width="144"> <input type="text" name="txtRContact" id="txtRContact" class="formInputText" tabindex="31"  value="" /></td>
                    </tr>
                    <tr>
                        <td width="25"><input type="checkbox" name="reason[<?php echo 'voluntary'; ?>]" value="Voluntary" id="r7" /></td>
                        <td width="70">Voluntary</td>
                        <td width="144"> <input type="text" name="txtVoluntary" id="txtVoluntary" class="formInputText" tabindex="32"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'clinic'; ?>]" value="Clinic Followup" id="r8" /></td>
                        <td width="70">Clinic Followup</td>
                        <td width="144"> <input type="text" name="txtClinic" id="txtClinic" class="formInputText" tabindex="33"  value="" /></td>

                        <td width="25"><input type="checkbox" name="reason[<?php echo 'other'; ?>]" value="Other" id="r9"  /></td>
                        <td width="70">Other</td>
                        <td width="144"> <input type="text" name="txtOther" id="txtOther" class="formInputText" tabindex="34"  value="" /></td>
                    </tr>
                </table>
            </div>          

        </form>
       
        <?php if(!empty($rights[$modNames['Registration']]['edit'])) { ?>
        <table border="0">
            <tr>
                <td width="342"></td>
                <td width="478" valign="top">
                    <div class="demo">
                        <button id="btnSave" style="height:25px;">Save</button>
                        <button id="btnClear" style="height:25px;">Clear</button>
                        <button id="btnBack">Back</button>
                    </div>
              </td>
            </tr>
        </table>
        <?php }?>
    </div>
    <br/>


</div>

<script type="text/javascript">

	jQuery.validator.addMethod(
        "customDateValidator",
         function(value, element) {
		// parseDate throws exception if the value is invalid
              try{jQuery.datepicker.parseDate( 'yy-mm-dd', value);return true;}
              catch(e){return false;}
             },
             "Please enter a valid date"
         );

    jQuery.validator.addMethod(
    "selectNone", 
    function(value, element) { 
        if (element.value == "0") 
        { 
            return false; 
        } 
        else return true; 
    }, 
    "Please select the patient category" 
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
    "Please select the gender"
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
    "Please select the occupation"
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

        //jQuery("#txtRegDate").rules("add", {customDateValidator:true});
        
        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('registration/showPatientList') ?>";
        });

        $(function() {
            $("button", ".demo").button();

        });

        //When click reset buton
        $("#btnClear").click(function() {
            document.forms[0].reset('');
        });

		
        $(function() {
            $("#txtDateOfBirth").datepicker({
                showOn: 'button',
                buttonImage: '/images/calendar.gif',
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                changeMonth: true,
                yearRange: '1930:2100'

            });
        });

        $(function() {
            $("#txtRegDate").datepicker({
                showOn: 'button',
                buttonImage: '/images/calendar.gif',
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                changeMonth: true,
                yearRange: '2000:2030'
            });
        });

    });
</script>