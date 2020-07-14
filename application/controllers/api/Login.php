<?php
  
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Login extends REST_Controller
{
public function __construct()
    {
         header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
           die();
        }

        parent::__construct();
        $this->load->model('login_model', 'lmodel');
    }
    public function token_get()
    {
        $tokenData = array();

        $tokenData['id'] = 1; //TODO: Replace with data for token
        $output['token'] = AUTHORIZATION::generateToken($tokenData);
        $this->set_response($output, REST_Controller::HTTP_OK);
    }

public function token_post()
    {
        $headers = $this->input->request_headers();

        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                $this->set_response($decodedToken, REST_Controller::HTTP_OK);
                return;
            }
        }

        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }


public function oauth_post()
{
  $jsonArray = json_decode($this->input->raw_input_stream);
   // print_r($jsonArray);
     $result = $this->lmodel->validate_student($jsonArray->loginName,$jsonArray->Password);
     if($result != null){
        $tokenData['First_name'] = $result[0]->First_name;
        $tokenData['Last_name'] = $result[0]->Last_name;
        $output['token'] = AUTHORIZATION::generateToken($tokenData);
        $result['token'] = $output['token'];
        $output['firstName'] = $result[0]->First_name;
        $output['lastName'] = $result[0]->Last_name;
         $output['username'] = $result[0]->email;
          $output['password'] = $result[0]->id;
          $output['id'] = $result[0]->id;
        
    $this->set_response($output, REST_Controller::HTTP_OK);  
} else{
   $this->set_response($result, REST_Controller::HTTP_OK);   
}
}

public function sendSMS_post(){

    $phone ="9739559700"; //9980157272
    $message="Hello RaviKumar, You have initiated the process of Bharathi Axa Life insurance";

   $url='http://alerts.solutionsinfini.com/api/v4/?api_key=Aa543cc5eb4515636710a30ba2a36b8f3&method=sms&message='.$message.'&to='.$phone.'&sender=BAXAGI'; 
   $status = null;
   $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $head = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
           
            if(!$head)
            {
               // return FALSE;
            }
           
            if($status === null)
            {
                if($httpCode < 400)
                {
                   // return TRUE;
                }
                else
                {
                  //  return FALSE;
                }
            }
            elseif($status == $httpCode)
            {
                //return TRUE;
            }
            $this->set_response($output, REST_Controller::HTTP_OK);  
}


public function payment_post()
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
}

    ?>