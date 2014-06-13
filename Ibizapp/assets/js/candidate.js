$(function(){
	loadCandidate();
  });
  
  function loadCandidate()
  {
      $("#candListing").dataTable({
	"bProcessing": true,
        "bServerSide": true,
        "bRetrieve":false,
        "bDestroy":true,
        "sAjaxSource": absolute+'candidate/getCandidateGridList?aColumns[0]=Candidate_Id&aColumns[1]=Name&aColumns[2]=primary_skill&aColumns[3]=Cell&aColumns[4]=email&aColumns[5]=Location&aColumns[6]=Status&sIndexColumn=Candidate_Id',
        "aoColumns": [
        {
            "mDataProp": "Candidate_Id",
            "sTitle": "",
            "bSortable": false,
            "mRender": function ( url, type, full )  {
                //console.log(full);
                var status='';
                if(full.Status=='Inactive')
                {
                    status='false';
                }
                else if(full.Status=='Active')
                {
                    status='true';
                }
                return  '<input type="radio" name="select" value="'+full.Candidate_Id+'"><input type="hidden" name="select'+full.Candidate_Id+'" value="'+full.Name+'"><input type="hidden" name="isActive'+full.Candidate_Id+'" value="'+status+'">';}
	},
	{
            "mDataProp": "Name",
            "sTitle": "Candidate Name",
            "mRender": function ( url, type, full )  {
            return  '<a href="'+absolute+'candidate/details/'+full.Candidate_Id+'">' + url + '</a>';},
	},
        {
            "mDataProp": "primary_skill",
            "sTitle": "Skills"
	},
        {
            "mDataProp": "Cell",
            "sTitle": "Contact No"
	},
        {
            "mDataProp": "email2",
            "sTitle": "Email"
	},
        {
            "mDataProp": "Location",
            "sTitle": "Location"
	},
	{
            "mDataProp": "Status",
            "sTitle": "Status"
	}]
	});
  }
