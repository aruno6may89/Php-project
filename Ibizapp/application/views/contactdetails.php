<?php include 'header.php';?>
<script type="text/javascript">
   $(document).ready(function() {
        $('#newnotes').click(function(e){
            var getModalId = $(this).attr("data-target");
            e.preventDefault();
                activatemodelwindow(getModalId);
                $('#notes_for').html('<?=$contact_details->Name?>');
        });

        $('#deleteContact').click(function(e){
            e.preventDefault();
                var confirm1 = confirm("Sure you want to deactivate the Contact");
                if(confirm1)
                {
                    $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>contacts/changeContactStatus/<?=$contact_id?>/20",//status 20 for deactivating notes
                    dataType: 'json',
                    success: function(responseText) {
                        $("#message").show(100);
                        if(!responseText.hasError){
                            $('#status_message').html(responseText.status);
                            window.location.href=absolute+'contacts';
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
                    	<h3>Contact Details</h3>
                    </div>
                    <hr>
                </div>
                <div class="row action-buttons">
                    <div class="span2">
                    	<div  class="inner">
                            <a href="<?=BASE_URL?>/contacts/editContact/<?=$contact_id?>">
                                <button rel="tooltip" data-toggle="tooltip" title="Edit" class="edit"></button>
                            </a>
                                <button rel="tooltip" data-toggle="tooltip" title="Delete" class="delete" id="deleteContact"></button>
                        </div>
                    </div>
                    <div class="span3">
                    	<div  class="inner">
                        	<button type="button" rel="tooltip" data-toggle="tooltip" title="Add Notes" class="add-notes activate-model" data-target="addNotes" id="newnotes"></button>
                        </div>
                    </div>
                </div>
                <div class="row candidate-h-info">
                	<div class="span10">
                    	<h4><?=$contact_details->Name?></h4>
                    	<h6><span class="email"><?=$contact_details->Email?></span></h6>
                    </div>
                </div>
                <div class="row">
                	<div class="span3">
                    	<label>Contact Type</label>
                    </div>
                    <div class="span9">
                    	<span><?=$contact_details->contact_type?></span>
                    </div>
                </div>
                <div class="row">
                	<div class="span3">
                    	<label>Referred By</label>
                    </div>
                    <div class="span9">
                    	<span><?=$contact_details->Referred_By?></span>
                    </div>
                </div>
                <div class="row">
                	<div class="span3">
                    	<label>Phone(s)</label>
                    </div>
                    <div class="span9">
                    	<span><b>C:</b><?=$contact_details->cell?></span><br>
                        <span><b>W:</b><?=$contact_details->phone?></span><br>
                    </div>
                </div>
                <div class="row">
                	<div class="span3">
                    	<label>E-mail(s)</label>
                    </div>
                    <div class="span9">
                    	<span><?=$contact_details->Email?></span>
                    </div>
                </div>
                <br>
               <?php include 'notes.php'; ?>
                <br>
           </div><!-- cotainer ends --> 
           <div class="modal-container">
            <?php
            $entity_id=$contact_details->Reporting_PersonID;
            $entity_type='contact';
            include 'addnotes.php'; 
            include 'addreference.php'; ?>
        </div>
<?php include 'footer.php'; ?>