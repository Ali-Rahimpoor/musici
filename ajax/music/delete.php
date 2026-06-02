<?php
require '../../init.php';
$music_id = isset($_POST['music_id']) ? $_POST['music_id'] : 0;
if(!$music_id){
sendJson(['message'=>'خطا در حذف موزیک '],400);
}
$res = db_delete('musics',['ID'=>$music_id]);
if(!$res){
   sendJson(['message'=>'خطا در حذف موزیک '],500);
}
sendJson(['message'=>'موزیک حذف شد'],200);
