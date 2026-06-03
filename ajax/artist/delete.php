<?php
require "../../init.php";
$id = intval($_POST['id']);

db_delete('artists',['ID'=>$id]);
sendJson(
   ['message'=>'خواننده حذف شد'], 200
);