<?php
require "../../init.php";
$music_id = $_POST['music_id'];
$sql = "SELECT download_count from musics WHERE ID = '$music_id'";
$res = db_query($sql);
if($res && $res->num_rows){
   $download_count = mysqli_fetch_assoc($res);
   // print_r($download_count);exit;
   db_update('musics',[
   'download_count' => $download_count['download_count'] + 1
],"ID = $music_id");
sendJson([
   'message'=>'دانلود انجام شد',
   'download_count'=>$download_count['download_count']+1
],200);
}
sendJson(['message'=>'خطایی رخ داد'],500);

