<?php
require '../../init.php';

$artist_fullname = isset($_POST['fullname']) ? db_escape($_POST['fullname']) : '';
$artist_image = isset($_FILES['upload_artist_avatar']) ? $_FILES['upload_artist_avatar'] : '';
$redirect = panel_url('artist.php');

if(!$artist_fullname || !$artist_image){
   echo "<h1> Error ! </h1>";
   echo "<p>لطفا نام و تصویر برای هنرمند انتخاب کنید </p>";
   echo "<a href='$redirect'> برگشت </a>";
   http_response_code(400);
   exit;
}

if($artist_image['error'] != 0){
   sendJson([
      'message'=>'فایل با مشکل آپلود شده !'
   ],400);
}

// پشتیبانی از فرمت‌های مجاز
$allowed_types = [
   'image/png',
   'image/jpeg',
   'image/jpg',
   'image/webp'
];

if(!in_array($artist_image['type'], $allowed_types)){
   sendJson([
      'message'=>'فرمت فایل مجاز نیست! فرمت‌های مجاز: PNG, JPEG, JPG, GIF, WebP'
   ],400);
}

$upload_relative_dir = 'uploads/images/'.date('Y/m').'/';
$upload_dir = ABSPATH .  $upload_relative_dir;

if(!file_exists($upload_dir)){
   mkdir($upload_dir, 0777, true);
}

// گرفتن اکستنشن فایل
$file_extension = pathinfo($artist_image['name'], PATHINFO_EXTENSION);
$new_avatar_name = 'artist_' . generate_random_string(3) . '.' . $file_extension;
$upload_path = $upload_dir . $new_avatar_name;

if(!move_uploaded_file($artist_image['tmp_name'], $upload_path)){
   sendJson([
      'message'=>'مشکلی به وجود آمده'
   ],400);
}
$artist_id = db_insert('artists',[
   'full_name'=>$artist_fullname,
   'avatar'=>$upload_relative_dir . $new_avatar_name]);
if($artist_id){
   redirect(panel_url('artist.php'));
}