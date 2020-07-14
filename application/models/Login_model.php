<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model{
   
    
    public function __construct()
    {
        parent::__construct();
       // $this->_sa = $this->load->database('sa', TRUE);
    }
    public function validate_student($loginName, $Password){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('email', $loginName);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1)
        {
          $result = $query->result();
          if (Password::validate_password($password, $result[0]->Password))
          {
            return $result;
          }
          return false;
        }
        return false;
    }
  

    public function addCustomer($datafile)
    {
      $data1 = $datafile->data;
       $obj11 = $data1->obj1;
       $obj22 = $data1->obj2;
       try{
        $row = array(
        'salutation'  => $this->db->escape_str($obj22->salutation),
        'first_name' =>$this->db->escape_str($obj22->firstName),
        'lastname'  =>$this->db->escape_str($obj22->lastName),
        'gender'  => $this->db->escape_str($obj22->gender),
        'age' => $this->db->escape_str($obj22->age),
        'email'  => $this->db->escape_str($obj22->email),
        'dependent_count'  => $this->db->escape_str($obj22->family),
        'created_by' => 1,
        'modified_by'  => 1,
        'mobile'  => $this->db->escape_str($obj22->mobile),
        'modified_date' => 'now()',
        'address1'  => $this->db->escape_str($obj11->address1),
        'address2'  => $this->db->escape_str($obj11->address2),
        'address3'  => $this->db->escape_str($obj11->address3),
        'state'  => $this->db->escape_str($obj11->state),
        'pincode'  => $this->db->escape_str($obj11->pincode),
        'pancard'  => $this->db->escape_str($obj11->pan_card),
        'city'  => $this->db->escape_str($obj11->city),
      );  
         $this->db->insert('customer', $row);   
         $insert_id = $this->db->insert_id();
         $qry ="select * from customer where cust_id = ?";
        $result = $this->db->query($qry,array($this->db->escape_str($insert_id)))->result(); 

         if(count($result) >0){
          //   $familySize = $this->getSize($obj22->family);  
          //   for($i=1;$i<=$familySize;$i++){
          //     $row = array(
          //     "cust_id" => $this->db->escape_str($result[0]->cust_id),
          //     "name" => $this->db->escape_str($obj11->gha_spouse_name.$i),
          //     "relation" =>$this->db->escape_str($obj11->gha_spouse_relation.$i),
          //     "gender" =>$this->db->escape_str($obj11->gha_spouse_gender.$i),
          //     "DOB" =>$this->db->escape_str($obj11->gha_spouse_dob.$i),
          //     "height" =>$this->db->escape_str($obj11->gha_spouse_height.$i),
          //     "weight" =>$this->db->escape_str($obj11->gha_spouse_weight.$i),
          // );
          //   $this->db->insert('cust_dependent', $row); 
          //  }

                $row = array(
                  "cust_id" =>   $this->db->escape_str($result[0]->cust_id),
                  "name" => $this->db->escape_str($obj11->gha_nominee),
                  "age" =>$this->db->escape_str($obj11->gha_nominee_dob),
                  "relation" =>$this->db->escape_str($obj11->gha_nominee_relation),
                  
              );
                $this->db->insert('nominee', $row);  
              
                $row = array(
                  "cust_id" => $this->db->escape_str($result[0]->cust_id),
                  "policy_Details"=>$obj22->family,
                  "premium"=>$obj22->premium,
                  "GST"=>$obj22->tax,
                  "Total_premium"=>$obj22->totalpremium,
                  "start_date"=>$obj11->policy_start,
                  "end_date"=>$obj11->policy_end,
                  "sum_assured"=>$obj22->sumAssured,
                
              );
                $this->db->insert('policy_details', $row);  
                  
                $audit = array(
                  "cust_id" => $result[0]->cust_id,
                  "audit_date"=> now(),
                  "audit_data"=> json_encode($row)
              );
                $this->db->insert('auditlog', $audit); 

        }
        return $result;
    }  catch(Exception $e){
         return null;   
        }
      
    }


public function getCustomerInfo($cust_id) {
        $customer= array();
        $dependents = array();
        $nominee = array();
        $policy_details = array();
        $qry ="select * from customer where cust_id = ?";
        $customer = $this->db->query($qry,array($this->db->escape_str($cust_id)))->result(); 
        if(count($customer)>0)
        {
        $qry ="select * from cust_dependent where cust_id = ?";
        $dependents = $this->db->query($qry,array($this->db->escape_str($cust_id)))->result(); 
        $qry ="select * from nominee where cust_id = ?";
        $nominee = $this->db->query($qry,array($this->db->escape_str($cust_id)))->result(); 
        $qry ="select * from policy_details where cust_id = ?";
        $policy_details = $this->db->query($qry,array($this->db->escape_str($cust_id)))->result(); 
        }

        return array("customer"=>$customer,"dependents"=>$dependents,"nominee"=>$nominee,"policy_details"=>$policy_details );

}

