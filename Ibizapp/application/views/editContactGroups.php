<?php include 'header.php';?>
<link rel="stylesheet" href="<?=CSS_DIR?>multi-select.css">
<script src="<?=JS_DIR?>vendor/jquery.multi-select.js"></script>
<script>
	$(document).ready(function(){
	$("#editGropuContact").validate({
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
                            url     : "<?php echo base_url()?>Contacts/updateGroup",  
                            data    : $('#editGropuContact').serialize(),
                            dataType:'json',
                            success : function(responseText){
                                    $(".notifications").show(100);
                                    $('#addgroup_submit').removeAttr('disabled');
                                    if(!responseText.hasError)
                                    {
                                        $('#status_message').html(responseText.status);
                                        $("#editGropuContact")[0].reset();
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
	        <div class="container main contacts">
				<div id="error"><?=$error_msg?></div>
	            <div class="row heading">
                	<div class="span8">
                    	<h3>Contact Groups</h3>
                    </div>
                    <div class="span4 top-link-buttons">
                    	<!--<a href="newgroup.html" class="cus-buttons"><b>+</b> Create New Group</a>-->
                    </div>
                    <hr>
                </div>
                <div class="row action-buttons">
                	<!--<div class="span1">
                    	<div class="inner">
                        	<label><input type="checkbox" id="selectAll"> All</label>
                        </div>
                    </div>-->
                    <div class="span2">
                    	<div  class="inner">
							<a href="<?=BASE_URL?>/Contacts/deleteGroup/<?=$groupid?>">
                        	<button rel="tooltip" data-toggle="tooltip" title="Delete" class="delete"></button>
                        	</a>
                        </div>
                    </div>
                    <!--<div class="span3">
                    	<div  class="inner">
                        	<button rel="tooltip" data-toggle="tooltip" title="Add Notes" class="add-notes"></button>
                        </div>
                    </div>-->
                </div>
                <?php 
					$contact_details = array();
					if($group_contact_details)
					{
					foreach ($group_contact_details as $key => $value) {
					$contact_details[] = $value->Contact_Id;
					}	
				}?>
                <div class="group-list-container">
                	<div class="row">
                        <div class="span12">
                        	<h4><span>AC Infotech</span> Group edit</h4>
                            <form class="group-edit" id="editGropuContact" name="editGropuContact">
                            	<fieldset>
                                	<div>
                                    	<select multiple="multiple" class="multi-select" id="myselect" name="myselect[]">
                                  <?php foreach($contact_display_details as $row) { ?>
										<option value="<?=$row->Reporting_PersonID?>" <?php if (in_array($row->Reporting_PersonID, $contact_details)) echo "SELECTED"; ?> >
										<?php echo $row->First_Name.' '.$row->Last_Name.'('.$row->Email1.') '.$row->companyname; ?>
										</option>
								  <?php } ?>
										</select>
										<input type="hidden" id="groupid" name="groupid" value="<?=$groupid?>">
                                    </div>
                                    <div class="center">
                                    	<button type="button" class="">Cancel</button>
                                        <button type="submit" class="cus-buttons">Save Changes</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
	        </div><!-- cotainer ends -->
<?php include 'footer.php'; ?>
