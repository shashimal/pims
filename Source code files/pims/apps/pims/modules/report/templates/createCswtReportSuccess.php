<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Commercial Sex Workers Report </a></li>
        </ul>
        <br />
       <form name="frmAppoinment" id="frmAppoinment" method="post" action="<?php echo url_for('report/viewCswReport') ?>/">
            <table width="487" >
                <tr>
                    <td width="125">Year<span class="required">*</span></td>
                    <td width="350"> <?php 
						$currentYear = date("Y");				
					
					?>
					<select name="cmbYear" id ="cmbYear" >
					<?php 
						for($a=2000; $a <=2050; $a++) {
						
						if($a == $currentYear) {
							$selected = "selected='selected'";
						}else {
							$selected = '';
						}
					?>
						<option value="<?php echo $a;?>" <?php echo $selected; ?> ><?php echo $a;?></option>
					<?php 
					}
					?>
					
					</select>	</td>
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
        }
        else return true;
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
                dateFormat: 'yy-mm-dd'
            });
        });
    });
</script>