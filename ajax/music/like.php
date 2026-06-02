<?php
require "../../init.php";
$music_id = $_POST['music_id'];
$user_id = get_current_user_id();
$like_count = null;
   $like_count_res = db_query("SELECT like_count from musics WHERE ID = '$music_id'");      
   if($like_count_res && $like_count_res->num_rows){
      $like_count = mysqli_fetch_column($like_count_res);
   }
if($like_count === null){
   sendJson(['message'=>'خطایی رخ داده است'],500);
}
if(!$user_id){
   sendJson(['message'=>'کاربری یافت نشد'],400);
}
$is_music_fav_sql = "SELECT COUNT(*) from music_favorite WHERE music_id ='$music_id' AND user_id ='$user_id'";
// print_r($is_music_fav_sql);exit;
$res = db_query($is_music_fav_sql);
if($res && $res -> num_rows){
   $result = mysqli_fetch_column($res);
   $status= '';
   $message = 'خطایی رخ داده';
   if($result){
      // REMOVE FAV
      db_delete('music_favorite',[
         'music_id'=>$music_id,
         'user_id'=>$user_id
      ]);
      db_update('musics',[
         'like_count'=>$like_count-1,
      ],[
         'ID'=>$music_id
      ]);
      $status = 'inactive';
   }else{
      // ADD FAV
      // print_r('0');
      db_insert('music_favorite',[
         'music_id'=>$music_id,
         'user_id'=>$user_id,
      ]);
     
      db_update('musics',[
         'like_count'=>$like_count+1,
      ],[
         'ID'=>$music_id
      ]);
      $status = 'active';
   }

   if($status == "active"){
      $message = 'موزیک به لیست علاقه مندی اضافه شد';
      $new_count = $like_count +1;
   }
   if($status == 'inactive'){
      $message = 'موزیک از لیست علاقه مندی حذف شد';
      $new_count = $like_count -1;
   }
   sendJson(['message'=>$message,'status'=>$status,'new_count'=>$new_count]);

}



else{
   sendJson(['message'=>'خطایی رخ داد'],500);
}