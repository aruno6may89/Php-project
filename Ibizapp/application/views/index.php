<?php include 'header.php';?>
<script src="<?=JS_DIR?>company.js"></script>
<script type="text/javascript">
$(document).ready(function() { 
        $("body").on( "click", "input:radio[name=select]", function() {
            if($('input[name=select]').is(':checked')) 
            { 
                $("#edit").parent().attr("href", absolute+"company/editCompanies/"+$('input[name=select]:checked').val());
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
                $('#entity_type').val('company');
                $('#entity_id').val($('input[name=select]:checked').val());
                $('#notes_for').html($('input[name=select'+$('input[name=select]:checked').val()+']').val());
                activatemodelwindow(getModalId);
            }
            else
            {
                alert("Please select atleast one Record");
            }
        });
        
        $('#newContact').click(function(e){
            var getModalId = $(this).attr("data-target");
            console.log(getModalId);
            e.preventDefault();
            if($('input[name=select]').is(':checked')) 
            {
                $('#company_id').val($('input[name=select]:checked').val());
                $('#contact_for').html($('input[name=select'+$('input[name=select]:checked').val()+']').val());
                activatemodelwindow(getModalId);
            }
            else
            {
                alert("Please select atleast one Record");
            }
        });
        $('#deleteCompany').click(function(e) {
            e.preventDefault();
            if ($('input[name=select]').is(':checked'))
            {
                if ($('input[name=isActive' + $('input[name=select]:checked').val() + ']').val() == 'true')
                {
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
                                    loadCompany();
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
                    alert("Selected Company is already Inactive");
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
	        <div class="container main Candidates">
	            <div class="row heading">
                	<div class="span8">
                    	<h3>Companies Listing</h3>
                    </div>
                    <!--<div class="span1">
                    	<button class="cus-buttons">S</button>
                    </div>-->
                    <div class="span4">
                    	<a href="<?=BASE_URL?>/company/add" class="cus-buttons"><b>+</b> Add New Company</a>
                    </div>
                    <hr>
                </div>
                <div class="row action-buttons">
                    <div class="span2">
                    	<div  class="inner">
                            <a>
                                <button rel="tooltip" data-toggle="tooltip" title="Edit" class="edit" id="edit"></button> 
                            </a>
                            <?php
                            if($this->session->userdata('Role') == "Admin"||$this->session->userdata('Role') == "Recruiter_Manager")
                            {
                            ?>
                            <button rel="tooltip" data-toggle="tooltip" title="Delete" class="delete" id="deleteCompany"></button>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="span3">
                    	<div  class="inner">
                            <button type="button" rel="tooltip" data-toggle="tooltip" title="Add Notes" class="add-notes activate-model" data-target="addNotes" id="newnotes"></button>
                            <button type="button" rel="tooltip" data-toggle="tooltip" title="Add Contact" class="add-contact activate-model" data-target="addcontact" id="newContact"></button>
                        </div>
                    </div>
                </div>
                <div  id="table-data-container">
                    <div>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="compListing" style="width:100%;">
                           
                        </table>
                    </div>
                </div><!-- #table-data-container ends -->
	        </div><!-- cotainer ends -->
                <div class="modal-container">
            <?php include 'addnotes.php'; ?>
            <?php include 'addContactModel.php';?>
            </div>
<?php include 'footer.php'; ?>  
        