public function updateCustomer($datafile,$cust_id) {

        $data = $datafile->data;
        $user = $datafile->user;
        $dep  = $datafile->user;
        $nominee  = $datafile->nominee;
        $policy_details  = $datafile->policy_details;
          try{
        $row = array(
        'first_name' =>$this->db->escape_str($data->firstName),
        'lastname'  =>$this->db->escape_str($data->lastName),
        'gender'  => $this->db->escape_str($data->gender),
        'age' => $this->db->escape_str($data->age),
        'email'  => $this->db->escape_str($data->email),
        'dependent_count'  => $this->db->escape_str($data->family),
        'created_by' => $this->db->escape_str($user->id),
        'modified_by'  => 'now()',
        'mobile'  => $this->db->escape_str($data->mobile),
        'modified_date' => 'now()',
        'address1'  => $this->db->escape_str($data->address1),
        'address2'  => $this->db->escape_str($data->address2),
        'address3'  => $this->db->escape_str($data->address3),
        'state'  => $this->db->escape_str($data->state),
        'pincode'  => $this->db->escape_str($data->pin),
        'pancard'  => $this->db->escape_str($data->pan)
		);  
        $this->db->where('cust_id', $data->cust_id);
         $this->db->upddata('customer', $row);   
		$familySize = getSize($data->family);   
        for($i=1;$i<=$familySize;$i++){
        if($dep->fullname1 != ""){
        $row = array(
        "cust_id" => $this->db->escape_str($result[0]->cust_id),
        "name" => $this->db->escape_str($dep->fullname.$i),
        "relation" =>$this->db->escape_str($dep->relation.$i),
        "gender" =>$this->db->escape_str($dep->gender.$i),
        "DOB" =>$this->db->escape_str($dep->DOB.$i),
        "height" =>$this->db->escape_str($dep->height.$i),
        "weight" =>$this->db->escape_str($dep->weight.$i),
        "created_by" =>1
    );
        $this->db->where('id', $dep->id);
      $this->db->upddata('cust_dependent', $row); 

      $row = array(
        "cust_id" =>   $this->db->escape_str($result[0]->cust_id),
        "name" => $this->db->escape_str($nominee->nominee),
        "age" =>$this->db->escape_str($nominee->age),
        "relation" =>$this->db->escape_str($nominee->releationship),
        "address" =>$this->db->escape_str($nominee->address),
        "created_by" =>1
    );
      $this->db->where('id', $nominee->id);
      $this->db->upddata('nominee', $row);  
    
      $row = array(
        "cust_id" => $this->db->escape_str($result[0]->cust_id),
         "policy_Details"=>1,
         "policy_premium"=>1,
        "created_by" =>1,
        "created_on"=> now(),
        "questionaries"=> json_encode($policy_Details)
    );
      $this->db->where('id', $data->cust_id);
      $this->db->upddata('policy_details', $row);  
         }   
  
       $audit = array(
        "cust_id" => $user->id,
        "audit_date"=> now(),
        "audit_data"=> json_encode($row)
    );
      $this->db->insert('auditlog', $audit); 
        return "Success";
        }
    }


        catch(Exception $e){
         return null;   
        }
        



}


public function getQuestionaries ($id)
{
    $qry ="select * from quationaries q,policy_map_questions pq where pq.policy_type = ? and q.id  = pq.questionaries order by pq.seqOrder";
        $result = $this->db->query($qry,array($this->db->escape_str($id)))->result(); 
        return $result;
}

public function dependents($data){
    $dep = $data->data;
    $cust = $data->cust;
    $user = $data->user;
    
     $policy_Details = $data->questionaries;
     print_r($cust);
    for($i=1;$i<=5;$i++){
        if($dep->fullname1 != ""){
    $row = array(
        "cust_id" => $cust->id,
        "name" => $dep->fullname.$i,
        "relation" =>$dep->relation.$i,
        "gender" =>$dep->gender.$i,
        "DOB" =>$dep->DOB.$i,
        "height" =>$dep->height.$i,
        "weight" =>$dep->weight.$i,
        "created_by" =>$user->id
    );
      $this->db->insert('cust_dependent', $row);  
      $audit = array(
        "cust_id" => $user->id,
        "audit_date"=> now(),
        "audit_data"=> json_encode($row)
    );
      $this->db->insert('auditlog', $audit);  
}

}

if($dep->nominee !=""){
    print_r($cust);
    $row = array(
        "cust_id" =>   $cust->id,
        "name" => $dep->nominee,
        "age" =>$dep->age,
        "relation" =>$dep->releationship,
        "address" =>$dep->address,
        "created_by" =>$user->password
    );
      $this->db->insert('nominee', $row);  
    $audit = array(
        "cust_id" => $user->id,
        "audit_date"=> now(),
        "audit_data"=> json_encode($row)
    );
      $this->db->insert('auditlog', $audit);   
}


if(count($data->questionaries)>0){
    $row = array(
        "cust_id" => $cust->id,
         "policy_Details"=>1,
         "policy_premium"=>1,
        "created_by" =>$user->id,
        "created_on"=> now(),
        "questionaries"=> json_encode($policy_Details)
    );
      $this->db->insert('policy_details', $row);  
    
    $audit = array(
        "cust_id" => $user->id,
        "audit_date"=> now(),
        "audit_data"=> json_encode($policy_Details)
    );
      $this->db->insert('auditlog', $audit); 

}
return true;



}

