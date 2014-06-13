$(function(){
	loadContact();
  })

function loadContact()
{
    $("#conListing").dataTable({
	"bProcessing": true,
        "bServerSide": true,
        "bRetrieve":false,
        "bDestroy":true,
        "sAjaxSource": absolute+'contacts/getContactGridList?aColumns[0]=Reporting_PersonID&aColumns[1]=Name&aColumns[2]=contact_type_name&aColumns[3]=phone&aColumns[4]=email&aColumns[5]=Designation',
        "aoColumns": [
        {
            "mDataProp": "Reporting_PersonID",
            "sTitle": "",
            
            "mRender": function ( url, type, full )  {
                return  '<input type="radio" name="select" value="'+full.Reporting_PersonID+'"><input type="hidden" name="select'+full.Reporting_PersonID+'" value="'+full.Name+'">';}
	},  
	{
            "mDataProp": "Name",
            "aTargets": [0],
            "mRender": function ( url, type, full )  {
            return  '<a href="'+absolute+'contacts/details/'+full.Reporting_PersonID+'">' + url + '</a>';},
            "sTitle": "Name"
	},
        {
            "mDataProp": "contact_type_name",
            "sTitle": "Contact Type - Name"
	},
        {
            "mDataProp": "phone",
            
            "sTitle": "Phone(s)"
	},
        {
            "mDataProp": "email",
            "aTargets": [3],
            "sTitle": "E-mail(s)"
	},
	{
            "mDataProp": "Designation",
            "sTitle": "Title"
	}
        ]
	});
}