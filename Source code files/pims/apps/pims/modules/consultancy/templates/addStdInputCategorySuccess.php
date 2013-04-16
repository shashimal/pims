<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">STD Input Categoy</a></li>
        </ul>
        <br />
       <form name="frmInputCategory" id="frmInputCategory" method="post" action="<?php echo url_for('consultancy/saveStdInputCategory') ?>/">
            <table width="487" >
                <tr>
                    <td width="125">Category Name<span class="required">*</span></td>
                    <td width="350">  <input type="text" id="txtInputCategory" name="txtInputCategory"  tabindex="1" /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name="txtDescription" id="txtDescription" rows="3" cols="30" tabindex="2"></textarea></td>
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
        $("#frmInputCategory").validate({

            rules: {
                txtInputCategory: { required: true }
            },
            messages: {
                txtInputCategory: "Category name is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmInputCategory').submit();
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