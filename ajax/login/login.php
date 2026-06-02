<?php
require ('../../init.php');
$password = isset($_POST['password']) ? db_escape($_POST['password']) : '';
$username = isset($_POST['username']) ? db_escape($_POST['username']) : '';

$sql = "SELECT * FROM users WHERE password = '$password' AND username = '$username' ";

$res = db_query($sql);
if($res && $res -> num_rows){

   $user = mysqli_fetch_assoc($res);
   $user_id = $user['ID'];
   $_SESSION['user_id']=$user_id;
   sendJson([
      'message'=>'ورود موفق',
      'redirect'=>'index.php'
   ],200);
}else{
   sendJson(['ورود نا موفق'],400);
}