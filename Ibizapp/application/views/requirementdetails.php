<?php include 'header.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#newnotes').click(function(e) {
            var getModalId = $(this).attr("data-target");
            e.preventDefault();
            activatemodelwindow(getModalId);
            $('#notes_for').html('<?= $requirement_details->Position_title ?>');
        });
        $('#deleteRequirement').click(function(e) {
            e.preventDefault();
                    if($('#status').val()=='Inactive')
                    {
                    var confirm1 = confirm("Sure you want to deactivate the Requirement");
                    var Id = $('input[name=select]:checked').val();
                    if (confirm1)
                    {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url() ?>requirements/changeRequirmentStatus/<?= $requirement_id ?>/21", //status 22 for deactivating requirement
                            dataType: 'json',
                            success: function(responseText) {
                                $("#message").show(100);
                                if (!responseText.hasError) {
                                    $('#status_message').html(responseText.status);
                                    //loadRequirement();
                                    window.location.href = absolute + 'requirements';
                                }
                                else {
                                    $('#status_message').html(responseText.errors);
                                }
                            }
                        });

                    }
                    }
                    else{
                        alert('Selected Requirement is already Inactive');
                    }
        });
    });
</script>
<!-- App center content start -->
<div class="container main candidates-details">
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
            <h3>Requirement Details:</h3>
        </div>
        <hr>
    </div>
    <div class="row action-buttons">
        <div class="span2">
            <div  class="inner">
                <a href="<?= BASE_URL ?>/requirements/editRequirements/<?= $requirement_id ?>"><button rel="tooltip" data-toggle="tooltip" title="Edit" class="edit"></button></a>
                <?php
                if($requirement_details->status!='Inactive')
                {
                ?>
                <button rel="tooltip" data-toggle="tooltip" title="Delete" class="delete" id="deleteRequirement"></button>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="span3">
            <div  class="inner">
                <?php
                if($requirement_details->status!='Inactive')
                {
                ?>
                <a href="<?= BASE_URL ?>/requirements/submitResume/<?= $requirement_details->requirement_id ?>"><button rel="tooltip" data-toggle="tooltip" title="Submit Resume" class="submit-resume"></button></a>
                <button type="button" rel="tooltip" data-toggle="tooltip" title="Add Notes" class="add-notes activate-model" data-target="addNotes" id="newnotes"></button>
                <!--                            <button rel="tooltip" data-toggle="tooltip" title="Send Email" class="send-email"></button>-->
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="row candidate-h-info">
        <div class="span10">
            <h4><?= $requirement_details->Position_title ?></h4>
        </div>
        <div class="span2">
            <span class="label label-success"><?= $requirement_details->status ?></span>
        </div>
        <hr>
    </div>
    <!-- <div class="row">
             <div class="span3">
             <label>Requirement ID</label>
         </div>
         <div class="span9">
             <span>4742</span>
         </div>
     </div>-->
    <div class="row">
        <div class="span3">
            <label>Position ID</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->Position_ID ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Start Date</label>
        </div>
        <div class="span9">
            <span>
                <?php
                //echo $requirement_details->Start_Date;
                if ($requirement_details->Start_Date != '--') {
                    echo date('d-m-Y', strtotime($requirement_details->Start_Date));
                } else {
                    echo '--';
                }
                ?>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Close Date</label>
        </div>
        <div class="span9">
            <span><?php
                //echo $requirement_details->Close_Date;
                if ($requirement_details->Close_Date != '--') {
                    echo date('d-m-Y', strtotime($requirement_details->Close_Date));
                } else {
                    echo '--';
                }
                ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Position Description</label>
        </div>
        <div class="span9">
<?= $requirement_details->Requirement_Detail ?>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Skills</label>
        </div>
        <div class="span9">
<?= $requirement_details->Skills ?>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Duration</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->to_time_frame ?> - <?= $requirement_details->from_time_frame ?> <?= $requirement_details->time_frame_desc ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Billing Rate $</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->billing_rate ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Tax Term</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->Tax_Term ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Location</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->Location ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Source</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->Source ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>End Client Name</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->End_Client_Name ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Contact Person</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->contact_name ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Contact Phone</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->Work_Phone ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Contact Email</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->Email1 ?></span>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <label>Priority</label>
        </div>
        <div class="span9">
            <span><?= $requirement_details->priority ?></span>
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
                        <th>Candidate Name</th>
                        <th>Contact No</th>
                        <th>Submitted By</th>
                        <th>Submitted To</th>
                        <th>Submitted Date</th>
                        <th>Status</th>
                        <th>Change status</th>
                    </tr>
                </thead>
                <tbody>
<?php
if ($submited_candidates) {
    foreach ($submited_candidates as $value) {
        ?>
                            <tr>
                                <td><?= $value->cand_name ?></td>
                                <td>C : <?= $value->cell ?><br>W : <?= $value->phone ?></td>
                                <td><?= $value->rec_name ?></td>
                                <td><?= $value->comp_name ?></td>
                                <td><?php
                            if ($value->Submitted_Date != '') {
                                echo date('d-m-y', strtotime($value->Submitted_Date));
                            } else {
                                echo '--';
                            }
                            ?></td>
                                <td><?= $value->status ?></td>
                                <td>
                                    <?php
                                    if ($value->status != 'Closed' && $value->status != 'Cancelled' && $value->status != 'Placed') {
                                        ?>
                                        <select name="status">
                                            <option value="">--Select--</option>
                                        <?php
                                        foreach ($submit_status as $value1) {
                                            if ($value->status != $value1->Short_Name) {
                                                ?>   
                                                    <option value="<?= $value1->Status ?>"><?= $value1->Short_Name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
            <?php
        } else {
            echo 'Status cannot be changed';
        }
        ?>
                                </td>
                            </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                        <tr>
                            <td colspan="6">No Submitted Candidates Available</td>
                        </tr>
                                <?php
                            }
                            ?>
                </tbody>
            </table>
        </div>
    </div><!-- .resume-ends -->
    <br>
    <input type="hidden" name="status" id="status" value="<?= $requirement_details->status ?>">
                    <?php include 'notes.php'; ?>
</div><!-- cotainer ends --> 

<div class="modal-container">
<?php
$entity_id = $requirement_details->requirement_id;
$entity_type = 'requirement';
include 'addnotes.php';
?>
                    <?php include 'addreference.php'; ?>
</div><!-- end of  class="modal-container" -->
<?php include 'footer.php'; ?>