<?php
require "../../init.php";
$comment_id = requestInput("comment_id","POST");
if(!$comment_id){
   sendJson(['message'=>'خطایی رخ داد'],400);
}
db_update('comments',[
   'status'=>'publish',
   'updated_at'=>date('Y-m-d H:i')
],[
   'ID'=>$comment_id
]);
sendJson(['message'=>'کامنت تایید شد']);