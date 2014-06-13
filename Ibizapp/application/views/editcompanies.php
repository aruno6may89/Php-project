<?php include 'header.php'; ?>
<script type="text/javascript">
$(document).ready(function(){
            $("#editCompany").validate({
                debug:true,
                errorElement:'span',
                rules:
                {
                    cname:  {
                                required: true
                            },
                    ctype:  {
                                required: true
                            },
                    cstatus:  {
                                required: true
                            },
                    /*sdate:  {
                                required: true
                            },
                    ndasign:  {
                                required: true
                            },
                    add1:  {
                                required: true
                            },
                    add2:  {
                                required: true
                            },
                    city:  {
                                required: true
                            },
                    state:  {
                                required: true
                            },*/
                    zip:  {
                                number: true
                            },
                    /*signdate:  {
                                required: true
                            },
                    ndaby:  {
                                required: true
                            },
                    ptitle:  {
                                required: true
                            }*/
                    wurl:  {
                                urlvalidation: true
                            }
                },
                messages:
                {
                    cname:  {
                                required: 'Please Enter Company Name'
                            },
                    ctype:  {
                                required: 'Please Select Company Type'
                            },
                    sdate:  {
                                required: 'Please Select Nature of Business'
                            },
                    cstatus:  {
                                required: 'Please Select Company Status'
                            },
                    ndasign:  {
                                required: 'Please Select NDA signed'
                            },
                    add1:  {
                                required: 'Please Enter Address'
                            },
                    add2:  {
                                required: 'Please Enter Address'
                            },
                    city:  {
                                required: 'Please Enter City'
                            },
                    state:  {
                                required: 'Please Select State'
                            },
                    zip:  {
                                number: 'Please Enter Numeric value'
                            },
                    signdate:  {
                                required:  'Please Enter NDA Signed date'
                            },
                    ndaby:  {
                                required: 'Please Enter NDA Signed by'
                            },
                    ptitle:  {
                                required: 'Please Enter Position Title'
                            },
                    wurl:  {
                                urlvalidation: 'Please valid Website URL'
                            }
                },
                errorPlacement: function(error, element) {
                        if(element.attr('id') === "cname") 
                        {
                            error.appendTo(".cname");
                        } 
                        else if(element.attr('id') === "ctype") 
                        {
                            error.appendTo(".ctype");
                        }
                        else if(element.attr('id') === "sdate") 
                        {
                            error.appendTo(".sdate");
                        }
                        else if(element.attr('id') === "cstatus") 
                        {
                            error.appendTo(".cstatus");
                        }
                        else if(element.attr('id') === "ndasign") 
                        {
                            error.appendTo(".ndasign");
                        }
                        else if(element.attr('id') === "add1") 
                        {
                            error.appendTo(".add1");
                        }
                        else if(element.attr('id') === "add2") 
                        {
                            error.appendTo(".add2");
                        }
                        else if(element.attr('id') === "city") 
                        {
                            error.appendTo(".city");
                        }
                        else if(element.attr('id') === "state") 
                        {
                            error.appendTo(".state");
                        }
                        else if(element.attr('id') === "zip") 
                        {
                            error.appendTo(".zip");
                        }
                        else if(element.attr('id') === "signdate") 
                        {
                            error.appendTo(".signdate");
                        }
                        else if(element.attr('id') === "ndaby") 
                        {
                            error.appendTo(".ndaby");
                        }
                        else if(element.attr('id') === "ptitle") 
                        {
                            error.appendTo(".ptitle");
                        }
                        else if(element.attr('id') === "wurl") 
                        {
                            error.appendTo(".wurl");
                        }
//                        else {
//                            error.appendTo(".errors");
//                        }
                    },
                    submitHandler: function(form) 
                    {
                        //$('#submit').click(function() {
			$('#addcom_submit').attr("disabled", "disabled");
                        $('#addcom_reset').attr("disabled", "disabled");
                        $.ajax({  
                            type    : "POST",  
                            url     : "<?=BASE_URL?>/company/updateCompany",  
                            data    : $('#editCompany').serialize(),
                            dataType:'json',
                            success : function(responseText){
                                    $("#message").show(100);
                                    $('#addcom_submit').removeAttr('disabled');
                                    $('#addcom_reset').removeAttr('disabled');
                                    if(!responseText.hasError)
                                    {
                                            $('#status_message').html(responseText.status);
                                            //$("#editCompany")[0].reset();
                                    }
                                    else
                                    {
                                            $('#status_message').html(responseText.errors);
                                    }
                            }
                          });
						return false;
                    }
            });
        });
