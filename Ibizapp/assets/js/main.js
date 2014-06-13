jQuery.validator.addMethod("isnumber", function(value, element) {
    if (value) {
        return jQuery.isNumeric(value);
    }
    else
    {
        return true;
    }
}, "Numbers and symbols only allowed"
        );

jQuery.validator.addMethod("urlvalidation", function(value, element) {
    if (value) {
        return /(http(s)?:\\)?([\w-]+\.)+[\w-]+[.com|.in|.org]+(\[\?%&=]*)?/.test(value);
    }
    else
    {
        return true;
    }
}, "Enter valid URL"
        );

jQuery.validator.addMethod("nowhitespace", function(value, element) {

    return this.optional(element) || /^\S+$/i.test(value);

}, "No white space please");

jQuery.validator.addMethod("letterswithbasicpunc", function(value, element) {
    return this.optional(element) || /^[a-z-.,()'\"\s]+$/i.test(value);
}, "Letters or punctuation only please");

jQuery.validator.addMethod("charactersonly", function(value, element) {
    return this.optional(element) || /^[A-Z]'?[- a-zA-Z]+$/i.test(value);
}, "Letters only please");

jQuery.validator.addMethod("namespace", function(value, element) {
    return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);
}, "Letters only please");

jQuery.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Letters only please");
jQuery.validator.addMethod("letterspace", function(value, element) {
    return this.optional(element) || /^[a-z\s]*$/i.test(value);
}, "Letters only please");
jQuery.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^\w+$/i.test(value);
}, "Letters, numbers, spaces or underscores only please");
jQuery.validator.addMethod("isdigit", function(value, element) {
    return this.optional(element) || /^[( ) 0-9 -]+$/.test(value);
}, "Numbers and symbols only allowed");

// Table initialisation /^[a-zA-Z ]*$/
$(document).ready(function() {

    $('#candListing').dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page"
        },
        "bAutoWidth": false, // Disable the auto width calculation 
        "aoColumns": [
            {"sWidth": "3%"}, // 1st column width 
            {"sWidth": "15%"}, // 2nd column width 
            {"sWidth": "25%"}, // 3rd column width and so on
            {"sWidth": "12%"},
            {"sWidth": "25%"},
            {"sWidth": "10%"},
            {"sWidth": "10%"}
        ],
        "aoColumnDefs": [
            {"bSortable": false, "aTargets": [0]}
        ]
    });

    $('#conListing').dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page"
        },
        "bAutoWidth": false, // Disable the auto width calculation 
        "aoColumns": [
            {"sWidth": "3%"}, // 1st column width 
            {"sWidth": "15%"}, // 2nd column width 
            {"sWidth": "35%"}, // 3rd column width and so on
            {"sWidth": "17%"},
            {"sWidth": "20%"},
            {"sWidth": "10%"},
        ],
        "aoColumnDefs": [
            {"bSortable": false, "aTargets": [0]}
        ]
    });

    $('#compListing').dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page"
        },
        "bAutoWidth": false, // Disable the auto width calculation 
        "aoColumns": [
            {"sWidth": "3%"}, // 1st column width 
            {"sWidth": "30%"}, // 2nd column width 
            {"sWidth": "25%"}, // 3rd column width and so on
            {"sWidth": "30%"},
            {"sWidth": "12%"}

        ],
        "aoColumnDefs": [
            {"bSortable": false, "aTargets": [0]}
        ]
    });
    $('#reqListing').dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page"
        },
        "bAutoWidth": false, // Disable the auto width calculation 
        "aoColumns": [
            {"sWidth": "3%"}, // 1st column width 
            {"sWidth": "20%"}, // 2nd column width 
            {"sWidth": "30%"}, // 3rd column width and so on
            {"sWidth": "12%"},
            {"sWidth": "15%"},
            {"sWidth": "13%"},
            {"sWidth": "7%"}

        ],
        "aoColumnDefs": [
            {"bSortable": false, "aTargets": [0]}
        ]
    });

    // Hide the span error block in forms
    $("span.help-block").hide();

    // To enable tool tip - Note: we ever the tool tip is need, use rel="tooltip" in that element
    $('[rel=tooltip]').tooltip();

    // Generic code to Raise the modal window for add notes
    /*$(".activate-model").click(function(e) {
     e.preventDefault();
     var getModalId = $(this).attr("data-target");
     console.log(getModalId);
     $("#" + getModalId ).modal();
     
     });	*/

    // Hide and show add company form in add reference modal window in candidate details screen
    $(".create-new-company-container").hide();

    /*$("#searchCompany button.cus-buttons").click(function(e) {
        $("#searchResult").show();
    });*/

    $(".l-create-new-company").click(function(e) {
        $("#searchResult, #searchCompany, #addReference .modal-footer").hide();
        $(".create-new-company-container").show();
    });

    $(".create-new-company-container .cancel").click(function(e) {
        $(".create-new-company-container").hide();
        $("#refCreateNewCompany")[0].reset();
        $("#searchCompany, #addReference .modal-footer").show();
    });

    $(".modal-header .close").click(function(e) {
        $("#searchCompany")[0].reset();
        $("#searchResult").empty().hide();
    });

    // redirect to Edit group candidate list page when click on edit in Group listing page
    /*$(".groups .edit").click(function(e) {
        window.location.href = "contactgroups-edit.html";
    });*/

    $('.mdate').datepicker({targetElement: '#date_picker_modal'});

    $(".chosen-select").chosen({
        disable_search_threshold: 15,
        no_results_text: "Oops, nothing found!",
        width: "200px"
    });

    //bootstrap datepicker
    $('.bdate').datepicker({targetElement: '#date_picker'}).on('changeDate', function(ev) {
        $(".date").html('');
        $(this).removeClass('error');
    });
    // Prevent the backspace key from navigating back.
    /* $(document).unbind('keydown').bind('keydown', function (event) {
     var doPrevent = false;
     if (event.keyCode === 8) {
     var d = event.srcElement || event.target;
     if ((d.tagName.toUpperCase() === 'INPUT' && (d.type.toUpperCase() === 'TEXT' || d.type.toUpperCase() === 'PASSWORD' || d.type.toUpperCase() === 'FILE')) 
     || d.tagName.toUpperCase() === 'TEXTAREA') {
     doPrevent = d.readOnly || d.disabled;
     }
     else {
     doPrevent = true;
     }
     }
     
     if (doPrevent) {
     event.preventDefault();
     }
     });*/

});

function activatemodelwindow(dataTarget)
{
    $("#" + dataTarget).modal();
}
function closemodalwindow(dataTarget)
{
    $('#' + dataTarget).modal('toggle');
}


