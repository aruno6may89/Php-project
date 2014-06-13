<?php include 'header.php'; ?>
<script type="text/javascript">
        $(document).ready(function(){
            $("#editrequirement").validate({
                debug:true,
                errorElement:'span',
                rules:
                {
                    pid:  {
                                required: true
                            },
                    ptitle:  {
                                required: true
                            },
                    sdate:  {
                                required: true
                            },
                    cdate:  {
                                required: true
                            },
                    skill:  {
                                required: true
                            },
                    billrate:  {
                                required: true
                            },
                    tax_term:  {
                                required: true
                            },
                    loc:  {
                                required: true
                            },
                    status:  {
                                required: true
                            },
                    cemail:{
                                email: true
                    },
                    cname:  {
                                required: true
                            },
                        cp:{
                            required: true
                        },
                        cphone:{
                            required: true,
                            isdigit:true
                        },
                        cemail: {
                            required: true,
                            email: true
                        }
                    /*to_time_frame:  {
                                required: true
                            },
                    from_time_frame:  {
                                required: true
                            },
                    time_frame_desc:  {
                                required: true
                            }*/
                },
                messages:
                {
                    pid:  {
                                required: "Please Enter Position Id"
                            },
                    ptitle:  {
                                required: "Please Enter Position Title"
                            },
                    sdate:  {
                                required: "Please Enter Starting Date"
                            },
                    cdate:  {
                                required: "Please Enter Closing Date"
                            },
                    skill:  {
                                required: "Please Enter Skills Required"
                            },
                    billrate:  {
                                required: "Please Enter Billing Rate"
                            },
                    tax_term:  {
                                required: "Please Enter Tax Term"
                            },
                    loc:  {
                                required: "Please Enter Location"
                            },
                    status:  {
                                required: "Please Enter Status"
                            },
                    cname:  {
                                required: "Please Enter Company"
                            },
                    cp:{
                        required: "Please Enter contact Person"
                    },
                    cphone:{
                        required: "Please Enter Phone no",
                        isdigit:"Only Numbers , -,(,) are allowed"
                    },
                    cemail:{
                        required: "Please Enter Email",
                        email: "Please Enter Valid Email id"
                    },
                    to_time_frame:  {
                                required: "Please Enter Duration"
                            },
                    from_time_frame:  {
                                required: "Please Enter Duration"
                            },
                    time_frame_desc:  {
                                required: "Please Enter Duration"
                            }
                },
                errorPlacement: function(error, element) {
                        if(element.attr('id') === "pid") 
                        {
                            error.appendTo(".pid");
                        } 
                        else if(element.attr('id') === "ptitle") 
                        {
                            error.appendTo(".ptitle");
                        }
                        else if(element.attr('id') === "sdate") 
                        {
                            error.appendTo(".sdate");
                        }
                        else if(element.attr('id') === "cdate") 
                        {
                            error.appendTo(".cdate");
                        }
                        else if(element.attr('id') === "skill") 
                        {
                            error.appendTo(".skill");
                        }
                        else if(element.attr('id') === "cemail") 
                        {
                            error.appendTo(".cemail");
                        }
                        else if(element.attr('id') === "billrate") 
                        {
                            error.appendTo(".billrate");
                        }
                        else if(element.attr('id') === "tax_term") 
                        {
                            error.appendTo(".tax_term");
                        }
                        else if(element.attr('id') === "loc") 
                        {
                            error.appendTo(".loc");
                        }
                        else if(element.attr('id') === "status") 
                        {
                            error.appendTo(".status");
                        }
                        else if(element.attr('id') === "cname") 
                        {
                            error.appendTo(".cname");
                        }
                        else if (element.attr('id') === "cp")
                        {
                            error.appendTo(".cp");
                        }
                        else if (element.attr('id') === "cphone")
                        {
                            error.appendTo(".cphone");
                        }
                        else if(element.attr('id') === "to_time_frame") 
                        {
                            error.appendTo(".to_time_frame");
                        }
                        else if(element.attr('id') === "from_time_frame") 
                        {
                            error.appendTo(".from_time_frame");
                        }
                        else if(element.attr('id') === "time_frame_desc") 
                        {
                            error.appendTo(".time_frame_desc");
                        }
//                          else {
//                            error.appendTo(".errors");
//                        }
                    },
                    submitHandler: function(form) 
                    {
                        //$('#submit').click(function() {
			$('#addNewReq_submit').attr("disabled", "disabled");
                        $('#addNewReq_reset').attr("disabled", "disabled");
                        $.ajax({  
                            type    : "POST",  
                            url     : "<?php echo base_url()?>requirements/updateRequirement",  
                            data    : $('#editrequirement').serialize(),
                            dataType:'json',
                            success : function(responseText){
                                    $("#message").show(100);
                                    $('#addNewReq_submit').removeAttr('disabled');
                                    $('#addNewReq_reset').removeAttr('disabled');
                                    if(!responseText.hasError)
                                    {
                                            $('#status_message').html(responseText.status);
                                           // $("#editrequirement")[0].reset();
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
            <h3>Edit Requirement :</h3>
        </div>
        <!--<div class="span1">
            <button class="cus-buttons">S</button>
        </div>-->
        <hr>
    </div>
    <form class="editrequirement" id="editrequirement">
        <div class="row">
            <div class="span6">
                <label for="pid">Position ID<span class="required">*</span></label>
                <input type="text" name="pid" id="pid" value="<?=$requirement_details->Position_ID?>"/>
                <span class="b-error pid"></span>
            </div>
	    <div class="span6">
                <label for="ptitle">Position Title<span class="required">*</span></label>
	        <input type="text" name="ptitle" id="ptitle" value="<?=$requirement_details->Position_title?>"/>
                <span class="b-error ptitle"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="sdate">Start Date<span class="required">*</span></label>
<!--                <input type="date" name="sdate" id="sdate" value="<?php echo date('d-m-Y',strtotime($requirement_details->Start_Date));?>" data-date-format="dd-mm-yyyy"/>-->
                <input type="text" name="sdate" class="datepicker bdate" id="sdate"  data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y',strtotime($requirement_details->Start_Date));?>" readonly/>
                <span class="b-error sdate"></span>
            </div>
	    <div class="span6">
                <label for="cdate">Close Date<span class="required">*</span></label>
<!--                <input type="date" name="cdate" id="cdate" value="<?php date('d-m-Y',strtotime($requirement_details->Close_Date));?>" data-date-format="dd-mm-yyyy"/>-->
                
                <input type="text" name="cdate" class="datepicker bdate" id="cdate" data-date-format="dd-mm-yyyy"  value="<?php echo date('d-m-Y',strtotime($requirement_details->Close_Date));?>" readonly/>
                <span class="b-error cdate"></span>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <label for="posdes">Position Description</label>
                <textarea name="posdes" id="posdes"><?=$requirement_details->Requirement_Detail?></textarea>
                <span class="b-error posdes"></span>
            </div>
        </div>
	<div class="row">
            <div class="span12">
                <label for="skill">Skills<span class="required">*</span></label>
		<textarea name="skill" id="skill"><?=$requirement_details->Skills?></textarea>
                <span class="b-error skill"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="billrate">Billing Rate $<span class="required">*</span></label>
                <input type="text" name="billrate" id="billrate" value="<?=$requirement_details->billing_rate?>"/>
                <span class="b-error billrate"></span>
            </div>
	    <div class="span6">
                <label for="taxterm">Tax Term<span class="required">*</span></label>
                <select name="tax_term" id="tax_term">
                    <option value="">-Select-</option>
                    <?php foreach($this->taxTerm as $row) { ?>
                        <option value="<?=$row->name?>" <?php if($requirement_details->Tax_Term==$row->name) echo "SELECTED"?>><?=$row->name?></option>
                    <?php } ?>
		</select>
                <span class="b-error tax_term"></span>
            </div>
        </div>
	<div class="row">
            <div class="span6">
                <label for="loc">Location<span class="required">*</span></label>
                <input type="text" name="loc" id="loc" value="<?=$requirement_details->Location?>"/>
                <span class="b-error loc"></span>
            </div>
            <div class="span6">
                <label for="source">Source</label>
	        <input type="text" name="source" id="source" value="<?=$requirement_details->Source?>"/>
                <span class="b-error source"></span>
            </div>
        </div>
	<div class="row">
            <div class="span6">
                <label for="ecn">End Client Name</label>
	        <input type="text" name="ecn" id="ecn" value="<?=$requirement_details->End_Client_Name?>"/>
                <span class="b-error ecn"></span>
            </div>
	    <div class="span6">
                <label for="cp">Contact Person<span class="required">*</span></label>
	        <input type="text" name="cp" id="cp" value="<?=$requirement_details->contact_name?>"/>
                <span class="b-error cp"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="cphone">Contact Phone<span class="required">*</span></label>
	        <input type="tel" name="cphone" id="cphone" value="<?=$requirement_details->Work_Phone?>"/>
                <span class="b-error cphone"></span>
            </div>
            <div class="span6">
                <label for="cemail">Contact Email<span class="required">*</span></label>
	        <input name="cemail" id="cemail" value="<?=$requirement_details->Email1?>"/>
                <span class="b-error cemail"></span>
            </div>
        </div>
	<div class="row">
            <div class="span6">
                <label for="status">Status<span class="required">*</span></label>
	        <select name="status" id="status">
                    <option value="">-Select-</option>
                    <?php foreach($this->req_status as $row) { ?>
                        <option value="<?=$row->Status?>" <?php if($requirement_details->status == $row->Short_Name) echo "SELECTED";?>><?=$row->Short_Name?></option>
                    <?php } ?>
                </select>
                <span class="b-error status"></span>
            </div>
            <div class="span6">
                <label for="priority">Priority</label>
                <select name="priority" id="priority">
                    <option value="">-select-</option>
                    <?php foreach($this->priority as $row) { ?>
                        <option value="<?=$row->name?>" <?php if($requirement_details->priority==$row->name) echo "SELECTED"?>><?=$row->name?></option>
                    <?php } ?>
                </select>
                <span class="b-error priority"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="cname">Company Name<span class="required">*</span></label>
                <select name="cname" id="cname" class="chosen-select">
                    <option value="">-- Select --</option>
                    <?php foreach($this->company as $row) { ?>
                        <option value="<?=$row->Company_ID?>" <?php if($requirement_details->company_id == $row->Company_ID) echo "SELECTED";?>><?=$row->Name?></option>
                    <?php } ?>
		</select>
                <span class="b-error cname"></span>
            </div>
            <div class="span6">
                <label for="dure">Duration</label>
                <select name="to_time_frame" id="to_time_frame" style="width: 50px">
                    <?php foreach($this->timeFrame as $row) { ?>
                        <option value="<?=$row->name?>" <?php if($requirement_details->to_time_frame==$row->name) echo "SELECTED"?>><?=$row->name?></option>
                    <?php } ?>
                </select>
                <select name="from_time_frame" id="from_time_frame" style="width: 50px">
                    <?php foreach($this->timeFrame as $row) { ?>
                        <option value="<?=$row->name?>" <?php if($requirement_details->from_time_frame==$row->name) echo "SELECTED"?>><?=$row->name?></option>
                    <?php } ?>
                </select>
		<select name="time_frame_desc" id="time_frame_desc" style="width: 100px">
                    <option value="">select</option>
                    <?php foreach($this->timeFrameDesc as $row) { ?>
                        <option value="<?=$row->name?>" <?php if($requirement_details->time_frame_desc==$row->name) echo "SELECTED"?>><?=$row->name?></option>
                    <?php } ?>
                </select>
                <span class="b-error to_time_frame"></span>
                <span class="b-error from_time_frame"></span>
                <span class="b-error time_frame_desc"></span>
                <input type="hidden" id="requirement_id" name="requirement_id" value="<?=$requirement_details->requirement_id?>">
                <input type="hidden" id="address_id" name="address_id" value="<?=$requirement_details->Address_ID?>">
                <input type="hidden" id="contact_id" name="contact_id" value="<?=$requirement_details->Contact_ID?>">
            </div>
        </div>
	<div class="row">
            <div class="span12 center buttons">
                <button class="cus-buttons" id="addNewReq_submit" name="addNewReq_submit" type="submit">Update Requirement</button>
                <input type="hidden" name="contact_type" value="General">
            </div>
        </div>
        <div id="date_picker">
                                    
    </div>
    </form>
    </div><!-- cotainer ends -->
<?php include 'footer.php'; ?>