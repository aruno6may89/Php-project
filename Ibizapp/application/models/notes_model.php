<?php

class Notes_model extends common_model
{
    public function getallnotes($entity_id,$entity_type)
    {
        $sql="SELECT note_id, entity_id, comments, created_date, created_by, insert_date, insert_time, case when time_spend is not null then time_spend else '--' end as time_spend, STATUS, case when call_status is not null then call_status else '--' end as call_status, note_type, entity_type from rms_notes where Status = 12 and entity_id=".$entity_id." and entity_type='".$entity_type."' order by insert_date desc, insert_time desc";
        //echo $sql;
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