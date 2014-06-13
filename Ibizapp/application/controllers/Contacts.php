<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contacts extends MY_Session_Controller {

    private $page = "contact";

    public function index() {
        $data['page'] = $this->page;
        $this->load->view('contacts', $data);
    }

    public function add() {
        $this->load->model('lookup_model');
        $data['page'] = $this->page;
        $this->Designation = $this->lookup_model->getDisplayList('designation');
        $this->load->view('addContact', $data);
    }

    public function details($contact_id) {
        $this->load->model('contact_model');
        $this->load->model('notes_model');
        $data['page'] = $this->page;
        $data['contact_details'] = $this->contact_model->getContactDetails($contact_id);
        $data['notes_details'] = $this->notes_model->getallnotes($contact_id, 'contact');
        $data['contact_id'] = $contact_id;
        $this->load->view('contactdetails', $data);
    }

    public function viewgroups() {
        $this->load->model('contact_model');
        $data['group_names'] = $this->contact_model->getGroupNames();
        $data['page'] = $this->page;
        $this->load->view('contactgroups', $data);
    }

    public function getGroupEmails() {
        $this->ibizErrors = '';
        $error = '';
        $str = '';
        $id = $this->input->post('id');
        $this->load->model('contact_model');
        $groupEmails = $this->contact_model->getGroupMembers($id);
        if ($groupEmails) {
            $str = "<ul>";
            foreach ($groupEmails as $row) {
                $str.="<li id='" . $row->Reporting_PersonID . "'>
									<div>" . $row->First_Name . " " . $row->Last_Name . "(" . $row->Email1 . ")" . "</div>
								</li>";
            }
            $str.="</ul>";
        } else {
            $this->ibizErrors = "<ul><li>No emails found</li></ul>";
        }
        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $str
        );
        echo json_encode($return);
    }

    public function addgroups() {
        $this->load->model('contact_model');
        $data['page'] = $this->page;
        $data['contact_display_details'] = $this->contact_model->getContactDisplayDetails();
        $this->load->view('addgroup', $data);
    }

    public function saveGroup() {
        $status = '';
        $this->load->model('contact_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('gName', 'Group Name', 'required|trim');
        $this->form_validation->set_rules('myselect[]', 'Group Emails', 'required|trim');

        if ($this->form_validation->run()) {
            $groupdata = array(
                'Group_Name' => $this->input->post('gName'),
                'Status' => 6
            );
            $groupid = $this->contact_model->insertNewGroupName($groupdata);

            $myselect = $this->input->post('myselect');

            for ($i = 0; $i < count($myselect); $i++) {
                $groupcondata = array(
                    'Group_Id' => $groupid,
                    'Contact_Id' => $myselect[$i]
                );
                $groupmailid = $this->contact_model->insertNewGroupDetails($groupcondata);
            }
            $status = 'Email Group added Successfully';
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

    public function editGroup($groupid) {
        $this->load->model('contact_model');
        $data['page'] = $this->page;
        $data['groupid'] = $groupid;
        $data['contact_display_details'] = $this->contact_model->getContactDisplayDetails();
        $data['group_contact_details'] = $this->contact_model->getGroupContactDetails($groupid);
        $data['error_msg'] = '';
        $this->load->view('editContactGroups', $data);
    }

    public function updateGroup() {
        $status = '';
        $this->load->model('contact_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('myselect[]', 'Group Emails', 'required|trim');

        if ($this->form_validation->run()) {
            $groupdata = array(
                'Group_Name' => $this->input->post('gName'),
                'Status' => 6
            );
            $groupid = array(
                'Group_Id' => $this->input->post('groupid')
            );
            $deleteContact = $this->contact_model->deleteGroupContacts($groupid);

            $myselect = $this->input->post('myselect');
            if ($deleteContact) {
                for ($i = 0; $i < count($myselect); $i++) {
                    $groupcondata = array(
                        'Group_Id' => $this->input->post('groupid'),
                        'Contact_Id' => $myselect[$i]
                    );
                    $groupmailid = $this->contact_model->insertNewGroupDetails($groupcondata);
                }
            } else {
                $this->ibizErrors[] = 'Unable to Update Group Emails';
            }
            $status = 'Email Group added Successfully';
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

    public function deleteGroup($groupid) {
        $this->load->model('contact_model');
        $where = array(
            'Group_Id' => $groupid
        );
        $updatedata = array(
            'Status' => 7
        );
        $deleteContact = $this->contact_model->deleteGroupName($updatedata, $where);
        if ($deleteContact) {
            $this->viewgroups();
        } else {
            $this->load->model('contact_model');
            $data['page'] = $this->page;
            $data['groupid'] = $groupid;
            $data['contact_display_details'] = $this->contact_model->getContactDisplayDetails();
            $data['group_contact_details'] = $this->contact_model->getGroupContactDetails($groupid);
            $data['error_msg'] = 'Unable to Delete Group. Please try again later';
            $this->load->view('editContactGroups', $data);
        }
    }

    public function getContactGridList() {
        $this->load->model('contact_model');
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = $_GET['aColumns'];

        /* Indexed column (used for fast and accurate table cardinality) */
        //$sIndexColumn = $_GET['sIndexColumn'];

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
            $sOrder = " ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= "A.`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
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
        $rResult = $this->contact_model->getContactGridList($aColumns, $sWhere, $sOrder, $sLimit);

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

        $output['aaData'] = $rResult['contactList'];
        echo json_encode($output);
    }

    public function saveContact() {
        $ajaxDataContacts = '';
        $status = '';
        $this->load->model('contact_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('fName', 'First Name', 'required|trim');
        $this->form_validation->set_rules('lName', 'Last Name', 'required|trim');
        if ($this->input->post('contact_type') == 'General') {
            $this->form_validation->set_rules('pEmail', 'Personal Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('pPhone', 'Personal Phone', 'required|trim');
            $this->form_validation->set_rules('wEmail', 'Work Email', 'trim|valid_email');
            $this->form_validation->set_rules('wPhone', 'Work Phone', 'trim');
        } elseif ($this->input->post('contact_type') == 'Company Contact') {
            $this->form_validation->set_rules('pEmail', 'Personal Email', 'trim|valid_email');
            $this->form_validation->set_rules('pPhone', 'Personal Phone', 'trim');
            $this->form_validation->set_rules('wEmail', 'Work Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('wPhone', 'Work Phone', 'required|trim');
        }
        $this->form_validation->set_rules('desi', 'Designation', 'required|trim');
        $this->form_validation->set_rules('refby', 'Contact Person', 'trim');

        if ($this->form_validation->run()) {
            if ($this->contact_model->contact_check($this->input->post('fName'), $this->input->post('pEmail'))) {
                $this->ibizErrors[] = 'Contact not Added!!!, Contact has been already added with this Name and Email';
            } else {
                $addressdata = array(
                    'Address_1' => '',
                    'Address_2' => '',
                    'City' => '',
                    'State' => '',
                    'Zip' => '',
                    'Cell' => $this->input->post('pPhone'),
                    'Work_Phone' => $this->input->post('wPhone'),
                    'Fax' => '',
                    'Home_Phone' => '',
                    'Created_Date' => date("Y-m-d"),
                    'Created_By' => $this->session->userdata('Recruiter_ID')
                );
                $addressdata = $this->replaceEmptyValuesNull($addressdata);
                $addressid = $this->contact_model->insertAddressDetails($addressdata);

                if ($this->input->post('contact_type') == 'General') {
                    $entity_type_id = '';
                    $company_id = '';
                } elseif ($this->input->post('contact_type') == 'Company Contact') {
                    $entity_type_id = $this->input->post('company_id');
                    $company_id = $this->input->post('company_id');
                }
                $contactdata = array(
                    'First_Name' => $this->input->post('fName'),
                    'Last_Name' => $this->input->post('lName'),
                    'Created_Date' => date("Y-m-d"),
                    'Created_By' => $this->session->userdata('Recruiter_ID'),
                    'Display_Name' => '',
                    'Designation' => $this->input->post('desi'),
                    'Referred_By' => $this->input->post('refby'),
                    'Email1' => $this->input->post('pEmail'),
                    'Email2' => $this->input->post('wEmail'),
                    'Address_ID' => $addressid,
                    'Status' => '19',
                    'contact_type' => $this->input->post('contact_type'),
                    'entity_type_id' => $entity_type_id,
                    'company_id' => $company_id
                );
                $contactdata = $this->replaceEmptyValuesNull($contactdata);
                $contactid = $this->contact_model->insertContactsDetails($contactdata);
                if ($contactid) {
                    if ($this->input->post('pPhone')) {
                        $pphone = $this->input->post('pPhone');
                    } else {
                        $pphone = '--';
                    }
                    if ($this->input->post('pEmail')) {
                        $pemail = $this->input->post('pEmail');
                    } else {
                        $pemail = '--';
                    }
                    $ajaxDataContacts = '<tr class="contactDetails" id="con'.$contactid.'">
                                    <td>' . $this->input->post('fName') . ' ' . $this->input->post('lName') . '</td>
                                    <td><b>C:</b>' . $pphone . '<br><b>W:</b>' . $this->input->post('wPhone') . '<br></td>
                                    <td>' . $pemail . '<br>' . $this->input->post('wEmail') . '</td>
                                    <td>' . $this->input->post('desi') . '</td>
                                    <td>
                                        <a href="'.BASE_URL.'/contacts/details/'.$contactid.'">View</a> |
                                        <a href="'.BASE_URL.'/contacts/editCompanyContact/'.$contactid.'">Edit</a> |
                                        <a href="#" data-deactivate="true" data-id="'.$contactid.'">Deactivate</a>
                                    </td>
                                </tr>';
                    $status = 'Contact added Successfully';
                } else {
                    $this->ibizErrors[] = 'Contact Adding Failed Try Again!!';
                }
            }
        } else {
            $this->ibizErrors[] = validation_errors();
        }

        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status,
            'ajaxDataContacts' => $ajaxDataContacts
        );
        echo json_encode($return);
    }

    public function editContact($contact_id) {
        $this->load->model('lookup_model');
        $this->designation = $this->lookup_model->getDisplayList('designation');
        $this->load->model('contact_model');
        $this->load->model('notes_model');
        $data['page'] = $this->page;
        $data['contact_details'] = $this->contact_model->getContactDetails($contact_id);
        $data['contact_details'] = $this->replaceSymbolsWithEmptyValues('--', $data['contact_details']);
        $data['notes_details'] = $this->notes_model->getallnotes($contact_id, 'contact');
        $this->load->view('editContact', $data);
    }

    public function editCompanyContact($contact_id) {
        $this->load->model('lookup_model');
        $this->designation = $this->lookup_model->getDisplayList('designation');
        $this->load->model('contact_model');
        $this->load->model('notes_model');
        $data['page'] = $this->page;
        $data['contact_details'] = $this->contact_model->getContactDetails($contact_id);
        $data['contact_details'] = $this->replaceSymbolsWithEmptyValues('--', $data['contact_details']);
        $data['notes_details'] = $this->notes_model->getallnotes($contact_id, 'contact');
        $this->load->view('editCompanyContact', $data);
    }

    public function updateContact() {
        $status = '';
        $this->load->model('contact_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('fName', 'First Name', 'required|trim');
        $this->form_validation->set_rules('lName', 'Last Name', 'required|trim');
        if ($this->input->post('contact_type') == 'General') {
            $this->form_validation->set_rules('pEmail', 'Personal Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('pPhone', 'Personal Phone', 'required|trim');
            $this->form_validation->set_rules('wEmail', 'Work Email', 'trim|valid_email');
            $this->form_validation->set_rules('wPhone', 'Work Phone', 'trim');
        } elseif ($this->input->post('contact_type') == 'Company Contact') {
            $this->form_validation->set_rules('pEmail', 'Personal Email', 'trim|valid_email');
            $this->form_validation->set_rules('pPhone', 'Personal Phone', 'trim');
            $this->form_validation->set_rules('wEmail', 'Work Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('wPhone', 'Work Phone', 'required|trim');
        }

        if ($this->form_validation->run()) {
            $wherecon = array(
                'Reporting_PersonID' => $this->input->post('contact_id'),
            );
            $whereadd = array(
                'Address_Id' => $this->input->post('address_id'),
            );
            $addressdata = array(
                'Address_1' => '',
                'Address_2' => '',
                'City' => '',
                'State' => '',
                'Zip' => '',
                'Cell' => $this->input->post('pPhone'),
                'Work_Phone' => $this->input->post('wPhone'),
                'Fax' => '',
                'Home_Phone' => '',
                'Modified_Date' => date('Y-m-d'),
                'Modified_By' => $this->session->userdata('Recruiter_ID')
            );

            $addressid = $this->contact_model->updateAddressDetails($addressdata, $whereadd);
            if ($this->input->post('contact_type') == 'General') {
                $entity_type_id = '';
                $company_id = '';
            } elseif ($this->input->post('contact_type') == 'Company Contact') {
                $entity_type_id = $this->input->post('company_id');
                $company_id = $this->input->post('company_id');
            }
            $contactdata = array(
                'First_Name' => $this->input->post('fName'),
                'Last_Name' => $this->input->post('lName'),
                'Modified_Date' => date('Y-m-d'),
                'Modified_By' => $this->session->userdata('Recruiter_ID'),
                'Display_Name' => '',
                'Designation' => $this->input->post('desi'),
                'Referred_By' => $this->input->post('refby'),
                'Email1' => $this->input->post('pEmail'),
                'Email2' => $this->input->post('wEmail'),
                'Status' => '19',
                'contact_type' => $this->input->post('contact_type'),
                'entity_type_id' => $entity_type_id,
                'company_id' => $company_id,
                'Modified_Date' => date('Y-m-d'),
                'Modified_By' => $this->session->userdata('Recruiter_ID')
            );

            $contactid = $this->contact_model->updateContactDetails($contactdata, $wherecon);
            $status = 'Contact updated Successfully';
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

    public function addgroup() {
        if ($this->session->userdata('is_logged')) {
            $this->load->view('newgroup');
        } else {
            $this->load->view('login');
        }
    }

    /* public function saveGroup() {
      $status = '';
      $this->load->model('common');
      $this->load->library('form_validation');
      $this->form_validation->set_rules('gName', 'Group Name', 'required|trim');
      $this->form_validation->set_rules('myselect[]', 'Group Emails', 'required|trim');

      if ($this->form_validation->run()) {
      $groupdata = array(
      'Group_Name' => $this->input->post('gName'),
      'Status' => 6
      );
      $groupid = $this->common->insertValues('rms_email_groups', $groupdata);

      $myselect = $this->input->post('myselect');

      for ($i = 0; $i < count($myselect); $i++) {
      $groupcondata = array(
      'Group_Id' => $groupid,
      'Contact_Id' => $myselect[$i]
      );
      $groupmailid = $this->common->insertValues('rms_group_contacts', $groupcondata);
      }
      $status = 'Email Group added Successfully';
      } else {
      $this->ibizErrors[] = validation_errors();
      }
      $return = array(
      'hasError' => !empty($this->ibizErrors),
      'errors' => $this->ibizErrors,
      'status' => $status
      );
      echo json_encode($return);
      } */

    public function saveNotes() {
        $status = '';
        $ajaxDataNotes = '';
        $this->load->library('form_validation');

        $this->form_validation->set_rules('notes', 'Notes', 'required|trim');
        $this->form_validation->set_rules('notesType', 'Notes Type', 'required|trim');

        if ($this->form_validation->run()) {
            $this->load->model('contact_model');
            if ($this->input->post('notesType') == 'General') {
                $time_spend = '';
                $call_status = '';
            } else if ($this->input->post('notesType') == 'Call') {
                if ($this->input->post('time_spend') == '5') {
                    $time_spend = "00:00:0" . $this->input->post('time_spend');
                } else {
                    $time_spend = "00:00:" . $this->input->post('time_spend');
                }
                $call_status = $this->input->post('call_status');
            }
            $notesdata = array(
                'entity_id' => $this->input->post('entity_id'),
                'comments' => $this->input->post('notes'),
                'created_date' => date("Y-m-d"),
                'created_by' => $this->session->userdata('Recruiter_ID'),
                'insert_date' => date("Y-m-d"),
                'insert_time' => date("H:i:s"),
                'time_spend' => $time_spend,
                'STATUS' => 12,
                'call_status' => $call_status,
                'note_type' => $this->input->post('notesType'),
                'entity_type' => $this->input->post('entity_type')
            );

            $notesdata = $this->replaceEmptyValuesNull($notesdata);
            $notesid = $this->contact_model->insertNotes($notesdata);
            if ($notesid) {
                if (!$call_status) {
                    $call_status = '--';
                }
                if (!$time_spend) {
                    $time_spend = '--';
                }
                $ajaxDataNotes = '<tr class="notes" id="val' . $notesid . '">
                            <td>' . $this->input->post('notesType') . '</td>
                            <td>' . date('d-m-y') . '</td>
                            <td>' . $this->input->post('notes') . '</td>
                            <td>' . $call_status . '</td>
                            <td>' . $time_spend . '</td>
                            <td><a href="#" data-id="' . $notesid . '">Deactivate</a></td>
                        </tr>';
                $status = 'Notes added Successfully';
            } else {
                $this->ibizErrors[] = "Notes Adding Failed Try Again!!";
            }
        } else {
            $this->ibizErrors[] = validation_errors();
        }

        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status,
            'ajaxDataNotes' => $ajaxDataNotes
        );
        echo json_encode($return);
    }

    public function changeContactStatus($contact_id, $status_id) {
        $this->load->model('contact_model');
        $where = array(
            'Reporting_PersonID' => $contact_id
        );
        $data = array(
            'Status' => $status_id,
            'status_change_date' => date('Y-m-d')
        );
        if($this->contact_model->updateContactDetails($data, $where)){
            $status = 'Contact Deactivated Sucessfully';
        }
        else{
            $this->ibizErrors[] = 'Contact not Deactivated Try Again!!';
        }
         $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status,
        );
        echo json_encode($return);  
    }

    public function deactivateNotes($note_id) {
        $status = '';
        $this->load->model('contact_model');
        $where = array(
            'note_id' => $note_id
        );

        $data = array(
            'STATUS' => 13,
            'status_change_date' => date('Y-m-d')
        );
        if ($this->contact_model->updateNotes($data, $where)) {
            $status = 'Note Deactivated Sucessfully';
        } else {
            $this->ibizErrors[] = 'Note not Deactivated Try Again!!';
        }
        $return = array(
            'hasError' => !empty($this->ibizErrors),
            'errors' => $this->ibizErrors,
            'status' => $status,
        );
        echo json_encode($return);
    }

}
