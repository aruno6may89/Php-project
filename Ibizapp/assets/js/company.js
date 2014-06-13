$(function(){
	loadCompany();
  });
  
  function loadCompany(){
      $("#compListing").dataTable({
	"bProcessing": true,
        "bServerSide": true,
        "bRetrieve":false,
        "bDestroy":true,
        "sAjaxSource": absolute+'company/getGridDetails?aColumns[0]=Company_ID&aColumns[1]=Name&aColumns[2]=Company_Type&aColumns[3]=Url&aColumns[4]=Status&sIndexColumn=Company_ID',
        "aoColumns": [
	{
            "mDataProp": "Company_ID",
            "sTitle": "",
            "bSortable": false,
            "mRender": function ( url, type, full )  {
                var status='';
                if(full.Status=='Inactive')
                {
                    status='false';
                }
                else if(full.Status=='Active')
                {
                    status='true';
                }
                return  '<input type="radio" name="select" value="'+full.Company_ID+'"><input type="hidden" name="select'+full.Company_ID+'" value="'+full.Name+'"><input type="hidden" name="isActive'+full.Company_ID+'" value="'+status+'">';}
	},
        {
            "mDataProp": "Name",
            "sTitle": "Company Name",
            "mRender": function ( url, type, full )  {
            return  '<a href="'+absolute+'company/details/'+full.Company_ID+'">' + url + '</a>';}
	},
        {
            "mDataProp": "Company_Type",
            "sTitle": "Type"
	},
        {
            "mDataProp": "Url",
            "sTitle": "Website"
	},
	{
            "mDataProp": "Status",
            "sTitle": "Status"
	}]
	});
  }
