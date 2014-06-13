<?php include 'header.php'; ?>
<script src="<?=JS_DIR?>/submitResume.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
        $("#submitResume_Add").click(function(){
            if($('input[id=selectCheck]').is(':checked')) 
            {
                event.preventDefault();
                var val = [];
                $(':checkbox:checked').each(function(i){
                    val[i] = $(this).val();
                });
                
                $.ajax({ url: absolute+'requirements/saveSubmitResume',
                        data: {cand_id: val, req_id: <?=$requirement_details->requirement_id?>},
                        type: 'post',
                        dataType: 'json',
                        success: function(responseText) {
                            //console.log(responseText);
                            $("#message").show(100);
                            $('#submitResume_Add').removeAttr('disabled');
                            if(!responseText.hasError)
                            {
                                    $('#status_message').html(responseText.status);
                            }
                            else
                            {
                                    $('#status_message').html(responseText.errors);
                            }
                        }
                });
            }
            else
            {
                alert('Please select atleast one Record');
            }
        
        });
    });

</script>
             <!-- App center content start -->
	        <div class="container main candidates">
            	
	            <div class="row heading">
                	<div class="span8">
                    	<h3>Submit Candidates for <?=$requirement_details->Position_title?></h3>
                    </div>
                    <hr>
                </div>
                <div class="row" id="message" style="display: none">
                    <div class="span12">
                    <div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong></strong> <span id="status_message"></span>
                    </div>
                    </div>
                </div>
                <div class="row action-buttons">
                	<div class="span1">
                    	<div class="inner">
                        	<label><input type="checkbox" id="selectAll"> All</label>
                        </div>
                    </div>
                    <div class="span3">
                    	<div  class="inner">
                        	<button class="cus-buttons" id="submitResume_Add" name="submitResume_Add">Submit Selected Candidates</button>
                        </div>
                    </div>
                </div>
                <div  id="table-data-container">
                    
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="submitCandListing" style="width:100%;">
                        </table>
                    
                </div><!-- #table-data-container ends -->
	        </div><!-- cotainer ends -->
        </div> <!-- .app-main ends -->
        <?php include 'footer.php'; ?>
    