public function send_sms($data){
   $url=SMS_URL.'?api_key='.SMS_API_KEY.'&method=sms&message='.$data['msg'].'&to='.$data['phone'].'&sender='.SMS_SENDER; 
   $status = null;
   $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $head = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
           
            return $httpCode;
}


public function send_Mail($data){
   require APPPATH.'/libraries/class.phpmailer.php';
   $mail = new PHPMailer();
    
    $mail->IsSMTP();
    $mail->SMTPAuth   = true;
    $mail->Mailer = SMTP_TYPE;
    $mail->Host= SMTP_URL; // Amazon SES
    $mail->Port = SMTP_PORT;  // SMTP Port
    $mail->Username   = SMTP_USERNAME;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SetFrom(SMTP_FROM, SMTP_SENDER); //from (verified email address)
    $mail->Subject = $data['sub']; //subject
    
    //message
    $body = $data['msg']; 
    //$body = eregi_replace("[\]",'',$body);
    $mail->MsgHTML($body);
    //
    
    //recipient
    $mail->AddAddress($data['email'], $data['name']);
    
    //Success
    
    if ($mail->Send()) {
        //echo "Message sent!"; 
    }
    //Error
    if(!$mail->Send()) {
       // echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
   


public function getSize($data){
    if($data =='1A'){
    return 1;
    }else if($data =='2A'){
    return 2;
    }else if($data =='2A1C'){
    return 3;
    }else if($data=='2A2C'){
    return 4;
    }else if($data =='2A3C'){
    return 5;
    }else if($data =='1A1C'){
    return 2;
    }else if($data =='1A2C'){
    return 3;
    }
    else if($data =='1A3C'){
    return 4;
    }
}

/*OTP related*/
public function sendLink(){

   $data = md5("cust_Id=1");
   $myObj = array("phone"=>"9845218738","email"=>"pudayashankar@gmail.com", 
   "msg"=>"Hello Shasidhar, Thank you for registering in our site, Please click the link ".SEND_LINK."/".$data."  and verify your details and proceed to pay the premium","sub"=>"Insurance Policy","name"=>"shasidhar Maganti"); 
      $this->send_sms( $myObj);
      $this->send_Mail($myObj); 
      return true;

}

public function sendOTP($jsonArray)
{
$random = $this->makeid();
$row = array(
        "otp_numer"=>$random,
        "OTP_Applied_status" =>0
       // "OTP_sent_time"=> `current_timestamp()`
    );
      $this->db->set('OTP_sent_time', 'current_timestamp()', FALSE);
      $this->db->where('cust_id', $jsonArray->cust_id);
      $this->db->update('customer', $row);  
$data = md5("cust_Id=".$jsonArray->cust_id);
$myObj = array(
  "phone"=>$jsonArray->mobile,
  "email"=>$jsonArray->email,
   "msg"=> " Hello ".$jsonArray->firstName.", Thank you for registering in our site, Please click the link ".SEND_LINK."/".$data."  and verify your details and proceed to pay the premium",
   "sub"=>"Insurance Policy",
   "name"=>$jsonArray->firstName); 
      
      $this->send_sms( $myObj);
       $this->send_Mail($myObj); 
      return true;
}


public function validateOTP($data){
$qry="select * from customer where otp_numer = '".$this->db->escape_str($data)."' and OTP_Applied_status=0 and  TIMESTAMPDIFF(SECOND, customer.OTP_sent_time, current_timestamp())<=120";
$result= array();
$result = $this->db->query($qry)->result();
  if(count($result)>0){
      $row = array(
          "OTP_Applied_status"=>1
      );
        $this->db->where('cust_id', $result[0]->cust_id);
        $this->db->update('customer', $row); 

        return true;
  }else{
    return false;
  }
}

private function makeid() {
   $digits_needed=6;
    $random_number=''; // set up a blank string
    $count=0;
while ( $count < $digits_needed ) {
    $random_digit = mt_rand(0, 9);
    $random_number .= $random_digit;
    $count++;
}

   return $random_number;
}


}