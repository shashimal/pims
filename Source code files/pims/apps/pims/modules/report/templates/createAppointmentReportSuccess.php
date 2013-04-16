<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Clinic Appointments Report</a></li>
        </ul>
        <br />
       <form name="frmAppoinment" id="frmAppoinment" method="post" action="<?php echo url_for('report/viewAppointmentReport') ?>/">
            <table width="487" >
                <tr>
                    <td width="125">Date<span class="required">*</span></td>
                    <td width="350">  <input type="text" id="txtDate" name="txtDate"  tabindex="1" /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><select id="cmbGender" name="cmbGender" tabindex="2" >
                <option value="1">Male</option>
                <option value="2">Female</option>
            </select></td>
                </tr>
                
            </table>
        </form>
        <table border="0">
            <tr>
                <td width="97"></td>
                <td width="29"></td>
                <td width="472" valign="top">
                    <div class="demo">
                        <button id="btnView" style="height:25px;">View</button>                        
                        <button id="btnClear">Clear</button>
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
        $("#frmAppoinment").validate({

            rules: {

                txtDate: {
                    required: true

                }              


            },
            messages: {
                txtDate: "Date is required"

            }
        });

        // When click edit button
        $("#btnView").click(function() {
            $('#frmAppoinment').submit();
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
            $("#tabs").tabs({ selected: 0 });
        });

 		$(function() {
            $("button", ".demo").button();

        });
        
        $(function() {
            $("#txtDate").datepicker({
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