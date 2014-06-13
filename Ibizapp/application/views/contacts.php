<?php include 'header.php';?>
<script src="<?=JS_DIR?>/contact.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("body").on( "click", "input:radio[name=select]", function() {
            if($('input[name=select]').is(':checked')) 
            { 
                $("#edit").parent().attr("href", absolute+"contacts/editContact/"+$('input[name=select]:checked').val());
            }
        });
        
        $("#edit").click(function(){
            if(!$('input[name=select]').is(':checked')) 
            {
                alert("Please select atleast one Record");
            }
        });
        $('#newnotes').click(function(e){
            var getModalId = $(this).attr("data-target");
            e.preventDefault();
            if($('input[name=select]').is(':checked')) 
            {
                $('#entity_type').val('contact');
                $('#entity_id').val($('input[name=select]:checked').val());
                $('#notes_for').html($('input[name=select'+$('input[name=select]:checked').val()+']').val());
                activatemodelwindow(getModalId);
            }
            else
            {
                alert("Please select atleast one Record");
            }
        });
        $('#deleteContact').click(function(e){
            e.preventDefault();
            if($('input[name=select]').is(':checked')) 
            {
                var confirm1 = confirm("Sure you want to deactivate the Contact");
                var Id=$('input[name=select]:checked').val();
                if(confirm1)
                {
                    $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>contacts/changeContactStatus/"+Id+'/20',//status 20 for deactivating notes
                    dataType: 'json',
                    success: function(responseText) {
                        $("#message").show(100);
                        if(!responseText.hasError){
                            $('#status_message').html(responseText.status);
                            loadContact();
                           }
                        else{
                            $('#status_message').html(responseText.errors);
                        }
                    }
                });
                    
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
	        <div class="container main contacts">
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
                    	<h3>Contacts Listing</h3>
                    </div>
                    <div class="span4 top-link-buttons">
                    	<a href="<?=BASE_URL?>/contacts/viewgroups" class="cus-buttons">View Groups</a>
                        <a href="<?=BASE_URL?>/contacts/add" class="cus-buttons"><b>+</b> Add New Contact</a>
                    </div>
                    <hr>
                </div>
                <div class="row action-buttons">
                	
                    <div class="span2">
                    	<div  class="inner">
                            <a>
                                <button rel="tooltip" data-toggle="tooltip" title="Edit" class="edit" id="edit"></button></a>
                            <button rel="tooltip" data-toggle="tooltip" title="Delete" class="delete" id="deleteContact"></button>
                        </div>
                    </div>
                    <div class="span3">
                    	<div  class="inner">
                            <button type="button" rel="tooltip" data-toggle="tooltip" title="Add Notes" class="add-notes activate-model" data-target="addNotes" id="newnotes"></button>
                        </div>
                    </div>
                </div>
                <div  id="table-data-container">
                    <div>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="conListing" style="width:100%;">
                            
                        </table>
                    </div>
                </div><!-- #table-data-container ends -->
	        </div><!-- cotainer ends -->
                <div class="modal-container">
            <?php include 'addnotes.php'; ?>
            <?php include 'addreference.php'; ?>
        </div>
<?php include 'footer.php'; ?>