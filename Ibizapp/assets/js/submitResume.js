$(function(){
	$("#submitCandListing").dataTable({
	"bProcessing": true,
        "bServerSide": true,
        "bRetrieve":false,
        "bDestroy":true,
        "sAjaxSource": absolute+'requirements/candidateDetailsToSubmit',
        "aoColumns": [
        {
            "mDataProp": "candidate_id",
            "aTargets": [0],
            "sTitle": "Select Candidate",
            "bSortable": false,
            formatter: '<input type="checkbox" checked/>',
            "mRender": function ( url, type, full )  {
                return  '<input type="checkbox" name="selectCheck[]" id="selectCheck" value="'+full.candidate_id+'">';}
	},
	{
            "mDataProp": "Candidate_Name",
            "aTargets": [0],
            "sTitle": "Candidate Name",
            "mRender": function ( url, type, full )  {
            return  '<a href="'+absolute+'candidate/details/'+full.candidate_id+'">' + url + '</a>';}
	},
        {
            "mDataProp": "Skills",
            "aTargets": [1],
            "sTitle": "Skills"
	},
        {
            "mDataProp": "Contact_no",
            "aTargets": [2],
            "sTitle": "Contact no"
	},
        {
            "mDataProp": "Email",
            "aTargets": [3],
            "sTitle": "Email"
	},
        {
            "mDataProp": "Location",
            "aTargets": [4],
            "sTitle": "Location"
	}],
	});
  })
