<script type="text/javascript">
    $(document).ready(function() {
        $("body").on("click", ".notes a", function(e) {
            e.preventDefault(); 
            var confirm1 = confirm("Sure you want to deactivate the notes");
            var Id = $(this).attr("data-id");
            if (confirm1) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>contacts/deactivateNotes/"+Id,
                    dataType: 'json',
                    success: function(responseText) {
                        $("#message").show(100);
                        if(!responseText.hasError){
                            $('#val'+Id).remove();
                            if($('#candidatesnotes tr').length>'1')
                            {
                                //do nothing
                            }
                            else
                            {
                                $('#notesBody').html('<tr><td colspan="6">No Notes Available</td></tr>');
                            }
                            $('#status_message').html(responseText.status);
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
<div class="row">
    <div class="span12">
        <h5>Notes</h5>
    </div>
</div>
<div class="notes-table">
    <div>
        <table id="candidatesnotes">
            <thead>
                <tr>
                    <th>Notes Type</th>
                    <th>Date (Time)</th>
                    <th>Comments</th>
                    <th>Call Status</th>
                    <th>Time Spend</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="notesBody">
                <?php
                if ($notes_details) {
                    foreach ($notes_details as $value) {
                        ?>
                        <tr class="notes" id="val<?= $value->note_id ?>">
                            <td><?= $value->note_type ?></td>
                            <td><?php echo date('d-m-y', strtotime($value->insert_date)); ?></td>
                            <td><?= $value->comments ?></td>
                            <td><?= $value->call_status ?></td>
                            <td><?= $value->time_spend ?></td>
                            <td><a href="#" data-id="<?= $value->note_id ?>">Deactivate</a></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6">No Notes Available</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <input type="hidden" name="isNotesAvailable" id="isNotesAvailable" value="<?php
               if ($notes_details) {
                   echo 'true';
               } else {
                   echo 'false';
               }
               ?>">
    </div>
</div><!-- .notes-ends -->