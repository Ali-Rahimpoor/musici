<?php 
require ('../../init.php');
$name = isset($_POST['name']) ? db_escape($_POST['name']) : '';
$username = isset($_POST['username']) ? db_escape($_POST['username']) : '';
$phone = isset($_POST['phone']) ? db_escape($_POST['phone']) : '';
$password = isset($_POST['password']) ? db_escape($_POST['password']) : '';

// VALIDATION
$message = "ثبت نام با موفقیت انجام شد";
$sql = "SELECT username FROM users WHERE username = '$username'";
$user_res = db_query($sql);
if($user_res && $user_res->num_rows){
$message = 'این نام کاربری وجود دارد';
sendJson(['message'=>$message],400);
}
$phone_res = db_query("SELECT phone FROM users WHERE phone = '$phone'");
if($phone_res && $phone_res -> num_rows){
   $message = 'این شماره تلفن از قبل وجود دارد';
sendJson(['message'=>$message],400);
}
$user_id = db_insert('users',[
   'name'=>$name,
   'username'=>$username,
   'phone'=>$phone,
   'password'=>$password,
]);

if($user_id){
   sendJson([
      'message'=>'ثبت نام با موفقیت انجام شد'
   ],201);
}else{
   sendJson([
      'message'=>'خطایی رخ داده'
   ],500);
}
