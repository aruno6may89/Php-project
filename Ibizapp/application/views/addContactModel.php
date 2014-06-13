<script type="text/javascript">
    $(document).ready(function() {
        $("#addNewContact").validate({
            debug: true,
            errorElement: 'span',
            rules:
                    {
                        fName: {
                            required: true,
                            letterspace: true
                        },
                        lName: {
                            required: true,
                            letterspace: true
                        },
                        pEmail: {
                            email: true
                        },
                        pPhone: {
                            isdigit: true
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
                            required: 'Please Enter Personal Phone number'
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
                        },
                        refby: {
                            required: 'Please Enter Reffered by'
                        }
                    },
            errorPlacement: function(error, element) {
                if (element.attr('id') === "fName")
                {
                    error.appendTo(".fName");
                }
                else if (element.attr('id') === "lName")
                {
                    error.appendTo(".lName");
                }
                else if (element.attr('id') === "pEmail")
                {
                    error.appendTo(".pEmail");
                }
                else if (element.attr('id') === "pPhone")
                {
                    error.appendTo(".pPhone");
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
                else if (element.attr('id') === "refby")
                {
                    error.appendTo(".refby");
                }
//                          else {
//                            error.appendTo(".errors");
//                        }
            },
            submitHandler: function(form)
            {
                $('#addContact_submit').attr("disabled", "disabled");
                $('#addContact_reset').attr("disabled", "disabled");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>contacts/saveContact",
                    data: $('#addNewContact').serialize(),
                    dataType: 'json',
                    success: function(responseText) {
                        $("#contact_message").show(100);
                        $('#addContact_submit').removeAttr('disabled');
                        $('#addContact_reset').removeAttr('disabled');
                        if (!responseText.hasError)
                        {
                            $('#contact_status_message').html(responseText.status);
                            $("#addNewContact")[0].reset();
                            if ($('#isCompanyDetails').val() == 'true')//check wheather it is deatails page
                            {
                                if ($('#isContactsAvailable').val() == 'true')//check wheather contacts avalable or not
                                {
                                    $('#contactsBody').append(responseText.ajaxDataContacts);//if available append the available data
                                }
                                else if ($('#isContactsAvailable').val() == 'false')
                                {
                                    $('#contactsBody').html(responseText.ajaxDataContacts);
                                    $('#isContactsAvailable').val('true');
                                }
                            }
                            setTimeout(function() {
                                closemodalwindow('addcontact');
                                $('#contact_message').hide();
                            }, 3000);
                        }
                        else
                        {
                            $('#contact_status_message').html(responseText.errors);
                            setTimeout(function() {
                                $('#contact_message').hide();
                            }, 3000);
                        }
                    }
                    //  });

                });
                return false;
            }
        });
    });
</script>
<div id="addcontact" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="addcontact" aria-hidden="true">
    <div class="row" id="contact_message" style="display: none">
        <div class="span12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong></strong> <span id="contact_status_message"></span>
            </div>
        </div>
    </div>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Add Contact for <span id="contact_for"></span></h3>
    </div>
    <div class="modal-body">
        <form class="newcontact" name="addNewContact" id="addNewContact">
            <div class="row">
                <div class="span3">
                    <label for="fName">First name<span class="required">*</span></label>
                    <input type="text" placeholder="" name="fName" id="fName">
                    <span class="b-error fName"></span>
                </div>
                <div class="span3">
                    <label for="lName">Last name<span class="required">*</span></label>
                    <input type="text" placeholder="" name="lName" id="lName">
                    <span class="b-error lName"></span>
                </div>
            </div>
            <div class="row">
                <div class="span3">
                    <label for="pEmail">Personal Email</label>
                    <input type="text" placeholder="" name="pEmail" id="pEmail">
                    <span class="b-error pEmail"></span>
                </div>
                <div class="span3">
                    <label for="pPhone">Personal Phone</label>
                    <input type="text" placeholder="" name="pPhone" id="pPhone">
                    <span class="b-error pPhone"></span>
                </div> 
            </div>
            <div class="row">
                <div class="span3">
                    <label for="wEmail">Work Email<span class="required">*</span></label>
                    <input type="text" placeholder="" name="wEmail" id="wEmail">
                    <span class="b-error wEmail"></span>
                </div>
                <div class="span3">
                    <label for="wPhone">Work Phone<span class="required">*</span></label>
                    <input type="text" placeholder="" name="wPhone" id="wPhone">
                    <span class="b-error wPhone"></span>
                </div>
            </div>
            <div class="row">
                <div class="span3">
                    <label for="desi">Designation<span class="required">*</span></label>
                    <select class="text" name="desi" id="desi">
                        <option value="">-- Select --</option>
                        <?php foreach ($designation as $row) { ?>
                            <option value="<?= $row->name ?>"><?= $row->name ?></option>
                        <?php } ?>
                    </select>
                    <span class="b-error desi"></span>
                </div>
                <div class="span3">
                    <label for="refby">Referred By</label>
                    <input type="text" placeholder="" name="refby" id="refby">
                    <span class="b-error refby"></span>
                </div>
            </div>
            <div class="row">
                <div class="span7 center buttons" >
                    <button class="" type="button">Reset</button>
                    <button class="cus-buttons" id='addcontact_submit' type="submit">Submit New Contact</button>
                    <input type="hidden" name="company_id" id="company_id" value="<?php if (isset($company_id)) {
                            echo $company_id;
                        } ?>">
                    <input type="hidden" name="contact_type" value="Company Contact">
                    <input type="hidden" name="isCompanyDetails" id="isCompanyDetails" value="<?php
                           if (isset($company_id)) {
                               echo 'true';
                           } else {
                               echo 'false';
                           }
                           ?>">
                </div>
            </div>
        </form>
    </div><!-- end of  id="addNotes" -->
</div>