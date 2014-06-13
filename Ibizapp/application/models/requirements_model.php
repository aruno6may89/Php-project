<?php

class requirements_model extends common_model {

    public function getRequirementGridDetails($sWhere, $sOrder, $sLimit, $recruiter_id = false) {
        if ($recruiter_id) {
            $sql = "SELECT SQL_CALC_FOUND_ROWS A.* FROM (SELECT distinct req.requirement_id,Recruiters.Recruiter as Recruiter,st.Short_Name as Status,req.Position_title as Position_title,req.Location as Location,req.Skills as Skills,CONCAT(CASE WHEN c.First_Name IS NULL THEN '--' ELSE c.First_Name END, CASE WHEN c.Last_Name IS NULL THEN '' ELSE c.Last_Name END,'<br>', CASE WHEN c.Email1 IS NULL THEN '--' ELSE c.Email1 END,'<br>', CASE WHEN a.Cell IS NULL THEN '--' ELSE a.Cell END) as Contact from rms_requirements req LEFT OUTER JOIN rms_contact c on req.contact_id = c.Reporting_PersonID LEFT OUTER JOIN rms_address a on a.address_id = c.address_id,   rms_status_lookup st,rms_assigned_requirement ar,(select req.requirement_id, GROUP_CONCAT(distinct rec.First_Name SEPARATOR '<br>') as Recruiter from rms_recruiter rec,rms_requirements req,rms_assigned_requirement ar1 where ar1.Recruiter_ID = rec.Recruiter_ID
AND  ar1.requirement_id=req.requirement_id and ar1.requirement_id in (select ar2.Requirement_ID from rms_assigned_requirement ar2 where ar2.Recruiter_ID =" . $recruiter_id . " AND ar2.Assignment_Stauts = 31 ) GROUP BY req.requirement_id) Recruiters where ar.Recruiter_ID = " . $recruiter_id . " AND st.Status=req.status AND req.status != 21 and req.requirement_id = ar.Requirement_ID AND Recruiters.requirement_id=req.requirement_id ) A " . $sWhere . $sOrder . $sLimit;
        } else {
            $sql = "SELECT SQL_CALC_FOUND_ROWS A.* FROM (SELECT distinct req.requirement_id,GROUP_CONCAT(rec.First_Name SEPARATOR '<br>') as Recruiter,st.Short_Name as Status,req.Position_title as Position_title,req.Location as Location, req.Skills as Skills,CONCAT(CASE  WHEN c.First_Name IS  NULL THEN  '--' ELSE c.First_Name END, CASE  WHEN c.Last_Name IS  NULL THEN  '' ELSE c.Last_Name END,'<br>', CASE  WHEN c.Email1 IS  NULL THEN  '--' ELSE c.Email1 END,'<br>', CASE  WHEN a.Cell IS  NULL THEN  '--' ELSE a.Cell END) as Contact from " . DB_PREFIX . "requirements req LEFT OUTER JOIN " . DB_PREFIX . "contact c on req.contact_id = c.Reporting_PersonID LEFT OUTER JOIN " . DB_PREFIX . "address a on a.address_id =  c.address_id, " . DB_PREFIX . "assigned_requirement ar, " . DB_PREFIX . "recruiter rec , " . DB_PREFIX . "status_lookup st where ar.Assignment_Stauts = 31 and st.Status=req.status AND req.requirement_id = ar.Requirement_ID and ar.Recruiter_ID = rec.Recruiter_ID";

            $sql.=" GROUP BY req.requirement_id order by req.Position_title";
            $sql.=") A " . $sWhere . $sOrder . $sLimit;
        }

        $query = $this->db->query($sql);
        $count = $this->getLastTotalRows();
        if ($query->num_rows()) {
            return array('reqList' => $query->result(), 'count' => $count);
        } else {
            return array('reqList' => '', 'count' => 0);
        }
    }

    public function req_check($positionid, $source) {
        $sql = "select requirement_id from " . DB_PREFIX . "requirements where Position_ID = '" . $positionid . "' and Source = '" . $source . "'";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return true;
        } else {
            return false;
       }
    }

    public function getRequirementDetails($requirement_id) {
        $sql = "SELECT req.requirement_id, req.Position_title as Position_title, CASE WHEN req.Start_Date IS  NULL THEN  '--' WHEN req.Start_Date like '00%' THEN  '--' ELSE req.Start_Date END as Start_Date, CASE WHEN req.Close_Date IS NULL THEN '--' WHEN req.Close_Date like '00%' THEN  '--' ELSE req.Close_Date END as Close_Date, CONCAT(CASE  WHEN c.First_Name IS  NULL THEN  '--' ELSE c.First_Name END, CASE  WHEN c.Last_Name IS  NULL THEN  '' ELSE c.Last_Name END) as contact_name, req.Location as Location, req.Skills, req.billing_rate as billing_rate, CASE WHEN req.Requirement_Detail IS NULL THEN '--' ELSE req.Requirement_Detail END as Requirement_Detail, concat(rec.First_Name, ' ',rec.Last_Name), CASE WHEN req.End_Client_Name IS NULL THEN '--' ELSE req.End_Client_Name END as End_Client_Name, CASE  WHEN c.Email1 IS  NULL THEN  '--' ELSE c.Email1 END as Email1, CASE  WHEN a.Work_Phone IS  NULL THEN  '--' ELSE a.Work_Phone END as Work_Phone, CASE WHEN req.priority IS NULL THEN '--' ELSE req.priority END as priority, req.Tax_Term as Tax_Term, st.Short_Name as status, req.company_id, CASE WHEN req.to_time_frame IS NULL THEN '' ELSE req.to_time_frame END as to_time_frame, req.Position_ID as Position_ID, rec.Recruiter_ID, req.Source as Source, CASE  WHEN req.from_time_frame IS  NULL THEN  '' ELSE req.from_time_frame END as from_time_frame, req.time_frame_desc as time_frame_desc,req.Contact_ID,c.Address_ID  from " . DB_PREFIX . "requirements req LEFT OUTER JOIN " . DB_PREFIX . "contact c on req.contact_id = c.Reporting_PersonID LEFT OUTER JOIN " . DB_PREFIX . "address a on a.Address_Id =  c.Address_ID, " . DB_PREFIX . "status_lookup st, " . DB_PREFIX . "recruiter rec where req.Created_By = rec.Recruiter_ID and req.status = st.Status and req.requirement_id =" . $requirement_id;
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function getReqSubmitList($requirement_id) {

        $sql = "SELECT CONCAT(cand.First_Name, CASE WHEN cand.Last_Name IS NULL THEN '--' ELSE cand.Last_Name END) as cand_name, CASE WHEN address.Cell IS NULL THEN '--' ELSE address.Cell END as cell, CASE WHEN address.Work_Phone IS NULL THEN '--' ELSE address.Work_Phone END as phone, CASE WHEN address.Home_Phone IS NULL THEN '--' ELSE address.Home_Phone END as homePhone, rec.First_Name as rec_name, CASE WHEN comp.name IS NULL THEN '--' ELSE comp.name END as comp_name, cand_req.Submitted_Date as Submitted_Date, st.Short_Name as status, cand_req.candidate_id, cand_req.Line_No, req.Close_Date FROM " . DB_PREFIX . "requirements req LEFT OUTER JOIN " . DB_PREFIX . "company comp on comp.company_id = req.company_id, " . DB_PREFIX . "candidate_req cand_req, " . DB_PREFIX . "candidate cand, " . DB_PREFIX . "Status_Lookup st, " . DB_PREFIX . "recruiter rec, " . DB_PREFIX . "address address where req.requirement_id = cand_req.requirement_id and cand_req.Submitted_Status = st.Status and rec.recruiter_id = cand_req.Submitted_By and cand.candidate_id = cand_req.candidate_id and cand.Address_id = address.Address_id and cand_req.requirement_id =" . $requirement_id;
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_cand_submit_list($candidate_id) {

        $sql = "SELECT res.resume_id, res.candidate_id, res.description, res.file_name, res.upload_date, rec.First_Name, CASE WHEN comp.name IS NULL THEN '--' ELSE comp.name END, CASE WHEN rec1.first_name IS NULL THEN '--' ELSE rec1.first_name END, CASE WHEN cand_req.Submitted_Date IS NULL THEN '--' ELSE cand_req.Submitted_Date END as Submitted_Date FROM " . DB_PREFIX . "requirements req RIGHT OUTER JOIN " . DB_PREFIX . "candidate_req cand_req ON req.requirement_id = cand_req.requirement_id RIGHT OUTER JOIN " . DB_PREFIX . "resume res ON res.resume_id = cand_req.resume_id left OUTER JOIN " . DB_PREFIX . "company comp ON comp.company_id = req.company_id left OUTER JOIN " . DB_PREFIX . "recruiter rec1 ON rec1.recruiter_id = cand_req.Submitted_By, " . DB_PREFIX . "recruiter rec where res.upload_by = rec.recruiter_id and res.Candidate_id =" . $candidate_id;

        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_Line_No($cand_id, $req_id) {
        $sql = "select max(Line_No) as Line_No from " . DB_PREFIX . "candidate_req where Candidate_Id = " . $cand_id . " and requirement_id = " . $req_id;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
            foreach ($query->result() as $row)
                $data = $row->Line_No;

        if ($data >= 1) {
            $New_Line_No = $data + 1;
        } else {
            $New_Line_No = 1;
        }
        return $New_Line_No;
    }

    public function get_resume_id($cand_id) {
        $sql = "select max(resume_id) as resume_id from " . DB_PREFIX . "resume where Candidate_Id = " . $cand_id . " and resume_status = 22";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    public function getUpdatedRequirementDetails($status_id,$company_id,$date) {
        $sql="SELECT Contact_ID,requirement_id FROM " . DB_PREFIX . "requirements WHERE status_change_date='".$date."' AND company_id=".$company_id." AND status=".$status_id;
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function insertSubmitResumeDetails($values, $table = "candidate_req") {
        return $this->insertValues(DB_PREFIX . $table, $values);
    }

    public function insertRequirementDetails($values, $table = "requirements") {
        return $this->insertValues(DB_PREFIX . $table, $values);
    }

    public function insertAssignedRequirementDetails($values, $table = "assigned_requirement") {
        return $this->insertValues(DB_PREFIX . $table, $values);
    }

    public function updateRequirementsDetails($values, $where, $table = "requirements") {
        return $this->updateDatas(DB_PREFIX . $table, $values, $where);
    }

    public function updateSubmitedResumeStaus($values, $where, $table = "candidate_req") {
        return $this->updateDatas(DB_PREFIX . $table, $values, $where);
    }

}
