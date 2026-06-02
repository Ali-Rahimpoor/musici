<?php
require "../../init.php";
$title = isset($_POST['title']) ? db_escape($_POST['title']) : "";
$parent= isset($_POST['parent']) ? db_escape($_POST['parent']) : "";

$checkSql = "SELECT * FROM categories WHERE title = '$title'";
$checkRes = db_query($checkSql);
if($checkRes && $checkRes -> num_rows){
   sendJson([
      'message'=>'این دسته بندی وجود دارد'
   ],400);
}
$catId = db_insert('categories',[
   'title'=>$title,
   'parent'=>$parent
]);

sendJson([
   'message'=>'دسته بندی با موفقیت اضافه شد',
   'html'=>"  <div class='category-tag'>$title<button data-id='$catId' class='delete-cat'>✖</button></div> "
],201);