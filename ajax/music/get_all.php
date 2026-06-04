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
     <div class="music-card" data-music-id="<?php echo $music['music_id']; ?>">
        <div class="card-image">
            <img src="<?php echo isset($music['music_cover']) && $music['music_cover'] ? $music['music_cover'] : site_url('public/img/default_cover.jpg'); ?>" alt="کاور آهنگ">
            <div class="play-overlay">
                <button class="play-btn play-now-btn" data-music-url="<?php echo htmlspecialchars($music['music_url']); ?>">▶</button>
            </div>
        </div>
        <div class="card-body">
            <h3 class="music-title"><?php echo htmlspecialchars($music['music_title']); ?></h3>
            <div class="card-header">
                <?php if($is_admin): ?>
                <a style="color: #cccccc; text_decoration:none;" href="<?php echo panel_url('addMusic.php?action=edit&id='.$music['music_id']); ?>">ادیت موزیک</a>
                <?php endif; ?>                
                <span class="music-category"><?php echo isset($music['category_id']) ? $music['category_title'] : 'متفرقه'; ?></span>
                <span class="music-category"><?php echo isset($music['artist_id']) ? $music['artist_name'] : 'ناشناس'; ?></span>
            </div>
            <p class="music-description"><?php echo htmlspecialchars(substr($music['music_content'], 0, 100)) . (strlen($music['music_content']) > 100 ? '...' : ''); ?></p>
            <div class="music-actions">
                <?php
                 $music_id = $music['music_id'];
                 $user_id = get_current_user_id();
                 $is_login = false;
                 $is_liked = false;
                 if($user_id){
                    $is_login = true;
                 }
                 $sql="SELECT COUNT(*) from music_favorite WHERE music_id='$music_id' AND user_id='$user_id' ";
                 $res = db_query($sql);
                 if($res && $res->num_rows){
                    $is_liked= mysqli_fetch_column($res);                
                 }
                ?>
                <?php if($is_login): ?>
                    <button class="action-btn like-btn <?php echo $is_liked !=0 ? 'liked' : ''; ?> " data-music-id="<?php echo $music['music_id']; ?>">
                        <span class="like-icon">❤️</span>
                    </button>
                <?php else: ?>
                    <button class="need_login action-btn">
                        <span class="like-icon">❤️</span>
                    </button>
                <?php endif; ?>
                <a data-music-id='<?php echo $music['music_id']; ?>' download href="<?php echo htmlspecialchars($music['music_url']); ?>" class="download-btn action-btn">
                 ⬇️
                 دانلود
              </a>
                <button class="action-btn play-now-btn" data-music-url="<?php echo htmlspecialchars($music['music_url']); ?>">
                    <span>🎵</span>
                    <span>پخش</span>
                </button>
            </div>
            <div class="music-meta">
                <span>📅 <?php echo jdate('Y/m/d',strtotime($music['music_created_at'])); ?></span>
                <span>🎧 <span data-music-id='<?php echo $music['music_id']; ?>' class="download_count"><?php echo number_format($music['music_download_count']); ?></span> دانلود</span>
                <span>❤️ <span data-music-id='<?php echo $music['music_id']; ?>' class="like_count_display"><?php echo number_format($music['music_like_count'] ?? 0); ?></span> لایک</span>
            </div>
        </div>
    </div>
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