<?php
require "../../init.php";
$phone = isset($_POST['phone']) ? db_escape($_POST['phone']) : '';
$current_time = dateTime();
$expire = get_setting('otp_duration',120);
$sql = "SELECT * FROM otp_codes WHERE phone = '$phone' AND status = 'pending' AND expire_at > '$current_time' ORDER BY created_at DESC LIMIT 1 ";
$res = db_query($sql);
if($res && $res -> num_rows){
   $otp = mysqli_fetch_assoc($res);
   $expire = strtotime($otp['expire_at']) - strtotime($current_time);
   $otp_code = $otp['code'];
}else{
   $random_code = generate_otp(get_setting('otp_length',4));
   $messageId = send_otp($phone,$random_code);
   if(!$messageId){
      sendJson(['message'=>'ارور'],500);
   }
   $otp_data = [
      'phone'=>$phone,
      'code'=>$random_code,
      'sms_id'=>$messageId,
      'expire_at'=>date('Y-m-d H:i:s',time()+$expire)
   ];
   $otp_id = db_insert('otp_codes',$otp_data);
   $otp_code = $random_code;
   if(!$otp_id){
      sendJson(['message' => 'سامانه پیامکی مشکل داره بعدا تلاش کنید'],500);
   }
   
}
sendJson([
   'message' => "کد با موفقیت ارسال شد",
   'phone'=>$phone,
   'expire'=> $expire,
   'code'=>$otp_code
]);