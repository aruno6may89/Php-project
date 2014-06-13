<?php include 'header.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#editContact").validate({
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
            submitHandler: function(form)
            {
                //$('#submit').click(function() {
                $('#addContact_submit').attr("disabled", "disabled");
                $('#addContact_reset').attr("disabled", "disabled");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>contacts/updateContact",
                    data: $('#editContact').serialize(),
                    dataType: 'json',
                    success: function(responseText) {
                        $("#message").show(100);
                        $('#addContact_submit').removeAttr('disabled');
                        $('#addContact_reset').removeAttr('disabled');
                        if (!responseText.hasError)
                        {
                            $('#status_message').html(responseText.status);
                            //$("#editContact")[0].reset();
                            //$(".chosen-select").val('').trigger("chosen:updated");
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
            <h3>Edit Contact :</h3>
        </div>
        <!--<div class="span1">
            <button class="cus-buttons">S</button>
        </div>-->
        <hr>
    </div>
    <form class="newcontact" id="editContact" action="" method="POST">
        <div class="row">
            <div class="span6">
                <label for="fName">First name<span class="required">*</span></label>
                <input type="text" placeholder="" name="fName" id="fName" value="<?= $contact_details->First_Name ?>">
                <span class="b-error fName"></span>
            </div>
            <div class="span6">
                <label for="lName">Last name<span class="required">*</span></label>
                <input type="text" placeholder="" name="lName" id="lName" value="<?= $contact_details->Last_Name ?>">
                <span class="b-error lName"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="pEmail">Personal Email<span class="required">*</span></label>
                <input placeholder="" name="pEmail" id="pEmail" value="<?= $contact_details->Email1 ?>">
                <span class="b-error pEmail"></span>
            </div>
            <div class="span6">
                <label for="pPhone">Personal Phone<span class="required">*</span></label>
                <input type="text" placeholder="" name="pPhone" id="pPhone" value="<?= $contact_details->cell ?>">
                <span class="b-error pPhone"></span>
            </div> 
        </div>
        <div class="row">
            <div class="span6">
                <label for="wEmail">Work Email</label>
                <input placeholder="" name="wEmail" id="wEmail" value="<?= $contact_details->Email2 ?>">
                <span class="b-error wEmail"></span>
            </div>
            <div class="span6">
                <label for="wPhone">Work Phone</label>
                <input type="tel" placeholder="" name="wPhone" id="wPhone" value="<?= $contact_details->phone ?>">
                <span class="b-error wPhone"></span>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <label for="desi">Designation<span class="required">*</span></label>
                <select name="desi" id="desi">
                    <option value="">Select</option>
                    <?php foreach ($this->designation as $row) { ?>
                        <option value="<?= $row->name ?>" <?php if ($contact_details->Designation == $row->name) echo "SELECTED" ?>><?= $row->name ?></option>
                    <?php } ?>
                </select>
                <span class="b-error desi"></span>
            </div>
            <div class="span6">
                <label for="refby">Referred By</label>
                <input type="text" placeholder="" name="refby" id="refby" value="<?= $contact_details->Referred_By ?>">
                <span class="b-error refby"></span>
                <input type="hidden" id="address_id" name="address_id" value="<?= $contact_details->Address_ID ?>">
                <input type="hidden" id="contact_id" name="contact_id" value="<?= $contact_details->Reporting_PersonID ?>">
            </div>
        </div>
        <div class="row">
            <div class="span12 center buttons">
                <button class="cus-buttons" id='addcontact_submit' type="submit">Update Contact</button>
                <input type="hidden" name="contact_type" value="General">
            </div>
        </div>
    </form>
</div><!-- cotainer ends -->
<?php include 'footer.php'; ?>