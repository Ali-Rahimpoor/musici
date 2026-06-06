<?php
function get_current_user_id(){
   return isset($_SESSION['user_id']) ? $_SESSION['user_id']:0;
}
function check_user_login(){
 $userId = get_current_user_id();
 
 if($userId){
   return ;
 }else{
   redirect(panel_url('login.php'));
 }
}
function do_logout(){
   unset($_SESSION['user_id']);
}
function get_user_by_phone($phone){
  $sql = "SELECT * FROM users WHERE phone = '$phone' ";
  $res = db_query($sql);
  if($res && $res -> num_rows){
    return mysqli_fetch_assoc($res);
  }else{
    sendJson(['message'=>'این کاربر ثبت نام نکرده ابتدا ثبت نام کنید'],400);
  }
}
function get_user_by_id($id){
  $sql = "SELECT * FROM users WHERE ID = '$id'";
  $res = db_query($sql);
  if($res && $res->num_rows){
    return mysqli_fetch_assoc($res);
  }
}
function is_user_admin($user_role='user'){
  if($user_role == 'user'){
    print_r('دسترسی غیر مجاز');exit;
  }
}
function check_user_admin($user_id){
   $sql = "SELECT role FROM users WHERE ID = '$user_id'";
   $res = db_query($sql);
   if($res && $res -> num_rows){
    $role =  mysqli_fetch_column($res);
    return $role ==='admin' ? true : false;
   }
  return false;
}