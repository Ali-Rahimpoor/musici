<?php
require '../../init.php';
header('Content-Type: application/json');

$sql = "SELECT ID, title FROM categories ORDER BY title ASC";
$res = db_query($sql);

$categories = [];
if($res && $res->num_rows) {
    while($row = mysqli_fetch_assoc($res)) {
        $categories[] = $row;
    }
}

echo json_encode(['success' => true, 'categories' => $categories]);
exit;
?>