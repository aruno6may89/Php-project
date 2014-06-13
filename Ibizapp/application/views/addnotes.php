<script type="text/javascript">
    $(document).ready(function() {
        $("#addNewNotes").validate({
            debug: true,
            errorElement: 'span',
            rules:
                    {
                        notes: {
                            required: true
                        },
                        notesType: {
                            required: {
                                depends: function() {
                                    if ($('input[name=notesType]:checked').length <= 0)
                                        return true;
                                }
                            }
                        },
                        call_status: {
                            required: {
                                depends: function() {
                                    if ($('input[name=notesType]:checked').val() == 'call')
                                        return true;
                                }
                            }
                        },
                        time_spend: {
                            required: {
                                depends: function() {
                                    if ($('input[name=notesType]:checked').val() == 'call')
                                        return true;
                                }
                            }
                        }
                    },
            messages:
                    {
                        notes: {
                            required: 'Please Enter Notes'
                        },
                        notesType: {
                            required: 'Please Select Notes Type'
                        },
                        call_status: {
                            required: 'Please Select Call Status'
                        },
                        time_spend: {
                            required: 'Please Select Time Spend'
                        }
                    },
            errorPlacement: function(error, element) {
                if (element.attr('id') === "notes")
                {
                    error.appendTo(".notes");
                }
                else if (element.attr('name') === "notesType")
                {
                    error.appendTo(".notesType");
                }
                else if (element.attr('id') === "call_status")
                {
                    error.appendTo(".call_status");
                }
                else if (element.attr('id') === "time_spend")
                {
                    error.appendTo(".time_spend");
                }
            },
            submitHandler: function(form)
            {
                //$('#submit').click(function() {
                $('#addnotes_submit').attr("disabled", "disabled");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>contacts/saveNotes",
                    data: $('#addNewNotes').serialize(),
                    dataType: 'json',
                    success: function(responseText) {
                        $("#notes_message").show(100);
                        $('#addnotes_submit').removeAttr('disabled');
                        if (!responseText.hasError)
                        {
                            $('#notes_status_message').html(responseText.status);
                            $("#addNewNotes")[0].reset();
                            if ($('#isDetails').val() == 'true')//check wheather it is deatails page
                            {
                                if ($('#isNotesAvailable').val() == 'true')//check wheather notes avalable or not
                                {
                                    $('#notesBody').prepend(responseText.ajaxDataNotes);//if available append the available data
                                }
                                else if($('#isNotesAvailable').val() == 'false')
                                {
                                    $('#notesBody').html(responseText.ajaxDataNotes);
                                    $('#isNotesAvailable').val('true');
                                }
                            }
                            setTimeout(function() {
                                closemodalwindow('addNotes');
                                $("#notes_message").hide();
                                $("#hide_call").hide();
                            }, 3000);
                        }
                        else
                        {
                            $('#notes_status_message').html(responseText.errors);
                            setTimeout(function() {
                                $("#notes_message").hide();
                                $("#hide_call").hide();
                            }, 3000);
                        }
                    }
                });
                return false;
            }
        });
        $("#call").click(function() {
            $("#hide_call").show();
        });
        $("#general").click(function() {
            $("#hide_call").hide();
        });
    });
</script>
<div id="addNotes" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="addNotes" aria-hidden="true">
    <div class="row" id="notes_message" style="display: none">
        <div class="span12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong></strong> <span id="notes_status_message"></span>
            </div>
        </div>
    </div>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Add Notes for <span id="notes_for"></span></h3>
    </div>
    <div class="modal-body">
        <form action="" id="addNewNotes" method="POST">
            <div>
                <label>Notes</label>
                <textarea id="notes" name="notes"></textarea>
                <span class="b-error notes"></span>
            </div>
            <div>
                <label>Notes type</label>
                <div>
                    <label class="radio">
                        <input type="radio" name="notesType" id="general" value="General" >
                        General
                    </label>
                    <label class="radio">
                        <input type="radio" name="notesType" id="call" value="Call" >
                        Call
                    </label>
                    <span class="b-error notesType"></span>
                    <div id="hide_call" style="display:none">
                        <label for="call_status">Call Status</label>
                        <select name="call_status" id="call_status" >
                            <option value="">-- Select --</option>
                            <?php foreach ($this->callStatus as $row) { ?>
                                <option value="<?= $row->name ?>"><?= $row->name ?></option>
                            <?php } ?>
                        </select>
                        <span class="b-error call_status"></span>
                        <label for="time_spend">Time Spend</label>
                        <select name="time_spend" id="time_spend" >
                            <option value="">-- Select --</option>
                            <?php foreach ($this->timeSpend as $row) { ?>
                                <option value="<?= $row->name ?>"><?= $row->name ?></option>
                            <?php } ?>
                        </select>
                        <span class="b-error time_spend"></span>
                        <input type="hidden" name="entity_id" id="entity_id" value="<?php if (isset($entity_id)) echo $entity_id; ?>" >
                        <input type="hidden" name="entity_type" id="entity_type" value="<?php if (isset($entity_type)) echo $entity_type ?>" >
                        <input type="hidden" name="isDetails" id="isDetails" value="<?php if (isset($entity_type)) {
                               echo 'true';
                           } else {
                               echo 'false';
                           } ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="cus-buttons pad-five" id="addnotes_submit" type="submit">Save notes</button>
                </div>
        </form>
    </div>
</div><!-- end of  id="addNotes" -->
</div>
</div>