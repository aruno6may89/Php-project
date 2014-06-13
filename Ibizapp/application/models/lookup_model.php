<?php

class lookup_model extends common_model
{
    public function getDisplayList($type)
    {
        $query=$this->db->query("SELECT * FROM `".DB_PREFIX."common_lookup` WHERE `type`='$type'");
        if($query->num_rows())
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }
    
    public function getStatus($status_type) {
        $sql="select Status, Short_Name from ".DB_PREFIX."status_lookup where status_type = '".$status_type."' order by display_order";
        $query=$this->db->query($sql);
        if($query->num_rows())
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }
}

