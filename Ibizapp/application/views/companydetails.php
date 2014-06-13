<?php include 'header.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#newnotes').click(function(e) {
            var getModalId = $(this).attr("data-target");
            e.preventDefault();
            activatemodelwindow(getModalId);
            $('#notes_for').html('<?= $company_details->Name ?>');
        });

        $('#newContact').click(function(e) {
            var getModalId = $(this).attr("data-target");
            e.preventDefault();
            activatemodelwindow(getModalId);
            $('#contact_for').html('<?= $company_details->Name ?>');
        });
        $('#deleteCompany').click(function(e) {
            e.preventDefault();
            
                
                    var confirm1 = confirm("Sure you want to deactivate the Company");
                    var Id = $('input[name=select]:checked').val();
                    if (confirm1)
                    {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url() ?>Company/changeCompanyStatus/" + Id + '/2', //status 2 for deactivating company
                            dataType: 'json',
                            success: function(responseText) {
                                $("#message").show(100);
                                if (!responseText.hasError) {
                                    $('#status_message').html(responseText.status);
                                    //loadCompany();
                                    window.location.href = absolute + 'company';
                                }
                                else {
                                    $('#status_message').html(responseText.errors);
                                }
                            }
                        });

                    }
                
        });
        $("body").on("click", '.contactDetails a[data-deactivate="true"]', function(e) {
            e.preventDefault();
            var confirm1 = confirm("Sure you want to deactivate the Contact");
            var Id = $(this).attr("data-id");
            if (confirm1) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>contacts/changeContactStatus/"+Id+'/20',//status 20 for deactivating notes
                    dataType: 'json',
                    success: function(responseText) {
                        $("#message").show(100);
                        if(!responseText.hasError){
                            $('#con'+Id).remove();
                            if($('#companyContatcs tr').length>'1')
                            {
                                //do nothing
                            }
                            else
                            {
                                $('#contactsBody').html('<tr><td colspan="5">No Contacts Available</td></tr>');
                            }
                            $('#status_message').html(responseText.status);
                        }
                        else{
                            $('#status_message').html(responseText.errors);
                        }
                    }
                });
            }
        });
    });
</script>
<!-- App center content start -->
<div class="container main contact-details">
    <div class="row" id="message" style="display: none">
                	<div class="span12">
                        <div class="alert alert-info">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <strong></strong> <span id="status_message"></span>
                        </div>
                    </div>
                    </div>
    <div class="row heading">
        <div class="span12">
            <h3>Company Details</h3>
        </div>
        <hr>
    </div>
    <div class="row action-buttons">
        <div class="span2">
            <div  class="inner">
                <a href="<?= BASE_URL ?>/company/editCompanies/<?= $company_id ?>"><button rel="tooltip" data-toggle="tooltip" title="Edit" class="edit" id="edit"></button></a>
                <?php
                if ($this->session->userdata('Role') == "Admin" || $this->session->userdata('Role') == "Recruiter_Manager") {
                if($company_details->status!='Inactive')
                {
                    ?>
                
                <button rel="tooltip" data-toggle="tooltip" title="Delete" class="delete" id="deleteCompany"></button>
                    <?php
                }
                }
                ?>
            </div>
        </div>
        <div class="span3">
            <div  class="inner">
                <?php
                if($company_details->status!='Inactive')
                {
                ?>
                <button type="button" rel="tooltip" data-toggle="tooltip" title="Add Notes" class="add-notes activate-model" data-target="addNotes" id="newnotes"></button><button type="button" rel="tooltip" data-toggle="tooltip" title="Add Contact" class="add-contact activate-model" data-target="addcontact" id="newContact"></button>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="row candidate-h-info">
        <div class="span10">
            <h4><?= $company_details->Name ?></h4>
            <?php
            if ($company_details->URL != '' && $company_details->URL != '-') {
                ?>
                <h6><a target="_blank" href="http://<?= $company_details->URL ?>"><?= $company_details->URL ?></a></h6>
                <?php
            }
            ?>
        </div>
        <div class="span2">
            <span class="label label-success"><?= $company_details->status ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Company Type</label>
        </div>
        <div class="span9">
            <span><?= $company_details->Company_type ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Nature of Business</label>
        </div>
        <div class="span9">
            <span><?= $company_details->Nature_Of_Business ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>NDA Signed</label>
        </div>
        <div class="span9">
            <span><?= $company_details->NDA_Signed ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Signed Date</label>
        </div>
        <div class="span9">
            <span><?= $company_details->Signed_Date ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>NDA Signed By</label>
        </div>
        <div class="span9">
            <span><?= $company_details->NDA_Signed_By ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Position Title</label>
        </div>
        <div class="span9">
            <span><?= $company_details->Position_Title ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Address</label>
        </div>
        <div class="span9">
            <span>
<?php
if ($address_details) {
    ?>
                    <?= $address_details->Address_1 ?>,<br>
                    <?= $address_details->Address_2 ?>,<br>
                    <?= $address_details->City ?>,<br>
                    <?= $address_details->State ?><br>
                    <?php
                } else {
                    echo '-';
                }
                ?>
            </span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="span12">
            <h5>Contacts</h5>
        </div>
    </div>
    <div class="company-contacts">
        <div>
            <table id="companyContatcs">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone(s)</th>
                        <th>Email(s)</th>
                        <th>Contact Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="contactsBody">
<?php
//print_r($contact_details);
//exit();
if ($contact_details) {
    foreach ($contact_details as $value) {
        ?>
                            <tr class="contactDetails" id="con<?=$value->Reporting_PersonID?>">
                                <td><?= $value->contact_name ?></td>
                                <td><b>C:</b><?= $value->cell ?><br><b>W:</b> <?= $value->phone ?> <br></td>
                                <td><?= $value->Email1 ?><br><?= $value->Email2 ?></td>
                                <td><?= $value->Designation ?></td>
                                <td>
                                    <a href="<?=BASE_URL?>/contacts/details/<?=$value->Reporting_PersonID?>">View</a> |
                                    <a href="<?=BASE_URL?>/contacts/editCompanyContact/<?=$value->Reporting_PersonID?>">Edit</a> |
                                    <a href="#" data-deactivate="true" data-id="<?=$value->Reporting_PersonID?>">Deactivate</a>
                                </td>
                            </tr>

        <?php
    }
} else {
    ?>
                        <tr>
                            <td colspan="5">No Contacts Available</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <input type="hidden" name="isContactsAvailable" id="isContactsAvailable" value="<?php if ($contact_details) {
                        echo 'true';
                    } else {
                        echo 'false';
                    } ?>">
        </div>
    </div><!-- .contacts-ends -->
    <br>
                   <?php include 'notes.php'; ?>
</div><!-- cotainer ends --> 
<div class="modal-container">
    <?php
    $entity_id = $company_details->Company_ID;
    $entity_type = 'company';
    $company_id = $company_details->Company_ID;
    include 'addnotes.php';
    ?>
    <?php include 'addContactModel.php'; ?>
</div><!-- end of  class="modal-container" -->
    <?php include 'footer.php'; ?>