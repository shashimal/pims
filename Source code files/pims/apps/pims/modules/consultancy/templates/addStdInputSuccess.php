<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">STD Input</a></li>
        </ul>
        <br />
        <form name="frmStdInput" id="frmStdInput" method="post" action="<?php echo url_for('consultancy/saveStdInput') ?>/">
            <table width="487" >
                <tr>
                    <td width="125">Category Name<span class="required">*</span></td>
                    <td width="350">  <select id="cmbInputCategory" name ="cmbInputCategory" >
                            <option value="0">Select Category</option>
                            <?php
                            foreach ($stdInputCategories as $stdInputCategory) {
                            ?>
                                <option value = "<?php echo $stdInputCategory['input_category_code']; ?>"><?php echo $stdInputCategory['name']; ?></option>
                            <?php
                            }
                            ?>
                        </select></td>
                </tr>
                <tr>
                    <td>Input Name<span class="required">*</span></td>
                    <td><input type="text" id="txtInputName" name="txtInputName"  tabindex="1" /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td> <textarea name="txtDescription" id="txtDescription" rows="3" cols="30" tabindex="2"  ></textarea></td>
                </tr>
                <tr>
                    <td>No Of Inputs <span class="required">*</span></td>
                    <td><input type="text" name="txtNoOfInput" id="txtNoOfInput" rows="3" cols="30" tabindex="3"  /></td>
                </tr>
                <tr>
                    <td>Gender <span class="required">*</span></td>
                    <td><select id="cmbSex" name ="cmbSex" >
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                            <option value="3">Both</option>
                        </select></td>
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

    jQuery.validator.addMethod(
    "selectNone",
    function(value, element) {
        if (element.value == "0")
        {
            return false;
        }
        else return true;
    },
    "Please select an category."
);

    $(document).ready(function() {

        //Validate the form
        $("#frmStdInput").validate({

            rules: {

                cmbInputCategory: {
                    selectNone: true
                },

                txtInputName: {
                    required: true
                },

                txtNoOfInput: {
                    required: true,
                    number: true
                    
                }
            },
            messages: {
                cmbInputCategory: "Category name is required",
                txtInputName: "Input name is required",
                txtNoOfInput: "Valid numeric value is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmStdInput').submit();
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('consultancy/showStdInputList') ?>";
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