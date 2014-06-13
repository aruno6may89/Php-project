<?php include 'header.php';?>
<script type="text/javascript">
        $(document).ready(function(){
            $("#addNewContact").validate({
                debug:true,
                errorElement:'span',
                rules:
                {
                    fName:  {
                                            required: true,
                                            letterspace: true
                                        },
                                        lName: {
                                            required: true,
                                            letterspace: true
                                        },
                                        pEmail: {
                                            required: true,
                                            email: true
                                        },
                                        pPhone: {
                                            required: true,
                                            isdigit: true
                                        },
                                        wEmail: {
                                            email: true
                                        },
                                        wPhone: {
                                            isdigit: true
                                        },
                                        desi: {
                                            required: true
                                        }
                                        /*refby:  {
                                         required: true
                                         }*/
                                    },
                            messages:
                                    {
                                        fName: {
                                            required: 'Please Enter First Name',
                                            letterspace: 'only alphabets are allowed'
                                        },
                                        lName: {
                                            required: 'Please Enter Last Name',
                                            letterspace: 'only alphabets are allowed'
                                        },
                                        pEmail: {
                                            required: 'Please Enter Personal Email',
                                            email: 'Please Enter Valid Email'
                                        },
                                        pPhone: {
                                            required: 'Please Enter Personal Phone number',
                                            isdigit: 'Enter valid Personal Phone number'
                                        },
                                        wEmail: {
                                            email: 'Please Enter Valid Email'
                                        },
                                        wPhone: {
                                            isdigit: 'Enter valid Work Phone number'
                                        },
                                        desi: {
                                            required: 'Please Select Designation'
                                        },
                                        refby: {
                                            required: 'Please Enter Reffered by'
                                        }
                                    },
                errorPlacement: function(error, element) {
                        if(element.attr('id') === "fName") 
                        {
                            error.appendTo(".fName");
                        } 
                        else if(element.attr('id') === "lName") 
                        {
                            error.appendTo(".lName");
                        }
                        else if(element.attr('id') === "pEmail") 
                        {
                            error.appendTo(".pEmail");
                        }
                        else if(element.attr('id') === "pPhone") 
                        {
                            error.appendTo(".pPhone");
                        }
                        else if(element.attr('id') === "wEmail") 
                        {
                            error.appendTo(".wEmail");
                        }
                        else if(element.attr('id') === "wPhone") 
                        {
                            error.appendTo(".wPhone");
                        }
                        else if(element.attr('id') === "desi") 
                        {
                            error.appendTo(".desi");
                        }
                        else if(element.attr('id') === "refby") 
                        {
                            error.appendTo(".refby");
                        }
//                          else {
//                            error.appendTo(".errors");
//                        }
                    },
                    submitHandler: function(form) 
                    {
                        //$('#submit').click(function() {
			$('#addContact_submit').attr("disabled", "disabled");
                        $('#addContact_reset').attr("disabled", "disabled");
                        $.ajax({  
                            type    : "POST",  
                            url     : "<?php echo base_url()?>contacts/saveContact",  
                            data    : $('#addNewContact').serialize(),
                            dataType:'json',
                            success : function(responseText){
                                    $("#message").show(100);
                                    $('#addContact_submit').removeAttr('disabled');
                                    $('#addContact_reset').removeAttr('disabled');
                                    if(!responseText.hasError)
                                    {
                                        $('#status_message').html(responseText.status);
                                        $("#addNewContact")[0].reset();
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
        });
        </script>
             <!-- App center content start -->
	        <div class="container main new-requirement">
                    <div class="row" id="message" style="display: none">
                	<div class="span12">
                        <div class="alert alert-info">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <strong></strong> <span id="status_message"></span>
                        </div>
                    </div>
                    </div>
	        	<div class="row heading">
                	<div class="span8">
                    	<h3>Add New Contact :</h3>
                    </div>
                    <!--<div class="span1">
                    	<button class="cus-buttons">S</button>
                    </div>-->
                    <hr>
                </div>
                    <form class="newcontact" name="addNewContact" id="addNewContact">
	                    <div class="row">
                		<div class="span6">
                                <label for="fName">First name<span class="required">*</span></label>
                                <input type="text" placeholder="" name="fName" id="fName">
                                <span class="b-error fName"></span>
                            </div>
                            <div class="span6">
                                <label for="lName">Last name<span class="required">*</span></label>
                                <input type="text" placeholder="" name="lName" id="lName">
                                <span class="b-error lName"></span>
                            </div>
                        </div>
	                    <div class="row">
                		<div class="span6">
                                <label for="pEmail">Personal Email<span class="required">*</span></label>
                                <input type="text" placeholder="" name="pEmail" id="pEmail">
                                <span class="b-error pEmail"></span>
                            </div>
                            <div class="span6">
                                <label for="pPhone">Personal Phone<span class="required">*</span></label>
                                <input type="text" placeholder="" name="pPhone" id="pPhone">
                                <span class="b-error pPhone"></span>
                            </div> 
                        </div>
	                    <div class="row">
                		<div class="span6">
                                <label for="wEmail">Work Email</label>
                                <input type="text" placeholder="" name="wEmail" id="wEmail">
                                <span class="b-error wEmail"></span>
                            </div>
                            <div class="span6">
                                <label for="wPhone">Work Phone</label>
                                <input type="text" placeholder="" name="wPhone" id="wPhone">
                                <span class="b-error wPhone"></span>
                            </div>
                        </div>
	                    <div class="row">
                        	<div class="span6">
                            	<label for="desi">Designation<span class="required">*</span></label>
                                <select class="text" name="desi" id="desi">
                                        <option value="">-- Select --</option>
                                        <?php foreach($this->Designation as $row) { ?>
                                            <option value="<?=$row->name?>"><?=$row->name?></option>
                                        <?php } ?>
                                </select>
                                <span class="b-error desi"></span>
                            </div>
                            <div class="span6">
                            	<label for="refby">Referred By</label>
                                <input type="text" placeholder="" name="refby" id="refby">
                                <span class="b-error refby"></span>
                            </div>
                        </div>
	                    <div class="row">
	                    	<div class="span12 center buttons">
	                    		<button class="" type="reset">Reset</button>
  					<button class="cus-buttons" id='addcontact_submit' type="submit">Submit New Contact</button>
                                        <input type="hidden" name="contact_type" value="General">
                                        
	                    	</div>
	                    </div>
	                </form>
            </div><!-- cotainer ends -->
<?php include 'footer.php'; ?>
