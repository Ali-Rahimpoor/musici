<?php
require "../../init.php";
$userId = get_current_user_id();
$is_admin = false;
if($userId){
 $res = db_query("SELECT role from users WHERE ID = '$userId'");
 if($res && $res -> num_rows){
    $userRole = mysqli_fetch_column($res);
    if($userRole == 'admin'){
        $is_admin=true;
    }
 }
}

header('Content-Type: application/json');
// print_r($_GET);exit;
$artist_id = requestInput('artist_id');
$music_id = requestInput('music_id');
$musicSql = "SELECT * FROM view_musics WHERE artist_id = '$artist_id' AND music_id != '$music_id'  ";
// print_r($musicSql);exit;

$res = db_query($musicSql);
$musics = [];
if($res && $res->num_rows){
    $musics = mysqli_fetch_all($res,MYSQLI_ASSOC);
    // print_r($musics);exit;
}

// PAGINATE
// شروع بافر برای گرفتن خروجی HTML
ob_start();
?>

<?php if($musics): ?>
    <?php foreach($musics as $music): ?>
        <?php include ABSPATH. "parts/music-cart.php"; ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="empty-message">
        🎵 هیچ آهنگی با این مشخصات پیدا نشد!
        <br>
        <small>لطفا فیلترهای دیگری را امتحان کنید.</small>
    </div>
<?php endif; ?>

<?php
$html = ob_get_clean();

echo json_encode([
    'success' => true,
    'summary' => $html,
]);
?>