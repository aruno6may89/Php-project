<?php include 'header.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#uploadaNewResume").validate({
            debug: false,
            errorElement: 'span',
            rules:
                    {
                        resumeDesc: {
                            required: true
                        },
                        fileupload: {
                            required: true,
                            accept: 'odt|docx|pdf|doc|txt'
                        }
                    },
            messages:
                    {
                        resumeDesc: {
                            required: 'Please Enter Resume Description'
                        },
                        fileupload: {
                            required: 'Please choose File to upload',
                            accept: 'Accepts only odt,docx,pdf,doc,txt Extensions'
                        },
                    },
            errorPlacement: function(error, element) {
                if (element.attr('id') === "resumeDesc")
                {
                    error.appendTo(".resumeDesc");
                }
                else if (element.attr('id') === "fileupload")
                {
                    error.appendTo(".fileupload");
                }
            }
        });
    });
</script>
<!-- App center content start -->
<div class="container main add-candidate">
    <div class="row heading">
        <div class="span12">
            <h3>Resume Upload for <?= $candidateDetails->First_Name ?></h3>
        </div>
        <hr>
    </div>
    <?php
    if (isset($status) && $status) {
        ?>
        <div class="row" id="message">
            <div class="span12">
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong></strong> <span id="status_message"><?php echo $status; ?> <a href="<?= BASE_URL ?>/details/"</span>
                </div>
            </div>
            <?php
        }
        ?>
        <form action="<?= BASE_URL ?>/Candidate/do_upload/<?= $candidate_id ?>" id="uploadaNewResume" class="add-new-candidate" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend><span>Resume Details</span></legend>
                <div class="row">
                    <div class="span6">
                        <label for="resumeDesc">Resume Description<span class="required">*</span></label>
                        <input type="text" placeholder="" name="resumeDesc" id="resumeDesc">
                        <span class="b-error resumeDesc"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="span6">
                        <label for="fileupload">Upload Resume<span class="required">*</span></label>
                        <input type="file" placeholder="" name="fileupload" id="fileupload">
                        <span class="b-error fileupload"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="span12 center buttons">
                        <button type="reset" id="addCand_reset" class="">Reset Form</button>
                        <button type="submit" id="addCand_submit" class="cus-buttons">Submit New Candidate</button>
                        <input type="hidden" name="candidate_id" value="<?= $candidate_id ?>">
                        <input type="hidden" name="skill" value="<?= $candidateDetails->primary_skill . $candidateDetails->detailed_skill ?>">
                        <input type="hidden" name="candidate_name" value="<?= $candidateDetails->First_Name ?>">
                    </div>
                </div>
            </fieldset>
        </form>

    </div><!-- cotainer ends -->
</div> <!-- .app-main ends -->
<?php include 'footer.php'; ?>
        
