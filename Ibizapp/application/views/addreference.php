<script>
    $(document).ready(function() {
        $("#conCompany").change(function() {
            jQuery.ajax({
                type: "POST",
                url: "<?= base_url() ?>company/getCompanyContacts/" + $('#conCompany').val(),
                dataType: 'json',
                success: function(responseText) {
                    $('#conPerson').html(responseText.selectData);
                    $("#conPerson").data("placeholder", "--Select--").trigger('chosen:updated');
                }
            });
        });

        $("#searchCompany").validate({
            debug: true,
            errorElement: 'span',
            rules:
                    {
                        conCompany: {
                            required: true
                        },
                        conPerson: {
                            required: true
                        }
                    },
            messages:
                    {
                        conCompany: {
                            required: 'Please Select Company'
                        },
                        conPerson: {
                            required: 'Please Select Contact Person'
                        }
                    },
            errorPlacement: function(error, element) {
                if (element.attr('id') === "conCompany")
                {
                    error.appendTo(".conCompany");
                }
                else if (element.attr('name') === "conPerson")
                {
                    error.appendTo(".conPerson");
                }
            },
            submitHandler: function(form)
            {
                $('#addref_submit').attr("disabled", "disabled");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>Candidate/saveReference",
                    data: $('#searchCompany').serialize(),
                    dataType: 'json',
                    success: function(responseText) {
                        $("#reference_message").show(100);
                        $('#addref_submit').removeAttr('disabled');
                        if (!responseText.hasError)
                        {
                            $('#status_message').html(responseText.status);
                            $("#searchCompany")[0].reset();
                            if ($('#isDetails').val() == 'true')//check wheather it is deatails page
                            {
                                if ($('#isReferenceAvailable').val() == 'true')//check wheather notes avalable or not
                                {
                                    $('#reference_body').prepend(responseText.ajaxReferenceData);//if available append the available data
                                }
                                else if ($('#isReferenceAvailable').val() == 'false')
                                {
                                    $('#reference_body').html(responseText.ajaxReferenceData);
                                    $('#isReferenceAvailable').val('true');
                                }
                            }
                            setTimeout(function() {
                                closemodalwindow('addReference');
                                $("#reference_message").hide();
                            }, 3000);
                        }
                        else
                        {
                            $('#status_message').html(responseText.errors);
                            setTimeout(function() {
                                //closemodalwindow('addReference');
                                $("#reference_message").hide();
                            }, 3000);
                        }
                    }
                });
                return false;
            }
        });
        $("#refCreateNewCompany").validate({
            debug: true,
            errorElement: 'span',
            rules:
                    {
                        cname: {
                            required: true
                        },
                        compStatus: {
                            required: true
                        },
                        ndaSigned: {
                            required: true
                        },
                        fName: {
                            required: true,
                            letterspace: true
                        },
                        lName: {
                            required: true,
                            letterspace: true
                        },
                        wEmail: {
                            required: true,
                            email: true
                        },
                        wPhone: {
                            required: true,
                            isdigit: true
                        },
                        desi: {
                            required: true
                        }
                        
                    },
            messages:
                    {
                        cname: {
                            required: 'Please Enter Company Name'
                        },
                        compStatus: {
                            required: 'Please Select Company Status'
                        },
                        ndaSigned: {
                            required: 'Please Select NDA Signed'
                        },
                        fName: {
                            required: 'Please Enter First Name',
                            letterspace: 'only alphabets are allowed'
                        },
                        lName: {
                            required: 'Please Enter Last Name',
                            letterspace: 'only alphabets are allowed'
                        },
                         wEmail: {
                            required: 'Please Enter Work Email',
                            email: 'Please Enter Valid Email'
                        },
                        wPhone: {
                            required: 'Enter Work Phone number',
                            isdigit: 'Enter valid Work Phone number'
                        },
                        desi: {
                            required: 'Please Select Designation'
                        }
                    },
            errorPlacement: function(error, element) {
                if (element.attr('id') === "cname")
                {
                    error.appendTo(".cname");
                }
                else if (element.attr('name') === "compStatus")
                {
                    error.appendTo(".compStatus");
                }
                else if (element.attr('name') === "ndaSigned")
                {
                    error.appendTo(".ndaSigned");
                }
                else if (element.attr('id') === "fName")
                {
                    error.appendTo(".fName");
                }
                else if (element.attr('id') === "lName")
                {
                    error.appendTo(".lName");
                }
                else if (element.attr('id') === "wEmail")
                {
                    error.appendTo(".wEmail");
                }
                else if (element.attr('id') === "wPhone")
                {
                    error.appendTo(".wPhone");
                }
                else if (element.attr('id') === "desi")
                {
                    error.appendTo(".desi");
                }
            },
            submitHandler: function(form)
            {
                $('#addref_submit').attr("disabled", "disabled");
                $.ajax({
                    type: "POST",
                        url: "<?php echo base_url() ?>candidate/refNewCompany",
                    data: $('#refCreateNewCompany').serialize(),
                    dataType: 'json',
                    success: function(responseText) {
                        $("#message").show(100);
                        $('#addref_submit').removeAttr('disabled');
                        if (!responseText.hasError)
                        {
                            $('#status_message').html(responseText.status);
                            $("#refCreateNewCompany")[0].reset();
                            $(".create-new-company-container").hide();
                            $("#searchCompany,#addReference .modal-footer,#searchCompany").show();
                            $('#conCompany').prepend('<option value="'+responseText.company_id+'">'+responseText.company_name+'</option>');
                            $("#conCompany").data("placeholder", "--Select--").trigger('chosen:updated');
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
<div id="addReference" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="addReference" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Add Reference for <span id="reference_for"></span></h3>
    </div>
    <div class="row" id="reference_message" style="display: none">
        <div class="span12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong></strong> <span id="status_message"></span>
            </div>
        </div>
    </div>
    <div class="modal-body">
        <form id="searchCompany" name="searchCompany" action=''>
            <div class="input-append">
                <div class="span6">
                    <label for="conCompany">Company Search</label>
                    <select name="conCompany" data-placeholder="Choose a Company..." id="conCompany" class="chosen-select">
                        <option value=""></option>
                        <?php foreach ($companies as $row) { ?>
                            <option value="<?= $row->Company_ID ?>"><?= $row->Name ?></option>
                        <?php } ?>
                    </select>
                    <span class="b-error conCompany"></span>
                </div>
                <div class="span6">
                    <label for="conPerson">Contact Person<span class="required">*</span></label>
                    <select name="conPerson" id="conPerson" data-placeholder="Choose a Contact Person..." class="chosen-select">
                        <option value=""></option>
                    </select>
                    <span class="b-error conPerson"></span>
                </div>
                <input type="hidden" name="candidate_id" id="candidate_id" value="<?php if (isset($candidate_details)) echo $candidate_details->Candidate_Id; ?>" >
            </div>
            <div class="modal-footer">
                <button class="cus-buttons pad-five" name="addref_submit" id="addref_submit" type="submit">Add Reference</button>
            </div>
        </form>
        <div class="create-new-company-container">
            <form id="refCreateNewCompany">
                <fieldset>
                    <legend>Add New Company</legend>
                    <div class="input-append">
                        <label for="cname">Company Name<span class="required">*</span></label>
                        <input class="span4" id="cname" name="cname" type="text" placeholder="">
                    </div>
                    <span class="b-error cname"></span>
                    <div class="input-append">
                        <label for="compStatus">Company Status<span class="required">*</span></label>
                        <select name="compStatus" id="compStatus">
                            <option value="">-- Select --</option>
                            <?php foreach ($company_status as $row) { ?>
                                <option value="<?= $row->Status ?>"><?= $row->Short_Name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <span class="b-error compStatus"></span>
                    <div class="input-append">
                        <label for="">NDA Signed<span class="required">*</span></label>
                        <select name="ndaSigned" id="ndaSigned">
                            <option value="">-- Select --</option>
                            <?php foreach ($nda as $row) { ?>
                                <option value="<?= $row->name ?>"><?= $row->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <span class="b-error ndaSignedny"></span>
                    <div class="input-append">
                        <label for="">Contact First Name<span class="required">*</span></label>
                        <input type="text" placeholder="" name="fName" id="fName">
                    </div>
                    <span class="b-error fName"></span>
                    <div class="input-append">
                        <label for="">Contact Last Name<span class="required">*</span></label>
                        <input type="text" placeholder="" name="lName" id="lName">
                    </div>
                    <span class="b-error lName"></span>
                    <div class="input-append">
                        <label for="">Work Email<span class="required">*</span></label>
                        <input type="text" placeholder="" name="wEmail" id="wEmail">
                    </div>
                    <span class="b-error wEmail"></span>
                    <div class="input-append">
                        <label for="">Work Phone<span class="required">*</span></label>
                        <input type="text" placeholder="" name="wPhone" id="wPhone">
                    </div>
                    <span class="b-error wPhone"></span>
                    <div class="input-append">
                        <label for="">Designation<span class="required">*</span></label>
                        <select class="text" name="desi" id="desi">
                            <option value="">-- Select --</option>
                            <?php foreach ($designation as $row) { ?>
                                <option value="<?= $row->name ?>"><?= $row->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <span class="b-error desi"></span>
                    <div class="input-append center">
                        <button type="button" class="cancel">Cancel</button>
                        <button type="submit" class="cus-buttons">Submit New Company</button>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <p>Company is not in the list/Search result, <button class="cus-buttons l-create-new-company">Create New Company</button></p>
        </div>
    </div>

</div><!-- end of  id="addReference" -->
