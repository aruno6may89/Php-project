<?php include 'header.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#newnotes').click(function(e) {
            var getModalId = $(this).attr("data-target");
            e.preventDefault();
            activatemodelwindow(getModalId);
            $('#notes_for').html('<?= $candidate_details->First_Name ?> <?= $candidate_details->Last_Name ?>');
                    });
        $('#newreference').click(function(e) {
    var getModalId = $(this).attr("data-target");
    e.preventDefault();
            activatemodelwindow(getModalId);
            $('#reference_for').html('<?= $candidate_details->First_Name ?> <?= $candidate_details->Last_Name ?>');
                    });
                    $('#deleteCandidate').click(function(e) {
                        e.preventDefault();
                        var confirm1 = confirm("Sure you want to deactivate the Candidate");
                        if (confirm1)
                        {
                            $.ajax({
                                type: "POST",
                                url: "<?php echo base_url() ?>candidate/changeCandidateStatus/<?= $candidate_id ?>/11", //status 20 for deactivating notes
                                dataType: 'json',
                                success: function(responseText) {
                                    $("#message").show(100);
                                    if (!responseText.hasError) {
                                        $('#status_message').html(responseText.status);
                                        //loadCandidate();
                                        window.location.href = absolute + 'candidate';
                                    }
                                    else {
                                        $('#status_message').html(responseText.errors);
                                    }
                                }
                            });

                        }
                    });
                });
