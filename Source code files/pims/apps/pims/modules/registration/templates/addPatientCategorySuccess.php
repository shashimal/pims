<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Patient Category</a></li>
        </ul>
        <br />
        <form name="frmPatientCategory" id="frmPatientCategory" method="post" action="<?php echo url_for('registration/savePatientCategory') ?>/">
            <table width="487" >
                <tr>
                    <td width="125">Category Name<span class="required">*</span></td>
                    <td width="350"> <input type="text" id="txtPatientCategory" name="txtPatientCategory"  tabindex="1" /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name="txtDescription" id="txtDescription" rows="3" cols="30" tabindex="2"   ></textarea></td>
                </tr>               
            </table>
        </form>
         <table border="0">
            <tr>
                <td width="124"></td>
                <td width="350" valign="top">
                    <div class="demo">
                        <button id="btnSave" style="height:25px;">Save</button>
                        <button id="btnClear" style="height:25px;">Clear</button>
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
        $("#frmPatientCategory").validate({

            rules: {
                txtPatientCategory: { required: true }
            },
            messages: {
                 txtPatientCategory: "Category name is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmPatientCategory').submit();
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('registration/showPatientCategoryList') ?>";
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