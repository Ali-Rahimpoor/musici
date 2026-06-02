<?php 
require "../../init.php";
$avatar = $_FILES['user_avatar'] ?? "";
$user_id = $_POST['user_id'] ?? "";
if(! isset($_FILES['user_avatar'])){
   sendJson([
      'message'=>'فایل آپلود نشده است'
   ],400);
}
if($avatar['error'] != 0){
   sendJson([
      'message'=>'فایل با مشکل آپلود شده !'
   ],400);
}
if($avatar['type'] != 'image/png'){
   sendJson([
      'message'=>'فرمت فایل فقط png !'
   ],400);
}
if($avatar['type'] != 'image/png' || $avatar['name'] != 'avatar.png'){
   sendJson([
      'message'=>'نوع فایل صیحیح نیست'
   ],400);
}
$upload_relative_dir = 'uploads/'.date('Y/m').'/';
$upload_dir = ABSPATH  . $upload_relative_dir;
if(!file_exists($upload_dir)){
   mkdir($upload_dir,0777,true);
}
$new_avatar_name = generate_random_string(10).'.png';
$upload_path = $upload_dir. $new_avatar_name;
if(! move_uploaded_file($avatar['tmp_name'],$upload_path)){
   sendJson([
      'message'=>'مشکلی به وجود آمده'
   ],400);
}
$avatar_url=site_url($upload_relative_dir.$new_avatar_name);

// if(isset($_POST['user_id']) && is_current_user_admin()){
//    $user_id = intval($_REQUEST['user_id']);
// }
db_update('users',
['avatar' => $avatar_url],
[
   'ID'=> $user_id
]
);
sendJson([
   'meesage'=>'تصویر آپلود شد',
   'avatar'=> $avatar_url
],200);