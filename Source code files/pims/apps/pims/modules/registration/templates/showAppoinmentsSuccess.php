<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Clinic Appointments</a></li>
        </ul>
        <br />
        <form name="frmAppoinment" id="frmAppoinment" method="post" action="<?php echo url_for('registration/viewAppoinmentDetails') ?>/">
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
        $("#frmAppoinment").validate({

            rules: {

                txtDate: {
                    required: true
                    
                },

                cmbUserGroup: {
                    selectNone: true
                }


            },
            messages: {
                txtDate: "Date is required"

            }
        });

        // When click edit button
        $("#btnShow").click(function() {
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
                yearRange: '1950:2100'
            });
        });
    });
</script>