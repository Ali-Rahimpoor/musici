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
   $where .= " AND ( title LIKE '%$search%' OR content LIKE '%$search%' )";
}

// دسته بندی
if(isset($_GET['category']) && $_GET['category'] != 'all' && $_GET['category'] != ''){
   $category = db_escape($_GET['category']);
   $where .= " AND ID IN ( SELECT music_id FROM music_category WHERE category_id = '$category' )";
}

// مرتب سازی
$orderBy = "created_at DESC"; // پیش فرض
if(isset($_GET['orderby'])){
    switch($_GET['orderby']){
        case 'newest':
            $orderBy = "created_at DESC";
            break;
        case 'popular':
            $orderBy = "like_count DESC";
            break;
        case 'most_download':
            $orderBy = "download_count DESC";
            break;
        default:
            $orderBy = "created_at DESC";
    }
}
$page= isset($_GET['page']) ? ($_GET['page']) : 1;
if($page == 'next'){
    
}
$per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : get_setting('home_music_count',4);
$offset= ($page-1) * $per_page;

$musicSql = "SELECT * FROM musics WHERE $where ORDER BY $orderBy LIMIT $per_page OFFSET $offset ";

$res = db_query($musicSql);
$musics = [];
if($res && $res->num_rows){
    while($music = mysqli_fetch_assoc($res)){
            $music_idd = $music['ID'];
            $music['cat'] = false;
            $sql = "SELECT * from categories WHERE ID IN (SELECT category_id FROM music_category WHERE music_id = '$music_idd') ORDER BY music_count DESC LIMIT 1";
            $cat_query = db_query($sql);
            if($cat_query && $cat_query -> num_rows){
                $music['cat'] = mysqli_fetch_assoc($cat_query);
            }
            $musics[]=$music;
    }
}

// PAGINATE



$total_query = db_query("SELECT COUNT(*) FROM musics WHERE $where");

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
     <div class="music-card" data-music-id="<?php echo $music['ID']; ?>">
        <div class="card-image">
            <img src="<?php echo isset($music['cover']) && $music['cover'] ? $music['cover'] : './public/img/default-cover.jpg'; ?>" alt="کاور آهنگ">
            <div class="play-overlay">
                <button class="play-btn play-now-btn" data-music-url="<?php echo htmlspecialchars($music['music_url']); ?>">▶</button>
            </div>
        </div>
        <div class="card-body">
            <div class="card-header">
                <?php if($is_admin): ?>
                <a style="color: #cccccc; text_decoration:none;" href="./panel/addmusic.php?action=edit&id=<?php echo $music
                ['ID'] ?>">ادیت موزیک</a>
                <?php endif; ?>
                <h3 class="music-title"><?php echo htmlspecialchars($music['title']); ?></h3>
                <span class="music-category"><?php echo isset($music['cat']['title']) ? htmlspecialchars($music['cat']['title']) : 'متفرقه'; ?></span>
            </div>
            <p class="music-description"><?php echo htmlspecialchars(substr($music['content'], 0, 100)) . (strlen($music['content']) > 100 ? '...' : ''); ?></p>
            <div class="music-actions">
                <?php
                 $music_id = $music['ID'];
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
                    <button class="action-btn like-btn <?php echo $is_liked !=0 ? 'liked' : ''; ?> " data-music-id="<?php echo $music['ID']; ?>">
                        <span class="like-icon">❤️</span>
                    </button>
                <?php else: ?>
                    <button class="need_login action-btn">
                        <span class="like-icon">❤️</span>
                    </button>
                <?php endif; ?>
                <a data-music-id='<?php echo $music['ID']; ?>' download href="<?php echo htmlspecialchars($music['music_url']); ?>" class="download-btn action-btn">
                 ⬇️
                 دانلود
              </a>
                <button class="action-btn play-now-btn" data-music-url="<?php echo htmlspecialchars($music['music_url']); ?>">
                    <span>🎵</span>
                    <span>پخش</span>
                </button>
            </div>
            <div class="music-meta">
                <span>📅 <?php echo jdate('Y/m/d',strtotime($music['created_at'])); ?></span>
                <span>🎧 <span data-music-id='<?php echo $music['ID']; ?>' class="download_count"><?php echo number_format($music['download_count']); ?></span> دانلود</span>
                <span>❤️ <span data-music-id='<?php echo $music['ID']; ?>' class="like_count_display"><?php echo number_format($music['like_count'] ?? 0); ?></span> لایک</span>
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