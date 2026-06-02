<?php
function send_otp ($to,$code){
   $url = "https://api.sms.ir/v1/send/verify";
   $ch = curl_init();
   if(defined('SMS_DEBUG_PHONE') && SMS_DEBUG_PHONE){
    $to = SMS_DEBUG_PHONE;
   }
   $data = [
    "mobile"=> $to,
    "templateId"=> 157715,
    "parameters"=> [
      [
        "name"=> "CODE",
        "value"=> $code      
      ]
    ]
   ];
   curl_setopt_array($ch,[
          CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 30, 
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_POST=>true,
        CURLOPT_POSTFIELDS=>json_encode($data),
      CURLOPT_HTTPHEADER=>[
         'Accept: application/json',
         'X-API-KEY:'. SMS_API_KEY,
         'Content-Type: application/json'
      ],
   ]);
    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result);
    return $result->data->messageId;
}
function generate_otp($otp_length){
  $otp = '';
  for($i=0;$i<$otp_length;$i++){
    if(!$i){
      $otp .= rand(1,9);
    }else{
      $otp.=rand(0,9);
    }
  }
    return $otp;
}