<?php
require '../../init.php';
do_logout();
header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit;
?>