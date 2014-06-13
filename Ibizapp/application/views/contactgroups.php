<?php include 'header.php';?>
<script>
$(document).ready(function(){	
	$("#groupName").on("click", "li:not(.current)", function ( event ) {
		$(".current", event.delegateTarget).removeClass("current");
		$(this).addClass("current");
		$('#groupDetails').html('');
		$('#groupNameDetail').html($(this).text());
		var data=$(this).attr('id');
		$("#editGroup").parent().attr("href", absolute+"Contacts/editGroup/"+data);
		$("#deleteGroup").parent().attr("href", absolute+"Contacts/deleteGroup/"+data);
		$.ajax({
			type: "POST",
			url: "<?php echo base_url()?>Contacts/getGroupEmails",
			data: {'id' : data},
			dataType: 'JSON',
			success: function(responseText){
				if(!responseText.hasError)
				{
					$('#groupDetails').html(responseText.status);
				}
				else
				{
					$('#groupDetails').html(responseText.errors);
				}
			}
		});
	});
	$('.button1').click(function() {
	window.location = "www.example.com/index.php?id=" + this.id;
	});
});
</script>
             <!-- App center content start -->
	        <div class="container main contacts groups">
				<div class="row" id="message" style="display: none">
					<div class="span12">
						<div class="alert alert-info">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong></strong> <span id="status_message"><?=$error_msg?></span>
						</div>
					</div>
				</div>
	            <div class="row heading">
                	<div class="span8">
                    	<h3>Contact Groups</h3>
                    </div>
                    <div class="span4 top-link-buttons">
                    	<a href="<?=BASE_URL?>/contacts/addgroups" class="cus-buttons"><b>+</b> Create New Group</a>
                    </div>
                    <hr>
                </div>
                <div class="row action-buttons">
                	<!--<div class="span1">
                    	<div class="inner">
                        	<label><input type="checkbox" id="selectAll"> All</label>
                        </div>
                    </div>-->
                    <div class="span2">
                    	<div  class="inner">
								<a>
								<button rel="tooltip" data-toggle="tooltip" id="editGroup" title="Edit" class="edit"></button>
								</a>
								<a>
								<button rel="tooltip" data-toggle="tooltip" id="deleteGroup" title="Delete" class="delete"></button>
								</a>
                        </div>
                    </div>
                    <!--<div class="span3">
                    	<div  class="inner">
                        	<button rel="tooltip" data-toggle="tooltip" title="Add Notes" class="add-notes"></button>
                        </div>
                    </div>-->
                </div>
                <div class="group-list-container">
                	<div class="row">
                    	<div class="span4">
                        	<h4>Group List</h4>
                            <ul class="groups" id="groupName" name="groupName">
								<?php foreach($group_names as $row) { ?>
								<li id="<?=$row->Group_Id?>">
                                	<div id="Group_Name"><?=$row->Group_Name?></div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="span8">
                        	<h4><span id="groupNameDetail"></span> Group Details</h4>
                        	<div id="groupDetails">
								<ul>
									<li>Please Select Group Name From Group List
									</li>
								</ul>
                            </div>
                        </div>
                    </div>
                </div>
	        </div><!-- cotainer ends -->
<?php include 'footer.php'; ?>
