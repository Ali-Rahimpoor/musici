<?php
require '../../init.php';

$user_id = get_current_user_id();
$comment = autosave_p((requestInput('comment','POST')));
$music_id = requestInput('music_id',"POST");
$status = 'pending';
$parent_id = requestInput('parent_id',"POST");

if(check_user_admin($user_id)){
   $status = 'publish';
}
if(!$user_id || !$comment || !$music_id){
   sendJson([
      'message'=>'خطا در ثبت اطلاعات'
   ]);
}
$data =[
   'user_id'=>$user_id,
   'music_id'=>$music_id,
   'comment'=>$comment,
   'status'=>$status
];
if($parent_id){
   $data['parent_id'] = $parent_id;
}

db_insert('comments',$data);
sendJson([
   'message'=>'بعد از تایید توسط مدیر منتشر خواهد شد',   
   'comment'=>$comment
]);