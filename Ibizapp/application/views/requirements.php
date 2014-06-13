<?php include 'header.php'; ?>
<script src="<?= JS_DIR ?>/requirements.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("body").on("click", "input:radio[name=select]", function() {
            if ($('input[name=select]').is(':checked'))
            {
                $("#edit").parent().attr("href", absolute + "requirements/editRequirements/" + $('input[name=select]:checked').val());
                $("#submitResume").parent().attr("href", absolute + "requirements/submitResume/" + $('input[name=select]:checked').val());
            }
        });

        $("#edit").click(function() {
            if (!$('input[name=select]').is(':checked'))
            {
                alert("Please select atleast one Record");
            }
            else{
                if ($('input[name=isActive' + $('input[name=select]:checked').val() + ']').val() == 'true')
                {
                    
                }
                else{
                    alert("Selected Requirement is already Inactive");
                    e.preventDefault();
                }
            }
        });
        $("#submitResume").click(function(e) {
            if (!$('input[name=select]').is(':checked'))
            {
                alert("Please select atleast one Record");
            }
            else{
                if ($('input[name=isActive' + $('input[name=select]:checked').val() + ']').val() == 'true')
                {
                    
                }
                else{
                    alert("Selected Requirement is already Inactive");
                    e.preventDefault();
                }
            }
        });
        $('#newnotes').click(function(e) {
            var getModalId = $(this).attr("data-target");
            e.preventDefault();
            if ($('input[name=select]').is(':checked'))
            {
                if ($('input[name=isActive' + $('input[name=select]:checked').val() + ']').val() == 'true')
                {
                    $('#entity_type').val('requirement');
                    $('#entity_id').val($('input[name=select]:checked').val());
                    $('#notes_for').html($('input[name=select' + $('input[name=select]:checked').val() + ']').val());
                    activatemodelwindow(getModalId);
                }
                else
                {
                    alert("Selected Requirement is already Inactive");
                }
            }
            else
            {
                alert("Please select atleast one Record");
            }
        });
        $('#deleteRequirement').click(function(e) {
            e.preventDefault();
            if ($('input[name=select]').is(':checked'))
            {
                if ($('input[name=isActive' + $('input[name=select]:checked').val() + ']').val() == 'true')
                {
                    var confirm1 = confirm("Sure you want to deactivate the Requirement");
                    var Id = $('input[name=select]:checked').val();
                    if (confirm1)
                    {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url() ?>requirements/changeRequirmentStatus/" + Id + '/21', //status 22 for deactivating requirement
                            dataType: 'json',
                            success: function(responseText) {
                                $("#message").show(100);
                                if (!responseText.hasError) {
                                    $('#status_message').html(responseText.status);
                                    loadRequirement();
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
                    alert("Selected Requirement is already Inactive");
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
<div class="container main requirements">
    <div class="row heading">
        <div class="span8">
            <h3>Requirements Listing </h3>
        </div>
        <!--<div class="span1">
            <button class="cus-buttons">S</button>
        </div>-->
        <div class="span4">
            <a href="<?= BASE_URL ?>/requirements/add" class="cus-buttons"><b>+</b> Add new Requirement</a>
        </div>
        <hr>
    </div>
    <div class="row action-buttons">

        <div class="span2">
            <div  class="inner">
                <a>
                    <button rel="tooltip" data-toggle="tooltip" title="Edit" class="edit" id="edit"></button></a>
                <button rel="tooltip" data-toggle="tooltip" title="Delete" class="delete" id="deleteRequirement"></button>
            </div>
        </div>
        <div class="span3">
            <div  class="inner">
                <a><button rel="tooltip" data-toggle="tooltip" title="Submit Resume" class="submit-resume" id="submitResume"></button></a>
                <button type="button" rel="tooltip" data-toggle="tooltip" title="Add Notes" class="add-notes activate-model" data-target="addNotes" id="newnotes"></button>
                <!--                            <button rel="tooltip" data-toggle="tooltip" title="Send Email" class="send-email"></button>-->
            </div>
        </div>
    </div>
    <div  id="table-data-container">
        <div>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="reqListing" style="width:100%;">

            </table>
        </div>
    </div><!-- #table-data-container ends -->
</div><!-- cotainer ends -->
<div class="modal-container">
    <?php include 'addnotes.php'; ?>
</div>
<?php include 'footer.php'; ?>
       