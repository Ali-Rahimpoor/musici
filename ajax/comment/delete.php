<?php
require "../../init.php";
$comment_id = requestInput("comment_id","POST");
if(!$comment_id){
   sendJson(['message'=>'خطایی رخ داد'],400);
}
db_update('comments',[
   'status'=>'deleted',
   'updated_at'=>date('Y-m-d')
],[
   'ID'=>$comment_id
]);
sendJson(['message'=>'کامنت حذف شد']);