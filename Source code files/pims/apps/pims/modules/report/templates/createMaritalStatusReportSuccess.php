<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Marital Status Report</a></li>
        </ul>
        <br />
       <form name="frmMarital" id="frmMarital" method="post" action="<?php echo url_for('report/viewMaritalStatusReport') ?>/">
            <table width="487" >
                <tr>
                    <td width="125">Year<span class="required">*</span></td>
                    <td>
                   <?php 
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
					
					</select>
					</td>
                </tr>
                <tr>
                    <td>Quarter<span class="required">*</span></td>
                    <td><select id="cmbQuarter" name="cmbQuarter" tabindex="2" >
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
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
    function printpage() {
        window.print();
    }

    $(document).ready(function() {                    

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
		
		$(function() {
                $("button", ".demo").button();

            });

		 // When click edit button
        $("#btnView").click(function() {
            $('#frmMarital').submit();
        });
		
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('report/createAppointmentReport') ?>";
        });

        $("#btnClear").click(function() {
            document.forms[0].reset('');
        });

    });
</script>