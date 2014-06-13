<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Company extends MY_Session_Controller {

    private $page = "company";

    public function index() {
        $this->load->model('lookup_model');
        $data['page'] = $this->page;
        $data['designation'] = $this->lookup_model->getDisplayList('designation');
        $this->load->view('index', $data);
    }

    public function add() {
        $this->load->model('lookup_model');
        $this->states = $this->lookup_model->getDisplayList('state');
        $this->business = $this->lookup_model->getDisplayList('business');
        $this->companyType = $this->lookup_model->getDisplayList('comptype');
        $this->duration = $this->lookup_model->getDisplayList('duration');
        $this->immiStatus = $this->lookup_model->getDisplayList('immi_status');
        $this->taxTerm = $this->lookup_model->getDisplayList('tax_term');
        $this->positionType = $this->lookup_model->getDisplayList('position_type');
        $this->designation = $this->lookup_model->getDisplayList('designation');
        $this->role = $this->lookup_model->getDisplayList('role');
        $this->timeFrame = $this->lookup_model->getDisplayList('time_frame');
        $this->timeFrameDesc = $this->lookup_model->getDisplayList('time_frame_desc');
        $this->priority = $this->lookup_model->getDisplayList('priority');
        $this->callStatus = $this->lookup_model->getDisplayList('call_status');
        $this->timeSpend = $this->lookup_model->getDisplayList('time_spend');
        $this->statesAbbr = $this->lookup_model->getDisplayList('location');
        $this->company_status = $this->lookup_model->getStatus('COMP');
        $this->ndasigned = $this->lookup_model->getDisplayList('nda');
        $data['page'] = $this->page;
        $this->load->view('addCompany', $data);
    }
    
    public function getAllCompaniesAjax() {
        $this->load->model('company_model');
        $company = $this->company_model->getCompanies();
        $str='<option value=""></option>';
        foreach ($company as $row) {
            $str.='<option value="'.$row->Company_ID.'">'.$row->Name.'</option>';
        }
        echo $str;
    }

    public function details($company_id) {
        $this->load->model('company_model');
        $this->load->model('contact_model');
        $this->load->model('notes_model');
        $this->load->model('lookup_model');
        $data['page'] = $this->page;
        $data['company_details'] = $this->company_model->getCompanyDetails($company_id);
        $data['contact_details'] = $this->contact_model->getCompanyContacts($company_id);
        $data['address_details'] = $this->contact_model->getCompanyAddress($company_id);
        $data['designation'] = $this->lookup_model->getDisplayList('designation');
        $data['notes_details'] = $this->notes_model->getallnotes($company_id, 'company');
        $data['company_id'] = $company_id;
        $this->load->view('companydetails', $data);
    }

    public function editCompanies($company_id) {
        $this->load->model('lookup_model');
        $this->load->model('company_model');
        $this->load->model('contact_model');
        $this->load->model('notes_model');
        $this->states = $this->lookup_model->getDisplayList('state');
        $this->business = $this->lookup_model->getDisplayList('business');
        $this->companyType = $this->lookup_model->getDisplayList('comptype');
        $this->duration = $this->lookup_model->getDisplayList('duration');
        $this->immiStatus = $this->lookup_model->getDisplayList('immi_status');
        $this->taxTerm = $this->lookup_model->getDisplayList('tax_term');
        $this->positionType = $this->lookup_model->getDisplayList('position_type');
        $this->designation = $this->lookup_model->getDisplayList('designation');
        $this->role = $this->lookup_model->getDisplayList('role');
        $this->timeFrame = $this->lookup_model->getDisplayList('time_frame');
        $this->timeFrameDesc = $this->lookup_model->getDisplayList('time_frame_desc');
        $this->priority = $this->lookup_model->getDisplayList('priority');
        $this->callStatus = $this->lookup_model->getDisplayList('call_status');
        $this->timeSpend = $this->lookup_model->getDisplayList('time_spend');
        $this->statesAbbr = $this->lookup_model->getDisplayList('location');
        $this->comp_status = $this->lookup_model->getStatus('COMP');
        $data['page'] = $this->page;
        $data['company_details'] = $this->company_model->getCompanyDetails($company_id);
        $data['company_details'] = $this->replaceSymbolsWithEmptyValues('-', $data['company_details']);
        $data['contact_details'] = $this->contact_model->getCompanyContacts($company_id);
        $data['address_details'] = $this->contact_model->getCompanyAddress($company_id);
        $data['notes_details'] = $this->notes_model->getallnotes($company_id, 'company');
        $data['company_id'] = $company_id;
        $this->load->view('editcompanies', $data);
    }

    public function getGridDetails() {
        $this->load->model('company_model');
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = $_GET['aColumns'];

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = $_GET['sIndexColumn'];

        /* DB table to use */
        //$sTable = $_GET['sTable'];

        /*
         * Paging
         */

        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = " LIMIT " . intval($_GET['iDisplayStart']) . ", " .
                    intval($_GET['iDisplayLength']);
        }


        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
                            ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }


        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= "A.`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`A." . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }

        /*
         * SQL queries
         * Get data to display
         */
        if ($this->session->userdata('Role') == "Admin"||$this->session->userdata('Role') == "Recruiter_Manager") {
            $is_admin = true;
        } else {
            $is_admin = false;
        }
        $rResult = $this->company_model->getGridDetails($aColumns, $sWhere, $sOrder, $sLimit, $is_admin);

        //$rResult = mysqli_query( $sQuery ) or die(mysqli_error());

        /* Data set length after filtering */

        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $rResult['count'],
            "iTotalDisplayRecords" => $rResult['count'],
            "aaData" => array()
        );
        $output['aaData'] = $rResult['companyList'];
        echo json_encode($output);
    }

    public function getCompanyContacts($company_id) {
        $this->load->model('contact_model');
        $selectData = '<option value=""></option>';
        $contacts = $this->contact_model->getCompanyContacts($company_id);
        if ($contacts) {
            foreach ($contacts as $contact) {
                $selectData.='<option value="' . $contact->Reporting_PersonID . '">' . $contact->contact_name . '</option>';
            }
        }
        $return = array('selectData' => $selectData);
        echo json_encode($return);
    }

    public function saveNewCompany() {
        $status = '';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cname', 'Company Name', 'required|trim');
        $this->form_validation->set_rules('ctype', 'Company Type', 'required|trim');
        $this->form_validation->set_rules('sdate', 'Nature of Business', 'trim');
        $this->form_validation->set_rules('cstatus', 'Company Status', 'required|trim');
        $this->form_validation->set_rules('ndasign', 'NDA Signed', 'trim');
        $this->form_validation->set_rules('add1', 'Address1', 'trim');
        $this->form_validation->set_rules('add2', 'Address2', 'trim');
        $this->form_validation->set_rules('city', 'City', 'trim');
        $this->form_validation->set_rules('state', 'State', 'trim');
        $this->form_validation->set_rules('signdate', 'NDA Signed Date', 'trim');
        $this->form_validation->set_rules('ndaby', 'NDA Signed By', 'trim');
        $this->form_validation->set_rules('ptitle', 'Position Title', 'trim');
        $this->form_validation->set_rules('wurl', 'Web URL', 'trim');
        if ($this->form_validation->run()) {
            if (strtotime(date("Y-m-d")) <= strtotime($this->input->post('signdate'))) {
                $this->ibizErrors[] = 'Please Check Given Signed Date';
            } else {

                $this->load->model('company_model');
                $this->load->model('contact_model');
                if ($this->company_model->checkforExistingCompany($this->input->post('cname'))) {
                    $this->ibizErrors[] = 'Company Name ' . $this->input->post('cname') . ' exists change Company Name';
                } else {
                    $addressdata = array(
                        'Address_1' => $this->input->post('add1'),
                        'Address_2' => $this->input->post('add2'),
                        'City' => $this->input->post('city'),
                        'State' => $this->input->post('state'),
                        'Zip' => $this->input->post('zip'),
                        'Cell' => '',
                        'Work_Phone' => '',
                        'Fax' => '',
                        'Home_Phone' => '',
                        'Created_Date' => date('Y-m-d'),
                        'Created_By' => $this->session->userdata('Recruiter_ID')
                    );
                    $addressdata = $this->replaceEmptyValuesNull($addressdata);
                    $addressid = $this->contact_model->insertAddressDetails($addressdata);
                    $companydata = array(
                        'Name' => $this->input->post('cname'),
                        'Status' => $this->input->post('cstatus'),
                        'Nature_Of_Business' => $this->input->post('sdate'),
                        'Address_id' => $addressid,
                        'Url' => $this->input->post('wurl'),
                        'Created_Date' => date('Y-m-d'),
                        'Created_By' => $this->session->userdata('Recruiter_ID'),
                        'Company_type' => $this->input->post('ctype'),
                        'NDA_Signed' => $this->input->post('ndasign'),
                        'NDA_Signed_By' => $this->input->post('ndaby'),
                        'Position_Title' => $this->input->post('ptitle'),
                        'Signed_Date' => date('Y-m-d', strtotime($this->input->post('signdate')))
                    );
                    $companydata = $this->replaceEmptyValuesNull($companydata);
                    $companyid = $this->company_model->insertCompanyDetails($companydata);
                    $status = 'Company Details added Successfully';
                }
            }
        } else {
            $this->ibizErrors[] = validation_errors();
        }

        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status
        );
        echo json_encode($return);
    }

    public function changeCompanyStatus($company_id, $status_id,$alreadyStatusUpdated=false,$currentCompanyStatus=false) {
        $this->load->model('company_model');
        $this->load->model('contact_model');
        $this->load->model('requirements_model');
        if(!$alreadyStatusUpdated)
        {
            $currentCompanyStatus=$this->company_model->getCompanyCurrentStatus($company_id);
            $where = array(
                'Company_ID' => $company_id
            );
            $data = array(
                'Status' => $status_id,
                'status_change_date' => date('Y-m-d')
            );
            $this->company_model->updateCompanyDetails($data, $where);
        }
        if ($status_id == 1) {//active company
            $contact_status_id = 19; //active contact
            $requirement_status_id = 14; //active requirement
            $contactWhere=array(
                'company_id' => $company_id,
                'Status'=>20,
                'status_change_date'=>$currentCompanyStatus->status_change_date
            );
            $reqWhere=array(
                'company_id' => $company_id,
                'Status'=>21,
                'status_change_date'=>$currentCompanyStatus->status_change_date
            );
        } else {//other than active status company
            $contact_status_id = 20; //inactive contact
            $requirement_status_id = 21; //inactive requirement
            $contactWhere=array(
                'company_id' => $company_id,
                'Status'=>19
            );
            $reqWhere=array(
                'company_id' => $company_id,
                'Status'=>14,
            );
        }
        $contactData = array(
            'Status' => $contact_status_id,
            'status_change_date' => date('Y-m-d')
        );
        $requirementData = array(
            'status' => $requirement_status_id,
            'status_change_date' => date('Y-m-d')
        );
        $this->contact_model->updateContactDetails($contactData, $contactWhere);
        $this->requirements_model->updateRequirementsDetails($requirementData, $reqWhere);
        //$reqDetials=$this->requirements_model->getUpdatedRequirementDetails($requirement_status_id,$company_id,date('Y-m-d'));
        
        //$this->contact_model->updateContact($contactData, $contactWhere);
        //redirect('main/companies');
    }

    public function updateCompany() {
        $status = '';
        $stausChange=false;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cname', 'Company Name', 'required|trim');
        $this->form_validation->set_rules('ctype', 'Company Type', 'required|trim');
        $this->form_validation->set_rules('sdate', 'Nature of Business', 'trim');
        $this->form_validation->set_rules('cstatus', 'Company Status', 'required|trim');
        $this->form_validation->set_rules('ndasign', 'NDA Signed', 'trim');
        $this->form_validation->set_rules('add1', 'Address1', 'trim');
        $this->form_validation->set_rules('add2', 'Address2', 'trim');
        $this->form_validation->set_rules('city', 'City', 'trim');
        $this->form_validation->set_rules('state', 'State', 'trim');
        $this->form_validation->set_rules('signdate', 'NDA Signed Date', 'trim');
        $this->form_validation->set_rules('ndaby', 'NDA Signed By', 'trim');
        $this->form_validation->set_rules('ptitle', 'Position Title', 'trim');
        $this->form_validation->set_rules('wurl', 'Web URL', 'trim');

        if ($this->form_validation->run()) {
            $this->load->model('company_model');
            $this->load->model('contact_model');
            $wherecom = array(
                'Company_ID' => $this->input->post('company_id'),
            );
            $whereadd = array(
                'Address_Id' => $this->input->post('address_id'),
            );
            if ($this->company_model->checkforExistingCompany($this->input->post('cname'), $this->input->post('company_id'))) {
                $this->ibizErrors[] = 'Company Name ' . $this->input->post('cname') . ' exists change Company Name';
            } else {
                $addressdata = array(
                    'Address_1' => $this->input->post('add1'),
                    'Address_2' => $this->input->post('add2'),
                    'City' => $this->input->post('city'),
                    'State' => $this->input->post('state'),
                    'Zip' => $this->input->post('zip'),
                    'Cell' => $this->input->post('pPhone'),
                    'Work_Phone' => $this->input->post('wPhone'),
                    'Fax' => '',
                    'Home_Phone' => '',
                    'Modified_Date' => date('Y-m-d'),
                    'Modified_By' => $this->session->userdata('Recruiter_ID')
                );

                $addressid = $this->contact_model->updateAddressDetails($addressdata, $whereadd);

                $companydata = array(
                    'Name' => $this->input->post('cname'),
                    'Nature_Of_Business' => $this->input->post('sdate'),
                    'Url' => $this->input->post('wurl'),
                    'Company_type' => $this->input->post('ctype'),
                    'NDA_Signed' => $this->input->post('ndasign'),
                    'NDA_Signed_By' => $this->input->post('ndaby'),
                    'Position_Title' => $this->input->post('ptitle'),
                    'Signed_Date' => date('Y-m-d', strtotime($this->input->post('signdate'))),
                    'Modified_Date' => date('Y-m-d'),
                    'Modified_By' => $this->session->userdata('Recruiter_ID')
                );
                if ($this->session->userdata('Role') == "Admin"||$this->session->userdata('Role') == "Recruiter_Manager") {
                    if($this->input->post('oldStatus')!=$this->input->post('cstatus'))
                    {
                        $companydata['Status'] = $this->input->post('cstatus');
                        $currentCompanyStatus=$this->company_model->getCompanyCurrentStatus($company_id);
                        $stausChange=true;
                    }
                }
                $companyid = $this->company_model->updateCompanyDetails($companydata, $wherecom);
                if($stausChange){
                    $this->changeCompanyStatus($this->input->post('company_id'), $this->input->post('cstatus'),true, $currentCompanyStatus);    
                }
                $status = 'Company Details updated Successfully';
            }
        } else {
            $this->ibizErrors[] = validation_errors();
        }

        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status
        );
        echo json_encode($return);
    }

}
