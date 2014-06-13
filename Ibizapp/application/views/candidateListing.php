<?php include 'header.php'; ?>
<script src="<?= JS_DIR ?>/candidate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("body").on("click", "input:radio[name=select]", function() {
            if ($('input[name=select]').is(':checked'))
            {
                $("#edit").parent().attr("href", absolute + "candidate/editCandidate/" + $('input[name=select]:checked').val());
                $("#uploadResume").parent().attr("href", absolute + "candidate/uploadResume/" + $('input[name=select]:checked').val());
            }
        });
        $("#edit").click(function() {
            if (!$('input[name=select]').is(':checked'))
            {
                alert("Please select atleast one Record");
            }
        });
        $('#newnotes').click(function(e) {
            var getModalId = $(this).attr("data-target");
            e.preventDefault();
            if ($('input[name=select]').is(':checked'))
            {
                $('#entity_type').val('candidate');
                $('#entity_id').val($('input[name=select]:checked').val());
                $('#notes_for').html($('input[name=select' + $('input[name=select]:checked').val() + ']').val());
                activatemodelwindow(getModalId);
            }
            else
            {
                alert("Please select atleast one Record");
            }
        });
        $('#newreference').click(function(e) {
            var getModalId = $(this).attr("data-target");
            e.preventDefault();
            if ($('input[name=select]').is(':checked'))
            {
                $('#candidate_id').val($('input[name=select]:checked').val());
                $('#reference_for').html($('input[name=select' + $('input[name=select]:checked').val() + ']').val());
                activatemodelwindow(getModalId);
            }
            else
            {
                alert("Please select atleast one Record");
            }
        });
        $('#uploadResume').click(function(e) {
            if (!$('input[name=select]').is(':checked'))
            {
                alert("Please select atleast one Record");
            }
        });
    
    $('#deleteCandidate').click(function(e) {
        e.preventDefault();
        if ($('input[name=select]').is(':checked'))
        {
			if($('input[name=isActive'+$('input[name=select]:checked').val()+']').val()=='true')
			{
            var confirm1 = confirm("Sure you want to deactivate the Candidate");
            var Id = $('input[name=select]:checked').val();
            if (confirm1)
            {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>candidate/changeCandidateStatus/" + Id + '/11', //status 11 for deactivating candidate
                    dataType: 'json',
                    success: function(responseText) {
                        $("#message").show(100);
                        if (!responseText.hasError) {
                            $('#status_message').html(responseText.status);
                            loadCandidate();
                        }
                        else {
                            $('#status_message').html(responseText.errors);
                        }
                    }
                });

            }
		}
		else
		{
			alert("Selected Candidate is already Inactive");
        }
        }
        else
        {
            alert("Please select atleast one Record");
        }
    });
    });
</script>
<!-- App center content start -->
<div class="container main candidates">
    <div class="row heading">
        <div class="span8">
            <h3>Candidates Listing</h3>
        </div>
        <!--<div class="span1">
            <button class="cus-buttons">S</button>
        </div>-->
        <div class="span4">
            <a href="<?= BASE_URL ?>/candidate/add" class="cus-buttons"><b>+</b> Add new Candidate</a>
        </div>
        <hr>
    </div>
    <div class="row action-buttons">

        <div class="span2">
            <div  class="inner">
                <a>
                    <button rel="tooltip" data-toggle="tooltip" title="Edit" class="edit" id="edit"></button>
                </a>
                <button rel="tooltip" data-toggle="tooltip" title="Delete" class="delete" id="deleteCandidate"></button>
            </div>
        </div>
        <div class="span3">
            <div  class="inner">
                <a>
                    <button rel="tooltip" data-toggle="tooltip" title="Upload Resume" class="upload-resume" id="uploadResume"></button></a>
                <button type="button" rel="tooltip" data-toggle="tooltip" title="Add Notes" class="add-notes activate-model" data-target="addNotes" id="newnotes"></button>
                <button rel="tooltip" data-toggle="tooltip" title="Add Reference" class="add-reference" id="newreference" data-target="addReference"></button>
            </div>
        </div>
    </div>
    <div  id="table-data-container">
        <div>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="candListing" style="width:100%;">

            </table>
        </div>
    </div><!-- #table-data-container ends -->
</div><!-- cotainer ends -->
<div class="modal-container">
    <?php include 'addnotes.php'; ?>
    <?php include 'addreference.php'; ?>
</div>
<?php include 'footer.php'; ?>
