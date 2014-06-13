<?php include 'header.php';?>
             <!-- App center content start -->
	        <div class="container main contacts">
	            <div class="row heading">
                	<div class="span8">
                    	<h3>Contact Groups</h3>
                    </div>
                    <div class="span4 top-link-buttons">
                    	<!--<a href="newgroup.html" class="cus-buttons"><b>+</b> Create New Group</a>-->
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
                        	<button rel="tooltip" data-toggle="tooltip" title="Delete" class="delete"></button>
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
                        <div class="span12">
                        	<h4><span>AC Infotech</span> Group edit</h4>
                            <form class="group-edit">
                            	<fieldset>
                                	<div>
                                    	<select multiple="multiple" class="multi-select" name="my-select[]">
                                          <option value=''>elem 1</option>
                                          <option value='elem_2'>elem 2</option>
                                          <option value='elem_3'>elem 3</option>
                                          <option value='elem_4'>elem 4</option>
                                          <option value='elem_100'>elem 100</option>
                                        </select>
                                    </div>
                                    <div class="center">
                                    	<button type="button" class="">Cancel</button>
                                        <button type="submit" class="cus-buttons">Save Changes</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
	        </div><!-- cotainer ends -->
<?php include 'footer.php'; ?>