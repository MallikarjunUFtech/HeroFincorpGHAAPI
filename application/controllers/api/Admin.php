<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Admin extends REST_Controller
{
public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];

        if($method == "OPTIONS") {
            die();
        }

        parent::__construct();
        $this->load->model('student_model', 'smodel');
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

public function getMessage_post()
{

    $this->set_response("", REST_Controller::HTTP_OK);     
}

public function sendMessage_post()
{

    $this->set_response("", REST_Controller::HTTP_OK);     
}
public function markAttendance_post()
{

    $this->set_response("", REST_Controller::HTTP_OK);     
}
public function setattendance_post()
{

    $this->set_response("", REST_Controller::HTTP_OK);     
}

public function sendAssignment_post()
{

    $this->set_response("", REST_Controller::HTTP_OK);     
}
public function student_profile_post()
{

    $this->set_response("", REST_Controller::HTTP_OK);     
}
    }

    ?>