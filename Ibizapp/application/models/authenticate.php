<?php

class authenticate extends CI_Model
{
    public function validateUser()
    {
        $user_details=$this->db->query("SELECT U.*,L.name as role,L.id role_id,R.First_Name,R.Last_Name FROM ".DB_PREFIX."user_master U,".DB_PREFIX."recruiter R,".DB_PREFIX."common_lookup L WHERE U.password='".md5(ENCRYPTION_KEY.$this->input->post('password'))."' AND U.role_id=L.id AND U.user_name='".$this->input->post('username')."' AND R.Recruiter_ID=U.Recruiter_ID");
        if($user_details->num_rows())
        {
            $row = $user_details->row();
            return array('Recruiter_ID'=>$row->Recruiter_ID,'Role'=>$row->role,'username'=>$row->First_Name.' '.$row->Last_Name,'is_logged'=>1);
        }
        else {
            return false;
        }
    }
    
}

