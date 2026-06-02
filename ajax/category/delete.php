<?php
require "../../init.php";
$id = intval($_POST['id']);

db_delete('categories',['ID'=>$id]);
sendJson(
   ['message'=>'دسته بندی حذف شد'], 200
);