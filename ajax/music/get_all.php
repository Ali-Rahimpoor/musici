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
$where = "1=1";
$params = [];

// جستجو
if(isset($_GET['search']) && $_GET['search']){
   $search = db_escape($_GET['search']);
   $where .= " AND ( CONCAT(music_title ,' ' , music_content , ' ' ,artist_name) LIKE '%$search%' )";
}

// دسته بندی
if(isset($_GET['category']) && $_GET['category'] != 'all' && $_GET['category'] != ''){
   $category = db_escape($_GET['category']);
   $where .= " AND category_id = '$category'  ";
}
if(isset($_GET['artist']) && $_GET['artist'] != 'all' && $_GET['artist'] != ''){
   $artist = db_escape($_GET['artist']);
   $where .= " AND artist_id = '$artist'  ";
}



// مرتب سازی
$orderBy = "created_at DESC"; // پیش فرض
if(isset($_GET['orderby'])){
    switch($_GET['orderby']){
        case 'newest':
            $orderBy = "music_created_at DESC";
            break;
        case 'popular':
            $orderBy = "music_like_count DESC";
            break;
        case 'most_download':
            $orderBy = "music_download_count DESC";
            break;
        default:
            $orderBy = "music_created_at DESC";
    }
}
$page= isset($_GET['page']) ? ($_GET['page']) : 1;
if($page == 'next'){
    
}
$per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : get_setting('home_music_count',4);
$offset= ($page-1) * $per_page;

$musicSql = "SELECT * FROM view_musics WHERE $where ORDER BY $orderBy LIMIT $per_page OFFSET $offset ";
// print_r($musicSql);exit;

$res = db_query($musicSql);
$musics = [];
if($res && $res->num_rows){
    $musics = mysqli_fetch_all($res,MYSQLI_ASSOC);
    // print_r($musics);exit;
}

// PAGINATE
$total_query = db_query("SELECT COUNT(*) FROM view_musics WHERE $where");

$total_results=0;
if($total_query && $total_query->num_rows){
    $result = mysqli_fetch_array($total_query);
    $total_results = (int) $result[0];
}
$page_count = ceil($total_results/$per_page);


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
// ob_start();
$pagination='';
for($i=1; $i <= $page_count; $i++){
   
   if($i ==1){
    //   if($page > 1){
    //      $pagination .="<a href='#' class='page-btn prev'>قبلی</a>";
    //   }else{
    //      $pagination.="<a href='#' style='opacity:0.3' class='page-btn prev'>قبلی</a>";
    //   }
   }
   if(abs($page - $i) > 2){
      continue;
   }
   if($i == $page){
      $pagination.= "<button href='#' class='page-btn active'>$i</button>";
   }else{
       $pagination.= "<button href='#' class='page-btn '>$i</button>";
   }
//    if($i == $page_count && $page != $page_count){
//       $pagination .="<a href='#' class='page-btn next'>بعدی</a>";
//    }
}
// $paginate_html=ob_get_clean();
// برگرداندن داده به صورت JSON
echo json_encode([
    'success' => true,
    'summary' => $html,
    'count' => $total_results,
    'pagination'=>$pagination
]);
?>