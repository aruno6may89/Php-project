<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Requirements extends MY_Session_Controller {

    private $page = "requirement";

    public function index() {
        $data['page'] = $this->page;
        $this->load->view('requirements', $data);
    }

    public function add() {
        $this->load->model("company_model");
        $this->load->model('lookup_model');
        $data['page'] = $this->page;
        $data['company'] = $this->company_model->getCompanies(1);
        $data['time_frame'] = $this->lookup_model->getDisplayList('time_frame');
        $data['time_frame_desc'] = $this->lookup_model->getDisplayList('time_frame_desc');
        $data['tax_term'] = $this->lookup_model->getDisplayList('tax_term');
        $data['requirement_status'] = $this->lookup_model->getStatus('JOB');
        $data['priority'] = $this->lookup_model->getDisplayList('priority');
        $this->load->view('addRequirement', $data);
    }

    public function details($requirement_id) {
        $this->load->model('requirements_model');
        $this->load->model('notes_model');
        $this->load->model('lookup_model');
        $data['page'] = $this->page;
        $data['requirement_details'] = $this->requirements_model->getRequirementDetails($requirement_id);
        $data['submited_candidates'] = $this->requirements_model->getReqSubmitList($requirement_id);
        $data['notes_details'] = $this->notes_model->getallnotes($requirement_id, 'requirement');
        $data['submit_status'] = $this->lookup_model->getStatus('SUBMISSION');
        $data['requirement_id'] = $requirement_id;
        $this->load->view('requirementdetails', $data);
    }

    public function submitResume($requirement_id) {
        $this->load->model('requirements_model');
        $data['page'] = $this->page;
        $data['requirement_details'] = $this->requirements_model->getRequirementDetails($requirement_id);
        //$data['candidate_details']=$this->requirements_model->getAllCandidateDetails();
        $this->load->view('requirement-candidate-submit', $data);
    }

    public function saveSubmitResume() {
        $cand_id = $this->input->post('cand_id');
        $req_id = $this->input->post('req_id');
        $status = '';
        $this->load->model('requirements_model');

        foreach ($cand_id as $value) {
            $resume_id = '';
            $Line_No = '';
            $Line_No = $this->requirements_model->get_Line_No($value, $req_id);
            $resume_id = $this->requirements_model->get_resume_id($value);
            if (!isset($resume_id->resume_id))
                $resume_id->resume_id = 'null';

            $submitdata = array(
                'Line_No' => $Line_No,
                'Candidate_Id' => $value,
                'Requirement_ID' => $req_id,
                'Submitted_Date' => date('Y-m-d'),
                'Submitted_Status' => 24,
                'Submitted_By' => $this->session->userdata('Recruiter_ID'),
                'resume_id' => $resume_id->resume_id
            );
            $submitdata = $this->replaceEmptyValuesNull($submitdata);
            $submitId = $this->requirements_model->insertSubmitResumeDetails($submitdata);
            if ($Line_No > 1) {
                $Line_No = $Line_No - 1;
                $whereData = array(
                    'Line_No' => $Line_No,
                    'Candidate_Id' => $value,
                    'Requirement_ID' => $req_id
                );
                $updateData = array(
                    'Submitted_Status' => 28,
                    'Status_change_date' => date('Y-m-d'),
                    'Modified_By' => $this->session->userdata('Recruiter_ID')
                );
                $this->requirements_model->updateSubmitedResumeStaus($updateData, $whereData);
            }
            $status = 'Sucessfuly submited Candidate Submited';
        }

        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status
        );
        echo json_encode($return);
    }

    public function candidateDetailsToSubmit() {
        $this->load->model('candidate_model');
        $aColumns = array(
            '0' => 'Candidate_Name',
            '1' => 'primary_skill',
            '2' => 'Work_Phone',
            '3' => 'email1',
            '4' => 'location',
            '5' => 'Status'
        );

        /* Indexed column (used for fast and accurate table cardinality) */
        //$sIndexColumn = $_GET['sIndexColumn'];

        /* DB table to use */
        //$sTable = $_GET['sTable'];

        /*
         * Paging
         */

        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
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
                $sWhere .= "A.`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }

        /*
         * SQL queries
         * Get data to display
         */
        
        $rResult = $this->candidate_model->getAllCandidateDetails($sWhere, $sOrder, $sLimit);

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
        $i = 0;
        if ($rResult['candList']) {
            foreach ($rResult['candList'] as $row) {
                $output['aaData'][] = array(
                    'candidate_id' => $row->Candidate_Id,
                    'Candidate_Name' => $row->Candidate_Name,
                    'Contact_no' => $row->Cell,
                    'Skills' => $row->primary_skill,
                    'Email' => $row->email2,
                    'Location' => $row->location
                );
                $i++;
            }
        }
        echo json_encode($output);
    }

    public function getRequirementGridDetails() {
        $this->load->model('requirements_model');
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
            $recruiter_id = false;
        } else {
            $recruiter_id = $this->session->userdata('Recruiter_ID');
        }
        $rResult = $this->requirements_model->getRequirementGridDetails($sWhere, $sOrder, $sLimit, $recruiter_id);

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
        $output['aaData'] = $rResult['reqList'];
        echo json_encode($output);
    }

    public function saveRequirement() {
        $status = '';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pid', 'Postion Id', 'required|trim');
        $this->form_validation->set_rules('ptitle', 'Postion Title', 'required|trim');
        $this->form_validation->set_rules('sdate', 'Starting Date', 'required|trim');
        $this->form_validation->set_rules('cdate', 'Closing Date', 'required|trim');
        $this->form_validation->set_rules('skill', 'Skills', 'required|trim');
        $this->form_validation->set_rules('billrate', 'Billing Rate', 'required|trim|numeric');
        $this->form_validation->set_rules('tax_term', 'Tax Term', 'required|trim');
        $this->form_validation->set_rules('loc', 'Location', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');
        $this->form_validation->set_rules('company', 'Company Name', 'required|trim');
        $this->form_validation->set_rules('cp', 'Contact Person', 'required|trim');
        $this->form_validation->set_rules('cphone', 'Contact Phone', 'required|trim');
        $this->form_validation->set_rules('cemail', 'Contact Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('to_time_frame', 'Duration', 'trim');
        $this->form_validation->set_rules('from_time_frame', 'Duration', 'trim');
        $this->form_validation->set_rules('time_frame_desc', 'Duration', 'trim');

        $this->load->model('requirements_model');
        $this->load->model('contact_model');
        if ($this->form_validation->run()) {
            if ($this->requirements_model->req_check($this->input->post('pid'), $this->input->post('source'))) {
                $this->ibizErrors[] = 'Requirement Not added!!!! Already this Requirement Exisits';
            } else {
            if (strtotime($this->input->post('cdate')) <= strtotime($this->input->post('sdate'))) {
                $this->ibizErrors[] = 'Start Date Should Not Be More Than Close Date';
            } else if (strtotime(date("Y-m-d")) >= strtotime($this->input->post('cdate'))) {
                $this->ibizErrors[] = 'Your Closing Date is Expired';
            } else {
                $addressdata = array(
                    'Address_1' => '',
                    'Address_2' => '',
                    'City' => '',
                    'State' => '',
                    'Zip' => '',
                    'Cell' => '',
                    'Work_Phone' => $this->input->post('cphone'),
                    'Fax' => '',
                    'Home_Phone' => '',
                    'Created_Date' => date('Y-m-d'),
                    'Created_By' => $this->session->userdata('Recruiter_ID')
                );
                $addressdata = $this->replaceEmptyValuesNull($addressdata);
                $addressid = $this->contact_model->insertAddressDetails($addressdata);

                $contactdata = array(
                    'First_Name' => $this->input->post('cp'),
                    'Last_Name' => '',
                    'Created_Date' => date('Y-m-d'),
                    'Created_By' => $this->session->userdata('Recruiter_ID'),
                    'Display_Name' => '',
                    'Designation' => '',
                    'Referred_By' => '',
                    'Email1' => $this->input->post('cemail'),
                    'Email2' => '',
                    'Address_ID' => $addressid,
                    'Status' => '19',
                    'contact_type' => $this->input->post('contact_type'),
                    'entity_type_id' => '',
                    'company_id' => ''
                );
                $contactdata = $this->replaceEmptyValuesNull($contactdata);
                $contactid = $this->contact_model->insertContactsDetails($contactdata);

                $reqdata = array(
                    'Position_id' => $this->input->post('pid'),
                    'Position_title' => $this->input->post('ptitle'),
                    'Start_Date' => date('Y-m-d',strtotime($this->input->post('sdate'))),
                    'Close_Date' => date('Y-m-d',strtotime($this->input->post('cdate'))),
                    'Requirement_Detail' => $this->input->post('posdes'),
                    'Created_Date' => date('Y-m-d'),
                    'Created_By' => $this->session->userdata('Recruiter_ID'),
                    'Skills' => $this->input->post('skill'),
                    'billing_rate' => $this->input->post('billrate'),
                    'Tax_Term' => $this->input->post('tax_term'),
                    'Location' => $this->input->post('loc'),
                    'Source' => $this->input->post('source'),
                    'End_Client_Name' => $this->input->post('ecn'),
                    'Contact_ID' => $contactid,
                    'status' => $this->input->post('status'),
                    'priority' => $this->input->post('priority'),
                    'company_id' => $this->input->post('company'),
                    'to_time_frame' => $this->input->post('to_time_frame'),
                    'from_time_frame' => $this->input->post('from_time_frame'),
                    'time_frame_desc' => $this->input->post('time_frame_desc')
                );
                $reqdata = $this->replaceEmptyValuesNull($reqdata);
                $reqid = $this->requirements_model->insertRequirementDetails($reqdata);
                $where=array(
                    'Reporting_PersonID'=>$contactid
                );
                $condata=array(
                    'entity_type_id'=>$reqid
                );
                $this->contact_model->updateContactDetails($condata,$where);
                $assignRequirement = array(
                    'Requirement_ID' => $reqid,
                    'Recruiter_ID' => $this->session->userdata('Recruiter_ID'),
                    'Assignment_Stauts' => 31,
                    'Status_Change_date' => '',
                    'Modified_By' => $this->session->userdata('Recruiter_ID'),
                    'Assigned_By' => $this->session->userdata('Recruiter_ID'),
                    'Created_Date' => date('Y-m-d'),
                    'close_date' => ''
                );
                $assignRequirement = $this->replaceEmptyValuesNull($assignRequirement);
                $assignId = $this->requirements_model->insertAssignedRequirementDetails($assignRequirement);
                $status = 'Requirements added Successfully';
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

    public function editRequirements($requirement_id) {
        $this->load->model('lookup_model');
        $this->load->model('company_model');
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
        $this->req_status = $this->lookup_model->getStatus('JOB');
        $this->company = $this->company_model->getCompanies(1);
        $this->load->model('requirements_model');
        $this->load->model('notes_model');
        $data['page'] = $this->page;
        $data['requirement_details'] = $this->requirements_model->getRequirementDetails($requirement_id);
        $data['requirement_details'] = $this->replaceSymbolsWithEmptyValues('--', $data['requirement_details']);
        $data['submited_candidates'] = $this->requirements_model->getReqSubmitList($requirement_id);
        $data['notes_details'] = $this->notes_model->getallnotes($requirement_id, 'requirement');
        $this->load->view('editRequirement', $data);
    }

    public function updateRequirement() {
        $status = '';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pid', 'Postion Id', 'required|trim');
        $this->form_validation->set_rules('ptitle', 'Postion Title', 'required|trim');
        $this->form_validation->set_rules('sdate', 'Starting Date', 'required|trim');
        $this->form_validation->set_rules('cdate', 'Closing Date', 'required|trim');
        $this->form_validation->set_rules('skill', 'Skills', 'required|trim');
        $this->form_validation->set_rules('billrate', 'Billing Rate', 'required|trim|numeric');
        $this->form_validation->set_rules('tax_term', 'Tax Term', 'required|trim');
        $this->form_validation->set_rules('loc', 'Location', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');
        $this->form_validation->set_rules('cname', 'Compnay Name', 'required|trim');
        $this->form_validation->set_rules('cp', 'Contact Person', 'required|trim');
        $this->form_validation->set_rules('cphone', 'Contact Phone', 'required|trim');
        $this->form_validation->set_rules('cemail', 'Contact Email', 'required|trim|valid_email');


        if ($this->form_validation->run()) {
            $this->load->model('requirements_model');
            $this->load->model('contact_model');
            $wherereq = array(
                'requirement_id' => $this->input->post('requirement_id'),
            );
            $whereadd = array(
                'Address_Id' => $this->input->post('address_id'),
            );
            $wherecon = array(
                'Reporting_PersonID' => $this->input->post('contact_id'),
            );
            $addressdata = array(
                'Address_1' => '',
                'Address_2' => '',
                'City' => '',
                'State' => '',
                'Zip' => '',
                'Cell' => $this->input->post('cphone'),
                'Work_Phone' => '',
                'Fax' => '',
                'Home_Phone' => '',
                'Modified_Date' => date('Y-m-d'),
                'Modified_By' => $this->session->userdata('Recruiter_ID')
            );

            $addressid = $this->contact_model->updateAddressDetails($addressdata, $whereadd);

            $contactdata = array(
                'First_Name' => $this->input->post('cp'),
                'Last_Name' => '',
                'Modified_Date' => date('Y-m-d'),
                'Modified_By' => $this->session->userdata('Recruiter_ID'),
                'Display_Name' => '',
                'Designation' => '',
                'Referred_By' => '',
                'Email1' => $this->input->post('cemail'),
                'Email2' => '',
                'Status' => '19',
                'contact_type' => $this->input->post('contact_type'),
                'entity_type_id' => '',
                'company_id' => ''
            );

            $contactid = $this->contact_model->updateContactDetails($contactdata, $wherecon);

            $reqdata = array(
                'Position_id' => $this->input->post('pid'),
                'Position_title' => $this->input->post('ptitle'),
                'Start_Date' => date('Y-m-d',strtotime($this->input->post('sdate'))),
                'Close_Date' => date('Y-m-d',strtotime($this->input->post('cdate'))),
                'Requirement_Detail' => $this->input->post('posdes'),
                'Modified_Date' => date('Y-m-d'),
                'Modified_By' => $this->session->userdata('Recruiter_ID'),
                'Skills' => $this->input->post('skill'),
                'billing_rate' => $this->input->post('billrate'),
                'Tax_Term' => $this->input->post('tax_term'),
                'Location' => $this->input->post('loc'),
                'Source' => $this->input->post('source'),
                'End_Client_Name' => $this->input->post('ecn'),
                'status' => $this->input->post('status'),
                'priority' => $this->input->post('priority'),
                'company_id' => $this->input->post('cname'),
                'to_time_frame' => $this->input->post('to_time_frame'),
                'from_time_frame' => $this->input->post('from_time_frame'),
                'time_frame_desc' => $this->input->post('time_frame_desc')
            );
            $reqid = $this->requirements_model->updateRequirementsDetails($reqdata, $wherereq);
            $status = 'Requirements updated Successfully';
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

    public function changeRequirmentStatus($requirement_id, $status_id) {
        $this->load->model('requirements_model');
        $where = array(
            'requirement_id' => $requirement_id
        );
        $data = array(
            'status' => $status_id,
            'status_change_date' => date('Y-m-d')
        );
        
        if ($this->requirements_model->updateRequirementsDetails($data, $where)) {
            $status = 'Requirement Deactivated Sucessfully';
            
        } else {
            $this->ibizErrors[] = 'Requirement not Deactivated Try Again!!';
        }
        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status,
        );
        echo json_encode($return);
    }

}
