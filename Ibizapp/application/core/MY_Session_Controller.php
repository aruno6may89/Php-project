<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Session_Controller extends CI_Controller {

    public $ibizErrors;
    public $status;
    public $callStatus;
    public $timeSpend;

    function __construct() {

        parent::__construct();
        if ($this->session->userdata('is_logged') === FALSE) {
            redirect('/login', 'refresh');
        } else {
            $this->load->model('lookup_model');
            $this->callStatus = $this->lookup_model->getDisplayList('call_status');
            $this->timeSpend = $this->lookup_model->getDisplayList('time_spend');
        }
    }

    public function replaceEmptyValuesNull($array) {
        foreach ($array as $i => $value) {
            if ($value === "") {
                $array[$i] = NULL;
            }
        }
        return $array;
    }

    public function replaceSymbolsWithEmptyValues($symbol, $array) {
        $array = (array) $array;
        foreach ($array as $i => $value) {
            if ($value === $symbol) {
                $array[$i] = "";
            }
        }
        return (object) $array;
    }

}

?>