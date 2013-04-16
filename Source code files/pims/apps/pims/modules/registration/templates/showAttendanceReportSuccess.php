<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Clinic Attendance</a></li>
        </ul>
        <br />
        <form name="frmAttendance" id="frmAttendance" method="post" action="<?php echo url_for('registration/viewAttendanceReport') ?>/">
            <table width="487" >
                <tr>
                    <td width="125">Date<span class="required">*</span></td>
                    <td width="350">  <input type="text" id="txtDate" name="txtDate"  tabindex="1"  readonly="readonly"/></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><select id="cmbGender" name="cmbGender" tabindex="2" >
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select></td>
                </tr>    
                <tr>
                    <td>Selection 1</td>
                    <td><select id="cmbSelection1" name="cmbSelection1" tabindex="3" >
                            <option value="1">Clinic Patient</option>
                            <option value="2">Commercial Workers</option>
                        </select></td>
                </tr> 
                
                 <tr>
                    <td>Selection 2</td>
                    <td><select id="cmbSelection2" name="cmbSelection2" tabindex="3" >
                    		<option value="1">All</option>
                            <option value="2">New Patient</option>
                            <option value="3">New Episode</option>
                        </select></td>
                </tr>              
            </table>
        </form>

        <table border="0">
            <tr>
                <td width="124"></td>
                <td width="350" valign="top">
                    <div class="demo">
                        <button id="btnShow" >View</button>
                        <button id="btnClear" >Clear</button>                       
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>


<script type="text/javascript">

    jQuery.validator.addMethod(
    "selectNone",
    function(value, element) {
        if (element.value == "0")
        {
            return false;
        }else { 
            return true ;
        }
    },
    "Please select an user group."
);


    $(document).ready(function() {

        //Validate the form
        $("#frmAttendance").validate({

            rules: {

                txtDate: {
                    required: true,date: true
                    
                },

                cmbUserGroup: {
                    selectNone: true
                }


            },
            messages: {
                txtDate: "Valid date is required"

            }
        });

        // When click edit button
        $("#btnShow").click(function() {
            $('#frmAttendance').submit();
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for(public_path('admin/showUserList/')) ?>";
        });

        //When click reset buton
        $("#btnClear").click(function() {
            document.forms[0].reset('');
        });
		
        $(function() {
            $("button", ".demo").button();

        });
		
        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
        $(function() {
            $("#txtDate").datepicker({
                showOn: 'button',
                buttonImage: '/images/calendar.gif',
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                changeMonth: true,
                yearRange: '1950:2100'
            });
        });
    });
</script>