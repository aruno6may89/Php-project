<?php include 'header.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#newrequirement").validate({
            debug: true,
            errorElement: 'span',
            rules:
                    {
                        pid: {
                            required: true
                        },
                        ptitle: {
                            required: true
                        },
                        sdate: {
                            required: true
                        },
                        cdate: {
                            required: true
                        },
                        skill: {
                            required: true
                        },
                        billrate: {
                            required: true,
                            number: true
                        },
                        tax_term: {
                            required: true
                        },
                        loc: {
                            required: true
                        },
                        status: {
                            required: true
                        },
                        priority:{
                            required: true
                        },
                        company: {
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
                        /*to_time_frame: {
                            required: true
                        },
                        from_time_frame: {
                            required: true
                        },
                        time_frame_desc: {
                            required: true
                        }*/
                    },
            messages:
                    {
                        pid: {
                            required: "Please Enter Position Id"
                        },
                        ptitle: {
                            required: "Please Enter Position Title"
                        },
                        sdate: {
                            required: "Please Enter Starting Date"
                        },
                        cdate: {
                            required: "Please Enter Closing Date"
                        },
                        skill: {
                            required: "Please Enter Skills Required"
                        },
                        billrate: {
                            required: "Please Enter Billing Rate",
                            number: "Please Enter Numeric Values"
                        },
                        tax_term: {
                            required: "Please Enter Tax Term"
                        },
                        loc: {
                            required: "Please Enter Location"
                        },
                        status: {
                            required: "Please Select Status"
                        },
                        priority:{
                            required: "Please Select Priority"
                        },
                        company: {
                            required: "Please Select Company"
                        },
                        cp:{
                            required: "Please Enter contact Person"
                        },
                        cphone:{
                            required: "Please Enter Phone no",
                            isdigit:"Only Numbers , -,(,) are allowed"
                        },
                        cemail: {
                            required: "Please Enter Email",
                            email: "Please Enter Valid Email id"
                        },
                        to_time_frame: {
                            required: "Please Enter Duration"
                        },
                        from_time_frame: {
                            required: "Please Enter Duration"
                        },
                        time_frame_desc: {
                            required: "Please Enter Duration"
                        }
                    },
            errorPlacement: function(error, element) {
                if (element.attr('id') === "pid")
                {
                    error.appendTo(".pid");
                }
                else if (element.attr('id') === "ptitle")
                {
                    error.appendTo(".ptitle");
                }
                else if (element.attr('id') === "sdate")
                {
                    error.appendTo(".sdate");
                }
                else if (element.attr('id') === "cdate")
                {
                    error.appendTo(".cdate");
                }
                else if (element.attr('id') === "skill")
                {
                    error.appendTo(".skill");
                }
                else if (element.attr('id') === "cemail")
                {
                    error.appendTo(".cemail");
                }
                else if (element.attr('id') === "billrate")
                {
                    error.appendTo(".billrate");
                }
                else if (element.attr('id') === "tax_term")
                {
                    error.appendTo(".tax_term");
                }
                else if (element.attr('id') === "loc")
                {
                    error.appendTo(".loc");
                }
                else if (element.attr('id') === "status")
                {
                    error.appendTo(".status");
                }
                else if (element.attr('id') === "priority")
                {
                    error.appendTo(".priority");
                }
                else if (element.attr('id') === "company")
                {
                    error.appendTo(".company");
                }
                else if (element.attr('id') === "cp")
                {
                    error.appendTo(".cp");
                }
                else if (element.attr('id') === "cphone")
                {
                    error.appendTo(".cphone");
                }
                else if (element.attr('id') === "to_time_frame")
                {
                    error.appendTo(".to_time_frame");
                }
                else if (element.attr('id') === "from_time_frame")
                {
                    error.appendTo(".from_time_frame");
                }
                else if (element.attr('id') === "time_frame_desc")
                {
                    error.appendTo(".time_frame_desc");
                }
            },
            submitHandler: function(form)
            {
                //$('#submit').click(function() {
                $('#addNewReq_submit').attr("disabled", "disabled");
                $('#addNewReq_reset').attr("disabled", "disabled");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>requirements/saveRequirement",
                    data: $('#newrequirement').serialize(),
                    dataType: 'json',
                    success: function(responseText) {
                        $("#message").show(100);
                        $('#addNewReq_submit').removeAttr('disabled');
                        $('#addNewReq_reset').removeAttr('disabled');
                        if (!responseText.hasError)
                        {
                            $('#status_message').html(responseText.status);
                            $("#newrequirement")[0].reset();
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
            <h3>Add New Requirement :</h3>
        </div>
        <!--<div class="span1">
            <button class="cus-buttons">S</button>
        </div>-->
        <hr>
    </div>
    <form class="newrequirement" id="newrequirement">
        <div class="row">
            <div class="span6">
            <label for="pid">Position ID<span class="required">*</span></label>
            <input type="text" name="pid" id="pid"/>
            <span class="b-error pid"></span>
            </div>
            <div class="span6">
                <label for="ptitle">Position Title<span class="required">*</span></label>
                <input type="text" name="ptitle" id="ptitle"/>
                <span class="b-error ptitle"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="sdate">Start Date<span class="required">*</span></label>
                <input type="text" name="sdate" class="datepicker bdate" id="sdate"  data-date-format="dd-mm-yyyy" readonly/>
                <span class="b-error sdate"></span>
            </div>
            <div class="span6">
                <label for="cdate">Close Date<span class="required">*</span></label>
                
                <input type="text" name="cdate" class="datepicker bdate" id="cdate" data-date-format="dd-mm-yyyy" readonly/>
                <span class="b-error cdate"></span>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <label for="posdes">Position Description</label>
                <textarea name="posdes" id="posdes"></textarea>
                <span class="b-error posdes"></span>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <label for="skill">Skills<span class="required">*</span></label>
                <textarea name="skill" id="skill"></textarea>
                <span class="b-error skill"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="sdate">Billing Rate $<span class="required">*</span></label>
                <input type="text" name="billrate" id="billrate"/>
                <span class="b-error billrate"></span>
            </div>
            <div class="span6">
                <label for="taxterm">Tax Term<span class="required">*</span></label>
                <select name="tax_term" id="tax_term">
                    <option value="">-Select-</option>
                    <?php foreach ($tax_term as $row) { ?>
                        <option value="<?= $row->name ?>"><?= $row->name ?></option>
                    <?php } ?>
                </select>
                <span class="b-error tax_term"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="loc">Location<span class="required">*</span></label>
                <input type="text" name="loc" id="loc"/>
                <span class="b-error loc"></span>
            </div>
            <div class="span6">
                <label for="source">Source</label>
                <input type="text" name="source" id="source"/>
                <span class="b-error source"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="ecn">End Client Name</label>
                <input type="text" name="ecn" id="ecn"/>
                <span class="b-error ecn"></span>
            </div>
            <div class="span6">
                <label for="cp">Contact Person<span class="required">*</span></label>
                <input type="text" name="cp" id="cp"/>
                <span class="b-error cp"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="cphone">Contact Phone<span class="required">*</span></label>
                <input type="tel" name="cphone" id="cphone"/>
                <span class="b-error cphone"></span>
            </div>
            <div class="span6">
                <label for="cemail">Contact Email<span class="required">*</span></label>
                <input type="text" name="cemail" id="cemail"/>
                <span class="b-error cemail"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="status">Status<span class="required">*</span></label>
                <select name="status" id="status">
                    <option value="">-Select-</option>
                   <?php foreach ($requirement_status as $row) { ?>
                        <option value="<?= $row->Status ?>"><?= $row->Short_Name ?></option>
                    <?php } ?>
                </select>
                <span class="b-error status"></span>
            </div>
            <div class="span6">
                <label for="priority">Priority<span class="required">*</span></label>
                <select name="priority" id="priority">
                    <option value="">-select-</option>
                    <?php foreach ($priority as $row) { ?>
                        <option value="<?= $row->name ?>"><?= $row->name ?></option>
                    <?php } ?>
                </select>
                <span class="b-error priority"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="company">Company Name<span class="required">*</span></label>
                <select name="company" id="company" class="chosen-select">
                    <option value="">-- Select --</option>
                    <?php foreach ($company as $row) { ?>
                        <option value="<?= $row->Company_ID ?>"><?= $row->Name ?></option>
                    <?php } ?>
                </select>
                <span class="b-error company"></span>
            </div>
            <div class="span6">
                <label for="dure">Duration</label>
                <select name="to_time_frame" style="width: 50px">
                    <?php foreach ($time_frame as $row) { ?>
                        <option value="<?= $row->name ?>"><?= $row->name ?></option>
                    <?php } ?>
                </select>
                
                <select name="from_time_frame" id="dure" style="width: 50px">
                   <?php foreach ($time_frame as $row) { ?>
                        <option value="<?= $row->name ?>"><?= $row->name ?></option>
                    <?php } ?>
                </select>
                
                <select name="time_frame_desc" id="dure" style="width: 100px">
                    <option value="">select</option>
                    <?php foreach ($time_frame_desc as $row) { ?>
                        <option value="<?= $row->name ?>"><?= $row->name ?></option>
                    <?php } ?>
                </select>
                <span class="b-error to_time_frame"></span>
                <span class="b-error from_time_frame"></span>
                <span class="b-error time_frame_desc"></span>
            </div>
        </div>
        <div class="row">
            <div class="span12 center buttons">
                <button class="" type="reset">Reset</button>
                <button class="cus-buttons" type="submit">Submit New Requirement</button>
                <input type="hidden" name="contact_type" value="General">
            </div>
        </div>
    </form>
    <div id="date_picker">
                                    
    </div>
</div><!-- cotainer ends -->
<?php include 'footer.php'; ?>