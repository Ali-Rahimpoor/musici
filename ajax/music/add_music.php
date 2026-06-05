<?php 
require "../../init.php";
$music_id = isset($_POST['music_id']) ? $_POST['music_id'] : 0;
$is_edit = $music_id > 0;
$music_upload = true;
$image_upload = true;
if($is_edit){
$music_upload = false;
$image_upload = false;
}
// print_r($is_edit);exit;
$title = isset($_POST['title']) ? db_escape(trim($_POST['title'])) : false;
$content = isset($_POST['content']) ? db_escape(trim($_POST['content'])) : false;


$status = isset($_POST['status']) ? db_escape(trim($_POST['status'])) : 'publish';


$duration = isset($_POST['duration'])? intval($_POST['duration']) : 0;
$created_at = isset($_POST['created_at']) ? $_POST['created_at'] : '';
// print_r($_POST);exit;

 $music_url = isset($_POST['music']) ? $_POST['music'] : '';
 if(!$music_url){
   $music_upload=true;
 }
 $cover_url = isset($_POST['image']) ? $_POST['image'] : '';
 if(!$cover_url){
   $image_upload=true;
 }
if($music_upload){
   $music = $_FILES['music'];
   $upload_relative_dir = 'uploads/musics/'.date('Y/m').'/';
   $upload_dir = ABSPATH  . $upload_relative_dir;
   if(!file_exists($upload_dir)){
      mkdir($upload_dir,0777,true);
   }
   $new_music_name = generate_random_string(10).'.mp3';
   $upload_path = $upload_dir. $new_music_name;
   if(! move_uploaded_file($music['tmp_name'],$upload_path)){
      sendJson([
         'message'=>'مشکلی به وجود آمده'
      ],400);
   }
   $music_url=site_url($upload_relative_dir.$new_music_name);
   if(!$music_url ){
      sendJson([
         'message'=>'موزیک آپلود نشده'
      ],400);
   }
}
if($image_upload){
   $cover = $_FILES['image'];
   $upload_relative_dir = 'uploads/images/'.date('Y/m').'/';
   $upload_dir = ABSPATH  . $upload_relative_dir;
   if(!file_exists($upload_dir)){
      mkdir($upload_dir,0777,true);
   }
   $new_cover_name = generate_random_string(1).$cover['name'];
   $upload_path = $upload_dir. $new_cover_name;
   if(! move_uploaded_file($cover['tmp_name'],$upload_path)){
      sendJson([
         'message'=>'مشکلی به وجود آمده'
      ],400);
   }
   $cover_url=site_url($upload_relative_dir.$new_cover_name);
}

$music_data =[
   'title'=>$title,
   'content'=>autosave_p($content),
   'cover'=>$cover_url,
   'status'=>$status,
   'music_url'=>$music_url,
   'created_at'=>$created_at
];
if($is_edit){
   $updated = db_update('musics',$music_data,['ID'=>$music_id]);
}else{
   // FIX HERE
   $music_id = db_insert('musics',$music_data);
   if(!$music_id){
      sendJson([
         'message'=>'مشکلی به وجود امد'
      ],400);
   }
}

 db_delete('music_category',"music_id = $music_id");
if(isset($_POST['category']) ){
   $cat_id = $_POST['category'];
         db_insert('music_category',[
            'music_id'=>$music_id,
            'category_id'=>$cat_id]);
}
sync_cat_music_count();

db_delete('music_artist',"music_id = $music_id");
if(isset($_POST['artist'])){
   $artist_id = $_POST['artist'];
   db_insert('music_artist',[
      'music_id'=>$music_id,
      'artist_id'=>$artist_id
   ]);
}
sync_artist_music_count();
// $href = '';
// if(!$is_edit){
//    $href = panel_url('music.php?action=edit&id=' . $music_id);
// }
sendJson([
   'message'=>$is_edit ?'موزیک تغییر کرد':'موزیک ثبت شد',
   'music_id'=>$music_id
]);