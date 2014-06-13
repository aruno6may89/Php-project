<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Candidate extends MY_Session_Controller {

    private $page = "candidate";
    private $uploadStatus = '';

    public function index() {
        $this->load->model('lookup_model');
        $this->load->model('company_model');
        $data['page'] = $this->page;
        $data['company_status'] = $this->lookup_model->getStatus('COMP');
        $data['designation'] = $this->lookup_model->getDisplayList('designation');
        $data['nda'] = $this->lookup_model->getDisplayList('nda');
        $data['companies'] = $this->company_model->getCompanies();
        $this->load->view('candidateListing', $data);
    }

    public function add() {
        $this->load->model("company_model");
        $this->load->model('lookup_model');
        $data['page'] = $this->page;
        $data['state'] = $this->lookup_model->getDisplayList('state');
        $data['duration'] = $this->lookup_model->getDisplayList('duration');
        $data['available'] = $this->lookup_model->getDisplayList('available');
        $data['immi_status'] = $this->lookup_model->getDisplayList('immi_status');
        $data['position_type'] = $this->lookup_model->getDisplayList('position_type');
        $data['relocate'] = $this->lookup_model->getDisplayList('relocate');
        $data['tax_term'] = $this->lookup_model->getDisplayList('tax_term');
        $data['location'] = $this->lookup_model->getDisplayList('location');
        $data['companies'] = $this->company_model->getCompanies();
        $data['nda'] = $this->lookup_model->getDisplayList('nda');
        $this->load->view('addcandidate', $data);
    }

    public function details($candidate_id) {
        $this->load->model('notes_model');
        $this->load->model('candidate_model');
        $this->load->model("company_model");
        $this->load->model('lookup_model');
        $data['page'] = $this->page;
        $data['candidate_id'] = $candidate_id;
        if ($candidate_id && $data['candidate_details'] = $this->candidate_model->getCandidateDetails($candidate_id)) {
            $data['address_details'] = $this->candidate_model->getAddressDetails($data['candidate_details']->Address_Id);
            $data['resume_details'] = $this->candidate_model->getcandidateResumes($candidate_id);
            $data['companies'] = $this->company_model->getCompanies();
            $data['reference_details'] = $this->candidate_model->getReference($candidate_id);
            $data['notes_details'] = $this->notes_model->getallnotes($candidate_id, 'candidate');
            $data['designation'] = $this->lookup_model->getDisplayList('designation');
            $data['company_status'] = $this->lookup_model->getStatus('COMP');
            $data['nda'] = $this->lookup_model->getDisplayList('nda');
            $this->load->view('candidatedetails', $data);
        } else {
            $this->load->view('404');
        }
    }

    public function uploadResume($candidate_id) {
        $this->load->model('candidate_model');
        $data['page'] = $this->page;
        $data['candidate_id'] = $candidate_id;
        $data['candidateDetails'] = $this->candidate_model->getCandidateDetails($candidate_id);
        $data['status'] = $this->uploadStatus;
        //echo 'ss' . $this->uploadStatus;
        $this->load->view('uploadResume', $data);
    }

    public function do_upload() {
        $this->load->model('candidate_model');
        $resumeCount = $this->candidate_model->getResumeCount($this->input->post('candidate_id'));
        if ($resumeCount) {
            $value = $resumeCount->count + 1;
            $resume_key = $resumeCount->pKey;
        } else {
            $value = 1;
            $resume_key = false;
        }
        $dir = "./resumes/" . $this->input->post('candidate_id');
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $ext = explode(".", $_FILES['fileupload']['name']);
        $config['file_name'] = $this->input->post('candidate_name') . $value;
        $config['upload_path'] = $dir;
        $config['allowed_types'] = 'docx|pdf|doc|odt|txt';
        $config['max_size'] = '1024';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('fileupload')) {
            $status = $this->upload->display_errors();
        } else {
            $upload_data = $this->upload->data();
            if ($resume_key) {
                $where = array(
                    'resume_id' => $resume_key
                );
                $data = array(
                    'resume_status' => 23,
                    'status_change_date' => date('Y-m-d')
                );
                $this->candidate_model->updateResumeStatus($data, $where);
            }
            $resumeData = array(
                'candidate_id' => $this->input->post('candidate_id'),
                'file_name' => $upload_data['file_name'],
                'file_version' => '',
                'description' => $this->input->post('resumeDesc'),
                'file_type' => $upload_data['file_ext'],
                'file_size' => '',
                'resume_url' => 'resumes/' . $this->input->post('candidate_id') . '/' . $upload_data['file_name'],
                'resume_status' => 22,
                'upload_by' => $this->session->userdata('Recruiter_ID'),
                'upload_date' => date('Y-m-d'),
                'resume_text' => $this->input->post('skill'),
                'skills' => $this->input->post('skill')
            );
            $resumeData = $this->replaceEmptyValuesNull($resumeData);
            $this->candidate_model->insertResumeDetails($resumeData);
            $this->uploadStatus = "Resume Uploaded Sucessfully";
            $status=true;
        }
        //redirect('/candidate/uploadResume/' . $this->input->post('candidate_id'), 'refresh');
        redirect('/candidate/details/' . $this->input->post('candidate_id').'?sucess='.$status, 'refresh');
    }

    public function saveReference() {
        $status = '';
        $ajaxReferenceData = '';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('conPerson', 'Contact Person', 'required|trim');
        $this->form_validation->set_rules('conCompany', 'Contact Company', 'required|trim');
        if ($this->form_validation->run()) {
            $this->load->model('candidate_model');
            $refdata = array(
                'candidate_id' => $this->input->post('candidate_id'),
                'contact_id' => $this->input->post('conPerson'),
            );
            if (!$this->candidate_model->checkForExistingReference($this->input->post('candidate_id'), $this->input->post('conPerson'))) {
                $refId = $this->candidate_model->insertCandidateReference($refdata);
                $company_details = $this->candidate_model->getReference($this->input->post('candidate_id'), $this->input->post('conPerson'));
                if ($refId) {
                    $ajaxReferenceData = '<tr>
                                    <td>' . $company_details[0]->First_Name . '</td>
                                    <td>' . $company_details[0]->Name . '</td>
                                    <td>' . $company_details[0]->Work_Phone . '</td>
                                    <td>' . $company_details[0]->Email2 . '</td>
                                    <td>' . $company_details[0]->Designation . '</td>
                                    <td>' . $company_details[0]->Referred_By . '</td>
                                </tr>';
                    $status = 'Reference added Successfully';
                } else {
                    $this->ibizErrors[] = 'Reference Adding Failed Try Again!!';
                }
            } else {
                $this->ibizErrors[] = 'Already this reference has been added for this candidate';
            }
        } else {
            $this->ibizErrors[] = validation_errors();
        }
        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status,
            'ajaxReferenceData' => $ajaxReferenceData
        );
        echo json_encode($return);
    }

    public function refNewCompany() {
        $status = '';
        $companyid='';
        $this->load->library('form_validation');

        $this->form_validation->set_rules('cname', 'Company Name', 'required|trim');
        $this->form_validation->set_rules('compStatus', 'Company Status', 'required|trim');
        $this->form_validation->set_rules('ndaSigned', 'NDA Signed', 'trim');

        if ($this->form_validation->run()) {
            $this->load->model('company_model');
            $this->load->model('contact_model');
            $companydata = array(
                'Name' => $this->input->post('cname'),
                'Status' => $this->input->post('compStatus'),
                'NDA_Signed' => $this->input->post('ndaSigned')
            );

            $companyid = $this->company_model->insertCompanyDetails($companydata);
            if ($companyid) {
                $this->form_validation->set_rules('fName', 'First Name', 'required|trim');
                $this->form_validation->set_rules('lName', 'Last Name', 'required|trim');
                $this->form_validation->set_rules('wEmail', 'Work Email', 'required|trim|valid_email');
                $this->form_validation->set_rules('wPhone', 'Work Phone', 'required|trim');
                $this->form_validation->set_rules('desi', 'Designation', 'required|trim');
                if ($this->contact_model->contact_check($this->input->post('fName'), $this->input->post('wEmail'),true)) {
                    $this->ibizErrors[] = 'Contact not Added!!!, Contact has been already added with this Name and Email';
                } 
                else{
                if ($this->form_validation->run()) {
                    $addressdata = array(
                        'Address_1' => '',
                        'Address_2' => '',
                        'City' => '',
                        'State' => '',
                        'Zip' => '',
                        'Cell' => '',
                        'Work_Phone' => $this->input->post('wPhone'),
                        'Fax' => '',
                        'Home_Phone' => '',
                        'Created_Date' => date("Y-m-d"),
                        'Created_By' => $this->session->userdata('Recruiter_ID')
                    );
                    $addressdata = $this->replaceEmptyValuesNull($addressdata);
                    $addressid = $this->contact_model->insertAddressDetails($addressdata);

                    $contactdata = array(
                        'First_Name' => $this->input->post('fName'),
                        'Last_Name' => $this->input->post('lName'),
                        'Created_Date' => date("Y-m-d"),
                        'Created_By' => $this->session->userdata('Recruiter_ID'),
                        'Display_Name' => '',
                        'Designation' => $this->input->post('desi'),
                        'Referred_By' => '',
                        'Email1' => '',
                        'Email2' => $this->input->post('wEmail'),
                        'Address_ID' => $addressid,
                        'Status' => '19',
                        'contact_type' => 'Company Contact',
                        'entity_type_id' => $companyid,
                        'company_id' => $companyid
                    );
                    $contactdata = $this->replaceEmptyValuesNull($contactdata);
                    $contactid = $this->contact_model->insertContactsDetails($contactdata);
                    $status = 'Company Details added Successfully';
                }
                else {
                $this->ibizErrors[] = validation_errors();
                }
            } 
            }
        } else {
            $this->ibizErrors[] = validation_errors();
        }

        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status,
            'company_id'=>$companyid,
            'company_name'=>$this->input->post('cname')
        );
        echo json_encode($return);
    }

    public function saveCandidate() {
        $status = '';
        $this->load->library('form_validation');

        $this->form_validation->set_rules('fName', 'First Name', 'required|trim');
        $this->form_validation->set_rules('lName', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('pEmail', 'Personal Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('pPhone', 'Personal Phone', 'required|trim');
        $this->form_validation->set_rules('add1', 'Address1', 'trim');
        $this->form_validation->set_rules('add2', 'Address2', 'trim');
        $this->form_validation->set_rules('city', 'City', 'trim');
        $this->form_validation->set_rules('state', 'State', 'trim');
        $this->form_validation->set_rules('zip', 'Zipcode', 'trim|numeric');
        $this->form_validation->set_rules('ssn', 'SSN', 'trim');
        $this->form_validation->set_rules('primary_skill', 'Primary Skills', 'required|trim');
        $this->form_validation->set_rules('detailed_skill', 'Secondary Skills', 'trim');
        $this->form_validation->set_rules('bd', 'Birthdate', 'trim');
        $this->form_validation->set_rules('conCompany', 'Contact Company', 'trim');
        $this->form_validation->set_rules('conPerson', 'Contact Person', 'trim');
        $this->form_validation->set_rules('empName', 'Employer Name', 'trim');
        $this->form_validation->set_rules('subPosition', 'Suitable Position', 'trim');
        $this->form_validation->set_rules('askRate', 'Asking Rate', 'trim');
        $this->form_validation->set_rules('available', 'Available Status', 'requiredtrim');
        $this->form_validation->set_rules('availDate', 'Available Date', 'trim');
        $this->form_validation->set_rules('imgStatus', 'Immigration Status', 'required|trim');
        $this->form_validation->set_rules('taxterm', 'Tax Term', 'required|trim');
        $this->form_validation->set_rules('nda', 'NDA Signed', 'required|trim');
        $this->form_validation->set_rules('willRelocate', 'Relocate', 'required|trim');
        $this->form_validation->set_rules('posType', 'Preferred Position Type', 'trim');
        $this->form_validation->set_rules('preLocation', 'Preferred Location', 'trim');

        if ($this->form_validation->run()) {
            $this->load->model('candidate_model');
            $this->load->model('contact_model');
            if (strtotime(date("Y-m-d")) <= strtotime($this->input->post('bd'))) {
                $this->ibizErrors[] = 'Please Check Given Date of Birth';
            } else if ($this->candidate_model->candidate_check($this->input->post('fName'), $this->input->post('pEmail'))) {
                $this->ibizErrors[] = 'Candidate Already Exists with given Email';
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
                    'Created_Date' => date('Y-m-d'),
                    'Created_By' => $this->session->userdata('Recruiter_ID')
                );
                $addressdata = $this->replaceEmptyValuesNull($addressdata);
                $addressid = $this->contact_model->insertAddressDetails($addressdata);

                /* $contactdata=array(
                  'First_Name' => $this->input->post('fName'),
                  'Last_Name' => $this->input->post('lName'),
                  'Created_Date' => date('Y-m-d'),
                  'Created_By' => $this->session->userdata('Recruiter_ID'),
                  'Display_Name' => '',
                  'Designation' => '',
                  'Referred_By' => '',
                  'Email1' => $this->input->post('pEmail'),
                  'Email2' => $this->input->post('wEmail'),
                  'Address_ID' => $addressid,
                  'Status' => '19',
                  'contact_type' => '',
                  'entity_type_id' => '',
                  'company_id' => ''
                  );
                  $contactdata = $this->replaceEmptyValuesNull($contactdata);
                  $contactid = $this->contact_model->insertContactsDetails($contactdata); */

                $canddata = array(
                    'First_Name' => $this->input->post('fName'),
                    'Last_Name' => $this->input->post('lName'),
                    'Created_Date' => date('Y-m-d'),
                    'Created_By' => $this->session->userdata('Recruiter_ID'),
                    'Modified_By' => $this->session->userdata('Recruiter_ID'),
                    'primary_skill' => $this->input->post('primary_skill'),
                    'detailed_skill' => $this->input->post('detailed_skill'),
                    'email1' => $this->input->post('pEmail'),
                    'email2' => $this->input->post('wEmail'),
                    'immi_status' => $this->input->post('imgStatus'),
                    'available_yn' => $this->input->post('available'),
                    'available_date' => date('Y-m-d', strtotime($this->input->post('availDate'))),
                    'Address_Id' => $addressid,
                    'Status' => '10',
                    'Employer_Name' => $this->input->post('empName'),
                    'NDA_Signed' => $this->input->post('nda'),
                    'Asking_rate' => $this->input->post('askRate'),
                    'SSN' => $this->input->post('ssn'),
                    'Birth_Date' => date('Y-m-d', strtotime($this->input->post('bd'))),
                    'Suitable_Position' => $this->input->post('subPosition'),
                    'Tax_Term' => $this->input->post('taxterm'),
                    'company_id' => $this->input->post('conCompany'),
                    'contact_id' => $this->input->post('conPerson'),
                    'position_type' => $this->input->post('posType'),
                    'location' => $this->input->post('preLocation'),
                    'relocate' => $this->input->post('willRelocate'),
                    'duration' => $this->input->post('rateDuration')
                );
                $canddata = $this->replaceEmptyValuesNull($canddata);
                $candid = $this->candidate_model->insertCandidateDetails($canddata);
                $status = 'Candidates added Successfully';
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

    public function getCandidateGridList() {
        $this->load->model('candidate_model');
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
        $rResult = $this->candidate_model->getCandidateGridDetails($aColumns, $sWhere, $sOrder, $sLimit);

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
        $output['aaData'] = $rResult['candidateList'];
        echo json_encode($output);
    }

    public function editCandidate($candidate_id) {
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
        $this->comp_status = $this->lookup_model->getStatus('COMP');
        $this->load->model('company_model');
        $this->load->model('contact_model');
        $this->load->model('candidate_model');
        $this->load->model('notes_model');
        $data['page'] = $this->page;
        $data['companies'] = $this->company_model->getCompanies();
        $data['candidate_details'] = $this->candidate_model->getCandidateDetails($candidate_id);
        $data['candidate_details'] = $this->replaceSymbolsWithEmptyValues('-', $data['candidate_details']);
        $data['company_contacts'] = $this->contact_model->getCompanyContacts($data['candidate_details']->company_id);
        $data['company_contacts'] = $this->replaceSymbolsWithEmptyValues('-', $data['company_contacts']);
        $data['address_details'] = $this->candidate_model->getAddressDetails($data['candidate_details']->Address_Id);
        $data['address_details'] = $this->replaceSymbolsWithEmptyValues('-', $data['address_details']);
        $data['resume_details'] = $this->candidate_model->getcandidateResumes($candidate_id);
        //$data['reference_details']=$this->candidate_model->getReference($candidate_id);
        $data['notes_details'] = $this->notes_model->getallnotes($candidate_id, 'candidate');
        $this->load->view('editCandidate', $data);
    }

    public function updateCandidate() {
        $status = '';
        $this->load->library('form_validation');

        $this->form_validation->set_rules('fName', 'First Name', 'required|trim');
        $this->form_validation->set_rules('lName', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('pEmail', 'Personal Email', 'trim|valid_email');
        $this->form_validation->set_rules('pPhone', 'Personal Phone', 'trim');
        $this->form_validation->set_rules('add1', 'Address1', 'trim');
        $this->form_validation->set_rules('add2', 'Address2', 'trim');
        $this->form_validation->set_rules('city', 'City', 'trim');
        $this->form_validation->set_rules('state', 'State', 'trim');
        $this->form_validation->set_rules('zip', 'Zipcode', 'trim|numeric');
        $this->form_validation->set_rules('ssn', 'SSN', 'trim');
        $this->form_validation->set_rules('primary_skill', 'Primary Skills', 'required|trim');
        $this->form_validation->set_rules('detailed_skill', 'Secondary Skills', 'trim');
        $this->form_validation->set_rules('bd', 'Birthdate', 'trim');
        $this->form_validation->set_rules('wEmail', 'Work Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('wPhone', 'Work Phone', 'required|trim');
        $this->form_validation->set_rules('conCompany', 'Contact Company', 'trim');
        $this->form_validation->set_rules('conPerson', 'Contact Person', 'trim');
        $this->form_validation->set_rules('empName', 'Employer Name', 'trim');
        $this->form_validation->set_rules('subPosition', 'Suitable Position', 'trim');
        $this->form_validation->set_rules('askRate', 'Asking Rate', 'trim');
        $this->form_validation->set_rules('available', 'Available Status', 'required|trim');
        $this->form_validation->set_rules('availDate', 'Available Date', 'trim');
        $this->form_validation->set_rules('imgStatus', 'Immigration Status', 'required|trim');
        $this->form_validation->set_rules('taxterm', 'Tax Term', 'required|trim');
        $this->form_validation->set_rules('nda', 'NDA Signed', 'required|trim');
        $this->form_validation->set_rules('willRelocate', 'Relocate', 'required|trim');
        $this->form_validation->set_rules('posType', 'Preferred Position Type', 'trim');
        $this->form_validation->set_rules('preLocation', 'Preferred Location', 'trim');

        if ($this->form_validation->run()) {
            $this->load->model('candidate_model');
            $this->load->model('contact_model');
            $wherecan = array(
                'Candidate_Id' => $this->input->post('candidate_id'),
            );
            $whereadd = array(
                'Address_Id' => $this->input->post('address_id'),
            );
            $wherecon = array(
                'Reporting_PersonID' => $this->input->post('contact_id'),
            );
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

            /*$contactdata = array(
                'First_Name' => $this->input->post('fName'),
                'Last_Name' => $this->input->post('lName'),
                'Modified_Date' => date('Y-m-d'),
                'Modified_By' => $this->session->userdata('Recruiter_ID'),
                'Display_Name' => '',
                'Designation' => '',
                'Referred_By' => '',
                'Email1' => $this->input->post('pEmail'),
                'Email2' => $this->input->post('wEmail'),
                'Address_ID' => $addressid,
                'Status' => '19',
                'contact_type' => '',
                'entity_type_id' => '',
                'company_id' => ''
            );*/

            $contactid = $this->contact_model->updateContactDetails($contactdata, $wherecon);

            $canddata = array(
                'First_Name' => $this->input->post('fName'),
                'Last_Name' => $this->input->post('lName'),
                'Modified_Date' => date('Y-m-d'),
                'Modified_By' => $this->session->userdata('Recruiter_ID'),
                'primary_skill' => $this->input->post('primary_skill'),
                'detailed_skill' => $this->input->post('detailed_skill'),
                'email1' => $this->input->post('pEmail'),
                'email2' => $this->input->post('wEmail'),
                'immi_status' => $this->input->post('imgStatus'),
                'available_yn' => $this->input->post('available'),
                'available_date' => date('Y-m-d', strtotime($this->input->post('availDate'))),
                'Status' => '10',
                'Employer_Name' => $this->input->post('empName'),
                'NDA_Signed' => $this->input->post('nda'),
                'Asking_rate' => $this->input->post('askRate'),
                'SSN' => $this->input->post('ssn'),
                'Birth_Date' => date('Y-m-d', strtotime($this->input->post('bd'))),
                'Suitable_Position' => $this->input->post('subPosition'),
                'Tax_Term' => $this->input->post('taxterm'),
                'company_id' => $this->input->post('conCompany'),
                'contact_id' => $this->input->post('conPerson'),
                'position_type' => $this->input->post('posType'),
                'location' => $this->input->post('preLocation'),
                'relocate' => $this->input->post('willRelocate'),
                'duration' => $this->input->post('rateDuration')
            );
            $candid = $this->candidate_model->updateCandidateDetails($canddata, $wherecan);
            $status = 'Candidates Updated Successfully';
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

    public function changeCandidateStatus($candidate_id, $status_id) {
        $this->load->model('candidate_model');
        $where = array(
            'Candidate_Id' => $candidate_id
        );
        $data = array(
            'status' => $status_id,
            'status_change_date' => date('Y-m-d')
        );
        if ($this->candidate_model->updateCandidateDetails($data, $where)) {
            $status = 'Candidate Deactivated Sucessfully';
            
        } else {
            $this->ibizErrors[] = 'Candidate not Deactivated Try Again!!';
        }
        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status,
        );
        echo json_encode($return);
    }

}
