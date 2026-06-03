<?php
require '../../init.php';
header('Content-Type: application/json');

$sql = "SELECT ID, full_name FROM artists ORDER BY full_name ASC";
$res = db_query($sql);

$artists = [];
if($res && $res->num_rows) {
    while($row = mysqli_fetch_assoc($res)) {
        $artists[] = $row;
    }
}

echo json_encode(['success' => true, 'artists' => $artists]);
exit;
?>