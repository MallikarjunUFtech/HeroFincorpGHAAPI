<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/*
 * Changes:
 * 1. This project contains .htaccess file for windows machine.
 *    Please update as per your requirements.
 *    Samples (Win/Linux): http://stackoverflow.com/questions/28525870/removing-index-php-from-url-in-codeigniter-on-mandriva
 *
 * 2. Change 'encryption_key' in application\config\config.php
 *    Link for encryption_key: http://jeffreybarke.net/tools/codeigniter-encryption-key-generator/
 * 
 * 3. Change 'jwt_key' in application\config\jwt.php
 *
 */

class Auth extends REST_Controller
{
    /**
     * URL: http://localhost/CodeIgniter-JWT-Sample/auth/token
     * Method: GET
     */


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
        //$this->load->model('cestandard_model', 'cestdmodel');
    }

    public function token_get()
    {
        $tokenData = array();

        $tokenData['id'] = 1; //TODO: Replace with data for token
        $output['token'] = AUTHORIZATION::generateToken($tokenData);
        $this->set_response($output, REST_Controller::HTTP_OK);
    }

    /**
     * URL: http://localhost/CodeIgniter-JWT-Sample/auth/token
     * Method: POST
     * Header Key: Authorization
     * Value: Auth token generated in GET call
     */
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


    public function authenticate_post()
    {
         $jsonArray = json_decode($this->input->raw_input_stream, true);
     $jsonArray = json_decode($this->input->raw_input_stream);
 //   print_r($_POST);
   //     print_r($jsonArray);
        
       // $output['firstName'] = 'shasidhar';
        //$output['lastName'] = 'maganti';

        $output['token'] = AUTHORIZATION::generateToken($tokenData);
        //print_r($tokenData);
        $this->set_response($output, REST_Controller::HTTP_OK);   
        
    }
}