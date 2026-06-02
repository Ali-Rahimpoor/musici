<?php
require "../../init.php";
$phone = isset($_POST['phone']) ? db_escape($_POST['phone']) : '';
$otp = isset($_POST['otp']) ? intval($_POST['otp']):false;
$sql = "SELECT * FROM otp_codes WHERE phone = '$phone' ORDER BY created_at DESC LIMIT 1 ";
$result = db_query($sql);
if(!$result){
   sendJson(['message'=>"مشکلی به وجود آمد"]);
}
if(!$result -> num_rows){
   sendJson([
      'message'=>'کد اشتباه هست'
   ],400);
}
$otp_row = mysqli_fetch_assoc($result);
if($otp_row['code'] != $otp){
   $id = $otp_row['ID'];
   db_query("UPDATE otp_codes SET try_count = try_count + 1 WHERE ID = '$id' ");
   sendJson([
      'message'=>'کد اشتباه هست'
   ],400);
}
if($otp_row['try_count'] >= 3){
   sendJson([
      'message'=>"تعداد دفعات ورود مجاز نیست"],400
   );
}

if($otp_row['status'] == 'used'){
   sendJson([
      'message'=>'کد استفاده شده است'
   ],400);
}

if(date("Y-m-d H:i:s") >= $otp_row['expire_at']){
   sendJson([
      'message'=>'کد منقضی شده است'
   ],400);
}
db_update('otp_codes',[
   'status'=>'used',
],['ID'=>$otp_row['ID']]);
$user = get_user_by_phone($phone);
if(!$user){
   sendJson([
      'message'=>'خطایی رخ داده است در سرور'
   ],500);
}
$_SESSION['user_id']=($user['ID']);

$result = [
   'message'=>"ورود با موفقیت انجام شد",
   'redirect'=>site_url('panel/')
];
sendJson($result);