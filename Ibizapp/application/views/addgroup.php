<?php include 'header.php';?>
<link rel="stylesheet" href="<?=CSS_DIR?>multi-select.css">
<script src="<?=JS_DIR?>vendor/jquery.multi-select.js"></script>
<script>
	$(document).ready(function(){
	$("#addNewGroup").validate({
                debug:true,
                errorElement:'span',
                rules:
                {
                    gName:  {
                                required: true
                            },
                    "myselect[]": "required"
                            
                },
                messages:
                {
                    gName:  {
                                required: 'Enter Group Name'
                            },
                    "myselect[]": "Select Emails to add"
                },
                errorPlacement: function(error, element) {
                        if(element.attr('id') === "gName") {
                            error.appendTo(".gname");
                            //error.insertAfter(".chosen-container");
                        } else if(element.attr('id') === "myselect") {
                            error.appendTo(".myselect");
                            //error.insertAfter(".chosen-container");
                        }
//                        else {
//                            error.appendTo(".errors");
//                        }
                    },
                    submitHandler: function(form) 
                    {
                        //$('#submit').click(function() {
						$('#addgroup_submit').attr("disabled", "disabled");
                        $.ajax({  
                            type    : "POST",  
                            url     : "<?php echo base_url()?>Contacts/saveGroup",  
                            data    : $('#addNewGroup').serialize(),
                            dataType:'json',
                            success : function(responseText){
                                    $(".notifications").show(100);
                                    $('#addgroup_submit').removeAttr('disabled');
                                    if(!responseText.hasError)
                                    {
                                        $('#status_message').html(responseText.status);
                                        $("#addNewCandidate")[0].reset();
                                    }
                                    else
                                    {
                                        $('#status_message').html(responseText.errors);
                                    }
                            }
                          //  });
							
                        });
						return false;
                    }
            });
		$('#myselect').multiSelect({
			selectableHeader: "<div class='custom-header'>Selectable items</div><input type='text' class='search-input' autocomplete='off' placeholder='Enter Search term'>",
			selectionHeader: "<div class='custom-header'>Selection items</div><input type='text' class='search-input' autocomplete='off' placeholder='Enter Search term'>"
		});
});
</script>
             <!-- App center content start -->
	        <div class="container main new-requirement">
				<div class="row" id="message" style="display: none">
					<div class="span12">
						<div class="alert alert-info">
							<button type="button" class="close" data-dismiss="alert">Ã—</button>
							<strong></strong> <span id="status_message"></span>
						</div>
				</div>
				</div>
	        	<div class="row heading">
                	<div class="span8">
                    	<h3>Create New Group</h3>
                    </div>
                    <!--<div class="span1">
                    	<button class="cus-buttons">S</button>
                    </div>-->
                    <hr>
                </div>
                <form class="newgroup" id="addNewGroup" action="" method="POST">
	                    <div class="row">
                			<div class="span6">
                                <label for="gName">Group name<span class="required">*</span></label>
                                <input type="text" placeholder="" name="gName" id="gName">
                                <span class="help-block">Some error goes here</span>
                            </div>
                        </div>
	                    <div class="row">
                        	<div class="span12">
                            	<select multiple="multiple" class="multi-select" id="myselect" name="myselect[]">
                                  <?php foreach($contact_display_details as $row) { ?>
									  <option value="<?=$row->Reporting_PersonID?>">
									  <?php echo $row->First_Name.' '.$row->Last_Name.'('.$row->Email1.') '.$row->companyname; ?>
									  </option>
								  <?php } ?>
                                </select>
                            </div>
                        </div>
	                    <div class="row">
	                    	<div class="span12 center buttons">
	                    		<button class="" id="addgroup_cancel" type="reset">Cancel</button>
								<button class="cus-buttons" id="addgroup_submit" type="submit">Submit New Group</button>
	                    	</div>
	                    </div>
	                </form>
            </div><!-- cotainer ends -->
 <?php include 'footer.php'; ?>       