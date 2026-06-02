<?php
function db(){
   global $db;
   return $db;
}
function db_log($db_error){
    // ایجاد پوشه logs اگر وجود نداره
    $log_dir = __DIR__ . '/logs/';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0777, true);
    }
    
    // اطلاعات خطا و محل وقوع
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
    $file = isset($backtrace[0]['file']) ? basename($backtrace[0]['file']) : 'unknown';
    $line = isset($backtrace[0]['line']) ? $backtrace[0]['line'] : 'unknown';
    
    // فرمت لاگ کامل
    $log_entry = date('Y-m-d H:i:s') . ' | ' . 
                 $file . ':' . $line . ' | ' . 
                 $db_error . PHP_EOL;
    
    // ذخیره در فایل با تاریخ جداگانه
    $log_file = $log_dir . 'db-error-' . date('Y-m-d') . '.log';
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}
function clean_old_logs($log_dir, $days_to_keep) {
    $files = glob($log_dir . 'db-error-*.log');
    $now = time();
    
    foreach ($files as $file) {
        if (is_file($file)) {
            $file_age = ($now - filemtime($file)) / (60 * 60 * 24);
            if ($file_age > $days_to_keep) {
                unlink($file);
            }
        }
    }
}
function db_escape($string){
   return mysqli_real_escape_string(db(),trim($string));
}
function db_query($sql){
   $result = @mysqli_query(db(),$sql);
    if($result){
      return $result;
   }
   db_log(mysqli_error(db()));
   return false;
}
function db_insert($table,$data){
   $cols = array_keys ($data);
   $cols_sql = implode(', ',$cols);
   
   $vals = array_values($data);
   $new_data=[];
   foreach($vals as $val){
      if($val == NULL){
         $new_data[]= 'NULL';
      }else{
         $new_data[]= "'$val'";
      }
   }
   $vals_sql = implode(', ',$new_data);
   $sql = "INSERT INTO $table ( $cols_sql ) VALUES ( $vals_sql )";
   // print_r($sql);exit;
   $result = db_query($sql);
   if($result){
      return mysqli_insert_id(db());
   }
}
function db_update($table,$update_data,$where_data){
   $set_sql = '';
   foreach($update_data as $key => $val){
      if($val === null){
         $set_sql .= "$key = NULL, ";
      }else{
         $val = db_escape($val);
         $set_sql.="$key = '$val',";
      }
   }
   $set_sql = trim($set_sql,', ');

   if(is_array($where_data)){
      $where = ' 1 = 1 ';
      foreach ($where_data as $key => $val){
         if($val === null){
            $where .= " AND $key IS NULL";
         }else{
            $val = db_escape($val);
            $where .= " AND $key = '$val'";
         }
      }
   }elseif(is_string($where_data)){
      $where = $where_data;
   }
   $update_sql = "UPDATE $table SET $set_sql WHERE $where";
   $result = db_query($update_sql);
   if($result){
      return mysqli_affected_rows(db());
   }
}
function db_delete($table,$where_data){
   if(is_array($where_data)){
      $where = ' 1 = 1 ';
      foreach($where_data as $key => $val){
         if($val === null){
            $where .= " AND $key IS NULL";
         }else{
            $val = db_escape($val);
            $where .= " AND $key = '$val'";
         }
      }
   }elseif(is_string($where_data)){
      $where = $where_data;
   }
   $update_sql = "DELETE from $table WHERE $where";
   $result = db_query($update_sql);
   if($result){
      return mysqli_affected_rows(db());
   }
}
function get_record_by($table,$field,$field_val,$select='*'){
   // EDIT
   $sql = "SELECT $select from $table WHERE $field = '$field_val' LIMIT 1 ";
   return mysqli_fetch_assoc(db_query($sql));
}
