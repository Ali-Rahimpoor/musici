<?php
require '../../init.php';

header('Content-Type: application/json');

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT role FROM users WHERE ID = '$user_id'";
    $res = db_query($sql);
    
    if($res && $res->num_rows) {
        $user = mysqli_fetch_assoc($res);
        echo json_encode(['role' => $user['role']]);
    } else {
        echo json_encode(['role' => 'user']);
    }
}
exit;
?>