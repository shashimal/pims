<div id="tabs" class="div-tabs">
    <div >

        <ul>
            <li><a href="#tabs-1">Interview and Contact Tracing</a></li>
        </ul>
        <div class="message">
            <?php if ($sf_user->hasFlash('notice')): ?>
                <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
            <?php endif ?> <?php if ($sf_user->hasFlash('error')): ?>
                    <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
            <?php endif ?>
                </div>
                <br />
                <form name="frmSearchBox" id="frmSearchBox" method="post" action="<?php echo url_for('consultancy/viewTracedPatient') ?>/">
            <input type="hidden" name="mode" value="search" />
            <table width="487" >
                <tr>
                    <td width="125">Patient No<span class="required">*</span></td>
                    <td width="350"> <input type="text" size="20" name="pno" id="pno" value=""   />   </td>
                </tr>               

            </table>

        </form>
        <table border="0">
            <tr>
                <td width="124"></td>
                <td width="350" valign="top">
                    <div class="demo">
                        <button id="btnSearch" >Search</button>
                        <button id="btnClear" >Clear</button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<br/>
<div id="tabs1" class="div-tabs" style="width:930px;visibility: hidden;" >
    <ul>
        <li><a href="#tabs-1">Patient Details</a></li>
    </ul>
<div id="patintInfo"></div>
    
</div>


<script type="text/javascript"><!--

    $(document).ready(function() {

        //Validate the form
        $("#frmSearchBox").validate({

            rules: {
        	pno: { required: true }
            },
            messages: {
            	pno: "Patient no is required"
            }
        });
        

        $('#btnSearch').click(function(){                    
			//url = '<?php echo url_for('consultancy/viewPatient'); ?>/pno/' + $('#txtPatientNo').val();
			//$.getJSON(url, function(dates) {
		   // 	populateDateSelector($('#patintInfo'), dates);
	           // loadEndDate_Regular();
			//});

			//document.getElementById('tabs1').style.visibility = 'visible';
        	$('#frmSearchBox').submit();
		});

        function populateDateSelector(dateSelector, dates) {
    	    var html = '';
    	    //html += '<div id="tabs1" class="div-tabs" style="width:960px">';
    	   // html += '<ul><li><a href="#tabs-1">Episodes History</a></li></ul>';
    	    html += '<table>';
    	    html += '<thead>';
    	    html += '<th scope="col"   class="normal-list-head">Patient No</th>';
    	    html += '<th scope="col"   class="normal-list-head">First Name</th>';
    	    html += '<th scope="col"   class="normal-list-head">Last Name</th>';
    	    html += '<th scope="col"   class="normal-list-head">Current Address</th>';
    	    html += '<th scope="col"   class="normal-list-head">Permanent Address</th>';
    	    html += '<th scope="col"   class="normal-list-head">Contact Address</th>';
    	    html += '<th scope="col"   class="normal-list-head">Sex</th>';    	    
    	    html += '</thead>';
    	    html += '<tr>';
    	    $.each (dates, function (index, date) {
                html += '<td class="normal-lsit-data">' + date + '</td>';
            });
    	    html += '</tr>';
    	    html += '</table>';
            dateSelector.html(html);
    	}
        
        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });

        $(function() {
            $("button", ".demo").button();

        });

        $(function() {
            $("#tabs1").tabs({ selected: 0 });
        });
        
        //When click reset buton
        $("#btnClear").click(function() {
            document.forms[0].reset('');
        });

    });
--></script>