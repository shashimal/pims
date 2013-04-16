<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Contact Details</a></li>
        </ul>
        <br />
       <form name="frmContact" id="frmContact" method="post" action="<?php echo url_for('consultancy/saveContact') ?>/">
       <input type="hidden" name="pno" id="pno" value="<?php echo $patientNo;?>">
            <table width="487" >
                <tr>
                    <td width="125">Slip No<span class="required">*</span></td>
                    <td width="350">  <input type="text" id="txtSlipNo" name="txtSlipNo"  tabindex="1" /></td>
                </tr>
                <tr>
                    <td>Contact<span class="required">*</span></td>
                    <td><textarea name="txtContactDet" id="txtContactDet" rows="3" cols="30" tabindex="2"></textarea></td>
                </tr>                
            </table>
        </form>
        <table border="0">
			<tr>
				<td width="127"></td>
			    <td width="350" valign="top">
				<div class="demo">
					<button id="btnSave">Save</button>
					<button id="btnClear">Clear</button>
					<button id="btnBack">Back</button>
				</div>
				</td>
			</tr>
		</table>
    </div>
</div>


<script type="text/javascript">

    $(document).ready(function() {

        //Validate the form
        $("#frmContact").validate({

            rules: {
        	txtSlipNo: { 
        		required: true 
        	},
        	txtContactDet: { 
        		required: true 
        	}
            },
            messages: {
            	txtSlipNo: "Slip number is required",
            	txtContactDet: "Contact is required",
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmContact').submit();
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('consultancy/showStdInputCategoryList') ?>";
        });

        $(function() {
			$("button", ".demo").button();	
		
		});
		
		$(function() {
            $("#tabs").tabs({ selected: 0 });
        });
        //When click reset buton
        $("#btnClear").click(function() {
            document.forms[0].reset('');
        });
        
    });
</script>