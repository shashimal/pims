<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Trace Default Patients</a></li>
        </ul>
        <br />
          <div class="message">
        <?php if ($sf_user->hasFlash('notice')): ?>
            <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
        <?php endif ?> <?php if ($sf_user->hasFlash('error')): ?>
                <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
        <?php endif ?>
            </div>
        <form name="frmTracePatient" id="frmTracePatient" method="post" action="<?php echo url_for('consultancy/viewDefaultPatients') ?>/">
            <table width="487" >
                <tr>
                    <td width="125">From Date<span class="required">*</span></td>
                    <td width="350">  <input type="text" id="txtFromDate" name="txtFromDate"  tabindex="1"  readonly="readonly"/></td>
                </tr>
               <tr>
                    <td width="125">To Date<span class="required">*</span></td>
                    <td width="350">  <input type="text" id="txtToDate" name="txtToDate"  tabindex="2"  readonly="readonly"/></td>
                </tr>  
                <!--          
                <tr>
                    <td width="125">Delayed Days<span class="required">*</span></td>
                    <td width="350">  <input type="text" id="txtNo" name="txtNo"  tabindex="3"  /></td>
                </tr>     
                 --> 
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
        $("#frmTracePatient").validate({

            rules: {

        	txtFromDate: {
                    required: true
                    
                },

                txtToDate: {
                	required: true
                }


            },
            messages: {
            	txtFromDate: "From date is required",
            	txtToDate: "To date is required"

            }
        });

        // When click edit button
        $("#btnShow").click(function() {
            $('#frmTracePatient').submit();
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
            $("#txtFromDate").datepicker({
                showOn: 'button',
                buttonImage: '/images/calendar.gif',
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                changeMonth: true,
                yearRange: '2000:2010'
            });
        });

        $(function() {
            $("#txtToDate").datepicker({
                showOn: 'button',
                buttonImage: '/images/calendar.gif',
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                changeMonth: true,
                yearRange: '2000:2010'
            });
        });
    });
</script>