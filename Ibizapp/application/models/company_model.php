<?php

class company_model extends common_model {

    public function getGridDetails($aColumns, $sWhere, $sOrder, $sLimit, $is_admin) {
        $sql = "SELECT SQL_CALC_FOUND_ROWS A.* FROM (select comp.Company_ID as Company_ID, comp.Name as Name, st.Short_Name as Status, CASE WHEN comp.Url is NULL THEN '-' ELSE comp.Url END as Url,comp.Company_type as Company_Type from " . DB_PREFIX . "company comp, " . DB_PREFIX . "status_lookup st where comp.Status = st.Status";
        $sql.=($is_admin ? "" : " AND comp.Status =1");
        $sql.=" AND comp.Company_type != 'Candidate Reference' order by comp.Status) A " . $sWhere . $sOrder . $sLimit;
        $query = $this->db->query($sql);
        $count = $this->getLastTotalRows();
        if ($query->num_rows()) {
            return array('companyList' => $query->result(), 'count' => $count);
        } else {
            return array('companyList' => '', 'count' => 0);
        }
    }

    public function getCompanyDetails($company_id) {
        $sql = "select comp.Company_ID, comp.Name, st.Short_Name as status, CASE WHEN comp.Nature_Of_Business is NULL THEN '-' ELSE comp.Nature_Of_Business END as Nature_Of_Business, CASE WHEN comp.Address_Id is NULL THEN '-' ELSE comp.Address_Id END, CASE WHEN comp.Url is NULL THEN '-' ELSE comp.Url END as URL, comp.Created_Date, comp.Created_By, comp.Company_type, comp.NDA_Signed, CASE WHEN comp.NDA_Signed_By is NULL THEN '-' ELSE comp.NDA_Signed_By END as NDA_Signed_By, CASE WHEN comp.Position_Title is NULL THEN '-' ELSE comp.Position_Title END as Position_Title, CASE WHEN comp.Signed_Date is NULL THEN '-' ELSE comp.Signed_Date END as Signed_Date from " . DB_PREFIX . "company comp, " . DB_PREFIX . "status_lookup st where comp.Status = st.Status and comp.Company_ID =" . $company_id;

        $query = $this->db->query($sql);

        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    /* public function insertAddressDetails($values,$table="address")
      {
      return $this->insertValues(DB_PREFIX.$table,$values);
      } */

    public function insertCompanyDetails($values, $table = "company") {
        return $this->insertValues(DB_PREFIX . $table, $values);
    }

    public function updateCompanyDetails($values, $where, $table = "company") {
        return $this->updateDatas(DB_PREFIX . $table, $values, $where);
    }

    public function getCompanies($Status = false) {

        $sql = "SELECT Company_ID, Name from " . DB_PREFIX . "company";
        $sql.=$Status ? " where Status = " . $Status : "";

        $query = $this->db->query($sql);

        if ($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checkforExistingCompany($company_name, $company_id = false) {
        $sql = "SELECT Company_ID FROM " . DB_PREFIX . "company WHERE Name='" . $company_name . "'";
        $sql.=($company_id ? " AND Company_ID<>" . $company_id : '');
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getCompanyCurrentStatus($company_id) {
        $sql="SELECT Status,status_change_date FROM ". DB_PREFIX . "company WHERE Company_ID=".$company_id;
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

}
