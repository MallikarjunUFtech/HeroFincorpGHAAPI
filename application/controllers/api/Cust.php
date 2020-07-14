<?php
  
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Cust extends REST_Controller
{
public function __construct()
    {
         header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];

        if($method == "OPTIONS") {
            die();
        }

        parent::__construct();
        $this->load->model('login_model', 'lmodel');
    }
    public function paymentGateway_post()
    {
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL,"https://www.billdesk.com/pgidsk/PGIMerchantPayment");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,"<InputMsg>BAXAGENINS|VDJD0965698654|NA|00000001.00|NA|NA|NA|INR|NA|R|baxagenins|NA|NA|F|WEB|SUMIT ASDFASDF|1571|B2C/defaultInst106|ASD@ASD.ASD|TPI-B2C-EHL|9878767877|https://uat.bhartiaxaonline.co.in/com.bagi.b2c.payment.B2CPayProcess.wcp|170687729</InputMsg>");
    
    // In real life you should use something like:
    // curl_setopt($ch, CURLOPT_POSTFIELDS, 
    //          http_build_query(array('postvar1' => 'value1')));
    
    // Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $server_output = curl_exec($ch);
    
    curl_close ($ch);
    
    $this->set_response($server_output, REST_Controller::HTTP_OK);  
    }
public function addCustomer_post()
{
    $jsonArray = json_decode($this->input->raw_input_stream);
    $result = $this->lmodel->addCustomer($jsonArray);
    // $output['firstName'] = $result[1]->first_name;
    // $output['lastName'] = $result[2]->lastname;
    $output['dependents'] = $jsonArray->data;
    $output['id'] = $result[0]->cust_id;     
    $this->set_response($output, REST_Controller::HTTP_OK);  
}

public function getQuestionaries_post(){
    $header = $this->input->get_request_header('Authorization');
   $header = str_replace("Bearer ","",$header);
    if(!$this->lmodel->validateToken($header))    {
        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED); 
    }
     $output = $this->lmodel->getQuestionaries("1");
    $this->set_response($output, REST_Controller::HTTP_OK);  
}

public function addDependents_post(){
    $jsonArray = json_decode($this->input->raw_input_stream);
    $header = $this->input->get_request_header('Authorization');
   $header = str_replace("Bearer ","",$header);
    if(!$this->lmodel->validateToken($header))    {
        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED); 
    }
     $output = $this->lmodel->dependents($jsonArray );
    $this->set_response($output, REST_Controller::HTTP_OK);  
}
public function getCustomerInfo_get()
{
    //$cust_id = 256;
     $cust_id =  $this->input->get('cust_id', TRUE);
    $output = $this->lmodel->getCustomerInfo($cust_id);
    $this->set_response($output, REST_Controller::HTTP_OK);  
}
/* OTP related */
public function sendLink_post(){

    $this->lmodel->sendLink();
     $this->set_response("success", REST_Controller::HTTP_OK);  
}

public function sendOTP_post(){
    $jsonArray = json_decode($this->input->raw_input_stream);
    $this->lmodel->sendOTP($jsonArray);
    $this->set_response("success", REST_Controller::HTTP_OK);  
    
}
/*
public function getCustomerInfo_post()
{
    $jsonArray = json_decode($this->input->raw_input_stream);
    $output = $this->lmodel->getCustomerInfo($jsonArray->cust_id);
    $this->set_response($output, REST_Controller::HTTP_OK);  
}
*/
public function validateOTP_post()
{
    $jsonArray = json_decode($this->input->raw_input_stream);
    $optData = $jsonArray->opt;
    $output = $this->lmodel->validateOTP($optData);
    if($output == true){
        $this->set_response(1, REST_Controller::HTTP_OK);  
    }else{
        $this->set_response(0, REST_Controller::HTTP_OK);  
    }
}

}
   

    ?>