</script>
             <!-- App center content start -->
	        <div class="container main Candidates">
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
                    	<h3>Edit Company</h3>
                    </div>
                    <hr>
                </div>
                <div class="notifications" style="display: none">
                        <a href="#" title="close Notification">X</a>
                        <div id='message'>
                            
                        </div>
                    </div>
               <div  id="table-data-container">
                        <form action='' id='editCompany' class="new-company" method='POST'>
	                    <div class="row">
	                    	<div class="span6">
                                    <label for="cname">Company Name <span class="required">*</span></label>
                                    <input type="text" name="cname" id="cname" value="<?php if(isset($company_details->Name)) echo $company_details->Name; ?>"/>
                                    <span class="b-error cname"></span>
	                    	</div>
	                    	<div class="span6">
                                    <label for="ctype">Company Type <span class="required">*</span></label>
                                    <select class="text" name="ctype" id="ctype">
                                        <option value="">-- Select --</option>
                                        <?php foreach($this->companyType as $row) { ?>
                                            <option value="<?=$row->name?>" <?php if($company_details->Company_type==$row->name) echo "SELECTED"?>><?=$row->name?></option>
                                        <?php } ?>
                                    </select>
                                    <!--<input type="text" name="ctype" id="ctype"/>-->
                                    <span class="b-error ctype"></span>
	                    	</div>
	                    </div>
	                    <div class="row">
	                    	<div class="span6">
                                    <label for="sdate">Nature of Business</label>
                                    <select class="text" name="sdate" id="sdate">
                                        <option value="">-- Select --</option>
                                        <?php foreach($this->business as $row) { ?>
                                            <option value="<?=$row->name?>" <?php if(isset($company_details->Nature_Of_Business) && $company_details->Nature_Of_Business==$row->name) echo "SELECTED";?>><?=$row->name?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="b-error sdate"></span>
		                </div>
                                <?php
                            if($this->session->userdata('Role') == "Admin"||$this->session->userdata('Role') == "Recruiter_Manager")
                            {
                            ?>
                                ?>
                                <div class="span6">
                                    <label for="cstatus">Company Status <span class="required">*</span></label>
                                    <select class="text" name="cstatus" id="cstatus">
                                        <option value="">-- Select --</option>
                                        <?php foreach($this->comp_status as $row) { ?>
                                            <option value="<?=$row->Status?>" <?php if(isset($company_details->status) && $company_details->status==$row->Status) echo "SELECTED";?>><?=$row->Short_Name?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="b-error cstatus"></span>
                                </div>
                                <?php
                            }
                           ?>
                            </div>
		            <div class="row">
                                <div class="span6">
			        <label for="ndasign">NDA Signed</label>
                                <select class="text" name="ndasign" id="ndasign">
                                    <option value="">-- Select --</option>
                                    <option value="Yes" <?php if(isset($company_details->NDA_Signed) && $company_details->NDA_Signed=="Yes")echo "SELECTED";?>>Yes</option>
                                    <option value="No" <?php if(isset($company_details->NDA_Signed) && $company_details->NDA_Signed=="No")echo "SELECTED";?>>No</option>
                                </select>
                                <span class="b-error ndasign"></span>
                                </div>
                            <div class="span6"> 
                                <label for="signdate">Signed Date</label>
<!--			        <input type="date" name="signdate" id="signdate" value="<?php if(isset($company_details->Signed_Date)) echo $company_details->Signed_Date; ?>"/>-->
                                <input type="text" name="signdate" class="datepicker bdate" id="signdate" value="<?php if(isset($company_details->Signed_Date)) echo date('d-m-Y',  strtotime ($company_details->Signed_Date)); ?>" data-date-format="dd-mm-yyyy" readonly/>
                                <span class="b-error signdate"></span>
                            </div>
                        </div>
		        <div class="row">
                            <div class="span6">
                                <label for="ndaby">NDA Signed By</label>
                                <input type="text" name="ndaby" id="ndaby" value="<?php if(isset($company_details->NDA_Signed_By)) echo $company_details->NDA_Signed_By; ?>"/>
                                <span class="b-error ndaby"></span>
		            </div>
		            <div class="span6">
                                <label for="ptitle">Position Title</label>
		                <input type="text" name="ptitle" id="ptitle" value="<?php if(isset($company_details->Position_Title)) echo $company_details->Position_Title; ?>"/>
                                <span class="b-error ptitle"></span>
                            </div>
                        </div>
		        <div class="row">
                            <div class="span6">
                                <label for="add1">Address1</label>
		                <input type="text" name="add1" id="add1" value="<?=$address_details->Address_1?>"/>
                                <span class="b-error add1"></span>
                            </div>
                            <div class="span6">
                                <label for="add2">Address2</label>
		                <input type="text" name="add2" id="add2" value="<?=$address_details->Address_2?>"/>
                                <span class="b-error add2"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span6">
                                <label for="city">City</label>
		                <input type="text" name="city" id="city" value="<?=$address_details->City?>"/>
                                <span class="b-error city"></span>
                            </div>
		            <div class="span6">
                                <label for="state">State</label>
		                <select class="text" name="state" id="state">
                                    <option value="">--select--</option>
                                    <?php foreach($this->statesAbbr as $row) {
                                        if($row->name!="any where")
                                        {
                                        ?>
                                    <option value="<?=$row->name?>" <?php if($address_details->State==$row->name) echo "SELECTED";?>><?=$row->name?></option>
                                        <?php }} ?>
				</select>
                                <span class="b-error state"></span>
                            </div>
                        </div>
		        <div class="row">
                            <div class="span6">
                                <label for="zip">Zip</label>
		                <input type="text" name="zip" id="zip" value="<?=$address_details->Zip?>"/>
                                <span class="b-error zip"></span>
                            </div>
		            <div class="span6">
                                <label for="wurl">Website URL</label>
		                <input type="text" name="wurl" id="wurl" value="<?=$address_details->Url?>"/>
                                <span class="b-error wurl"></span>
                            </div>
                            <input type="hidden" name="company_id" id="company_id" value="<?php echo $company_details->Company_ID; ?>"/>
                            <input type="hidden" name="address_id" id="address_id" value="<?php echo $address_details->Address_Id; ?>"/>
                        </div>
                        <div class="row">
                            <div class="span12 center buttons" >
                                <button class="cus-buttons" id="addcom_submit" type="submit">Update Company</button>
                                <input type="hidden" name="oldStatus" value="<?=$company_details->status?>">
                            </div>
                        </div>
                    </form>
                   <div id="date_picker">
                                    
                                </div>
                </div><!-- #table-data-container ends -->
	        </div><!-- cotainer ends -->
         </div><!-- .app-main ends -->
<?php include 'footer.php'; ?>
        