</script>
<!-- App center content start -->
<div class="container main candidates-details">
    <?php
    if ((isset($_GET['sucess'])) && $_GET['sucess']) {
        $style = '';
        if ($_GET['sucess'] == 1) {
            $status = 'Sucessfully Uploaded';
        } else {
            $status = $_GET['sucess'];
        }
    } else {
        $style = ' style="display: none"';
    }
    ?>
    <div class="row" id="message" <?php echo $style ?>>
        <div class="span12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong></strong> <span id="status_message"><?= $status ?></span>
            </div>
        </div>
    </div>
    <div class="row heading">
        <div class="span12">
            <h3>Candidate Details:</h3>
        </div>
        <hr>
    </div>
    <div class="row action-buttons">
        <div class="span2">
            <div  class="inner">
                <a href="<?= BASE_URL ?>/candidate/editCandidate/<?= $candidate_id ?>">
                    <button rel="tooltip" data-toggle="tooltip" title="Edit" class="edit"></button>
                </a>
                <button rel="tooltip" data-toggle="tooltip" title="Delete" class="delete" id="deleteCandidate"></button>
            </div>
        </div>
        <div class="span3">
            <div  class="inner">
                <a href="<?= BASE_URL ?>/candidate/uploadResume/<?= $candidate_id ?>">
                    <button type="button" rel="tooltip" data-toggle="tooltip" title="Upload Resume" class="upload-resume"></button></a>
                <button type="button" rel="tooltip" data-toggle="tooltip" title="Add Notes" class="add-notes activate-model" data-target="addNotes" id="newnotes"></button>
                <button type="button" rel="tooltip" data-toggle="tooltip" title="Add Reference" class="add-reference activate-model" data-target="addReference" id="newreference"></button>
            </div>
            <!--<button type="button" data-toggle="modal" data-target="#addNotes">test</button>-->
        </div>
    </div>
    <div class="row candidate-h-info">
        <div class="span10">
            <h4><?= $candidate_details->First_Name . ' ' . $candidate_details->Last_Name ?></h4>
            <h6><span class="contact-no"><?= $address_details->Cell ?></span><span class="email"><?= $candidate_details->Email2 ?></span></h6>
        </div>
        <div class="span2">
            <span class="label label-success"><?= $candidate_details->Status ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span12">
            <h5>Personal Information</h5>
        </div>
    </div>
    <!--<div class="row">
        <div class="span3">
            <label>Candidate ID</label>
        </div>
        <div class="span9">
            <span>4742</span>
        </div>
    </div>-->
    <div class="row">
        <div class="span3">
            <label>Address</label>
        </div>
        <div class="span9">
            <span>
                <?php
                $address = '';
                if ($address_details->Address_1 != '-' && ($address_details->Address_1))
                    $address.=$address_details->Address_1 . '<br>';
                if ($address_details->Address_2 != '-' && ($address_details->Address_2))
                    $address.=$address_details->Address_2 . '<br>';
                if ($address_details->City != '-' && ($address_details->City))
                    $address.=$address_details->City . '<br>';
                if ($address_details->State != '-' && ($address_details->State))
                    $address.=$address_details->State . '<br>';

                echo $address;
                ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span12">
            <h5>Professional Information</h5>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Work email</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->Email1 ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Work phone</label>
        </div>
        <div class="span9">
            <span><?= $address_details->Work_Phone ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Primary Skills</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->primary_skill ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Secondary Skills</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->detailed_skill ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span12">
            <h5>Other Information</h5>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Willing to relocate</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->relocate ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Preferred Locations</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->location ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Suitable Position</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->Suitable_Position ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Preferred Position Type</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->position_type ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Contact Company</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->com_name ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Contact Person</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->contact_name ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Employer Name</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->Employer_Name ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Asking Rate</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->Asking_rate ?> <?= $candidate_details->duration ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Available Date</label>
        </div>
        <div class="span9">
            <span>
                <?php
                if ($candidate_details->available_date == '-' || $candidate_details->available_date == '' || $candidate_details->available_date == '0000-00-00' || (!$candidate_details->available_date)) {
                    echo '-';
                } else {
                    echo date('d-m-Y', strtotime($candidate_details->available_date));
                }
                ?>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Status (Tax Term)</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->immi_status ?> (<?= $candidate_details->Tax_Term ?>)</span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>NDA Signed</label>
        </div>
        <div class="span9">
            <span><?= $candidate_details->NDA_Signed ?></span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="span12">
            <h5>Resumes</h5>
        </div>
    </div>
    <div class="resume-table">
        <div>
            <table id="candidatesResumes">
                <thead>
                    <tr>
                        <th>File Description</th>
                        <th>File Name</th>
                        <th>Uploaded Date</th>
                        <th>Uploaded By</th>
                        <th>Submitted To</th>
                        <th>Submitted By</th>
                        <th>Submitted Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resume_details) {
                        foreach ($resume_details as $value) {
                            ?>
                            <tr>
                                <td><?= $value->filedescription ?></td>
                                <td><a href="<?= BASE_URL . '/' . $value->resume_url ?>"
                                    <?php
                                    if (trim($value->file_type) == '.txt' || trim($value->file_type) == '.pdf') {
                                        echo 'target="_blank"';
                                    }
                                    ?>
                                       ><?= $value->file_name ?></a></td>
                                <td><?php
                                    if ($value->upload_date == '-' || $value->upload_date == '' || $value->upload_date == '0000-00-00' || (!$value->upload_date)) {
                                        echo '-';
                                    } else {
                                        echo date('d-m-Y', strtotime($value->upload_date));
                                    }
                                    ?>
                                </td>
                                <td><?= $value->First_Name ?></td>
                                <td><?= $value->company_name ?></td>
                                <td><?= $value->submitted_by ?></td>
                                <td>
                                    <?php
                                    if ($value->Submitted_Date == '--' || $value->Submitted_Date == '' || $value->Submitted_Date == '0000-00-00' || (!$value->Submitted_Date)) {
                                        echo '--';
                                    } else {
                                        echo date('d-m-Y', strtotime($value->Submitted_Date));
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>  
                        <tr>
                            <td colspan="7">No Resumes Available For this Candidate</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div><!-- .resume-ends -->
    <br>
    <?php include 'notes.php'; ?>
    <br>
    <div class="row">
        <div class="span12">
            <h5>References</h5>
        </div>
    </div>
    <div class="reference-table">
        <div>
            <table id="candidatesreferences">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Company Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Title</th>
                        <th>Referred BY</th>
                    </tr>
                </thead>
                <tbody id="reference_body">
                    <?php
                    if ($reference_details) {
                        foreach ($reference_details as $value) {
                            ?>
                            <tr>
                                <td><?= $value->First_Name ?></td>
                                <td><?= $value->Name ?></td>
                                <td><?= $value->Work_Phone ?></td>
                                <td><?= $value->Email2 ?></td>
                                <td><?= $value->Designation ?></td>
                                <td><?= $value->Referred_By ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6">No Reference Available</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <input type="hidden" name="isReferenceAvailable" id="isReferenceAvailable" value="<?php
            if ($reference_details) {
                echo 'true';
            } else {
                echo 'false';
            }
            ?>">
        </div>
    </div><!-- .reference-ends -->
</div><!-- cotainer ends --> 
<div class="modal-container">
    <?php
    $entity_id = $candidate_details->Candidate_Id;
    $entity_type = 'candidate';
    include 'addnotes.php';
    include 'addreference.php';
    ?>
</div>
<?php include 'footer.php'; ?>