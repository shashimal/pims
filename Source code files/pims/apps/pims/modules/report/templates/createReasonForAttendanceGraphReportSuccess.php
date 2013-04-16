<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Reasons For Attendance</a></li>
        </ul>
        <br />
        <form name="frmAppoinment" id="frmAppoinment" method="post" action="<?php echo url_for('report/viewReasonForAttendanceGraph') ?>/">
            <table width="487" >
                <tr>
                    <td width="125">Year<span class="required">*</span></td>                    
                    <td>
                   <?php 
						$currentYear = date("Y");				
					
					?>
					<select name="txtYear" id ="txtYear" >
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
					
					</select>
					</td>
                   
                </tr>
                </table>
                    </form>
                    <br />
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
                            }
                            else return true;
                        },
                        "Please select an occupation."
                    );


                        $(document).ready(function() {

                            //Validate the form
                            $("#frmAppoinment").validate({

                                rules: {

                                    txtYear: {
                                        required: true

                                    }


                                },
                                messages: {
                                    txtFromDate: "Date is required",
                                    txtToDate: "Date is required"

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
                                $("#txtYear").datepicker({
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