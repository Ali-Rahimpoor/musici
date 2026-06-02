<?php
function sendJson($data,$status=200){
  header('Content-Type: application/json; charset=utf-8'); 
   http_response_code($status);
   echo json_encode($data);
   exit;
}
function redirect($url){
   header("Location:$url");
   exit;
}
function generate_random_string($len=6){
   $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmopqrstuvwxyz';
   $result ='';
   for ($i=0; $i <= $len ; $i++) { 
         $random = rand(0,strlen($chars) -1);
         $result .=  $chars[$random];
   }
   return $result;
}
function dateTime(){
   return date('Y-m-d H:i:s');
}
function site_url($path=''){
   return SITE_URL . $path;
}
function panel_url ($path=''){
   return SITE_URL . "panel/" .$path;
}
function sync_cat_music_count(){
   db_update('categories',['music_count'=>0],"1=1");
   $sql = "SELECT category_id AS ID,COUNT(*) AS total FROM music_category GROUP BY category_id";
   $res = db_query($sql);
   if($res && $res->num_rows){
      $result = mysqli_fetch_all($res,MYSQLI_ASSOC);
      foreach($result as $cat){
         db_update('categories',[
            'music_count'=>$cat['total']
         ],['ID'=>$cat['ID']]);
      }
   }
}