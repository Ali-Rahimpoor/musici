<?php require "./parts/header.php";
$fav_musics=[];
$user_id = get_current_user_id();
$music_ids_sql = "SELECT music_id from music_favorite WHERE user_id = '$user_id'";
$res = db_query($music_ids_sql);
$music_ids=[];
if($res && $res->num_rows){
    while($row = mysqli_fetch_column($res)){
        $music_ids[]=$row;
    }
}
if(!empty($music_ids)){
    $music_ids_string = implode(',', $music_ids);
    $music_sql = "SELECT ID,title,music_url,content FROM musics WHERE ID IN ($music_ids_string)";
    $res=db_query($music_sql);
    if($res && $res->num_rows){
        $fav_musics = mysqli_fetch_all($res,MYSQLI_ASSOC);
        // print_r($fav_musics);
    }
}
?>
        <!-- سایدبار -->
        <?php require "./parts/sidebar.php" ?>                
            <!-- ==================== صفحه 3: علاقه‌مندی‌ها ==================== -->
            <div class="page-content active" id="favorites-page">
                <div class="favorites-header">
                    <h2>علاقه‌مندی‌های من</h2>
                    <button class="btn-outline" id="clearFavorites">پاک کردن همه</button>
                </div>
                <div class="favorites-grid" id="favoritesList">
                    <?php if(!empty($music_ids)): ?>
                    <?php foreach($fav_musics as $music): ?>
                        <div class="favorite-card music-card">
                            <div class="favorite-img">🎵</div>
                            <div class="favorite-info">
                                <h4 class="music-title"><?php echo $music['title'] ?></h4>
                                <p><?php echo $music['content'] ?></p>
                                <button data-music-url='<?php echo $music['music_url'] ?>' class="btn-primary play-now-btn">▶ پخش</button>
                                <button data-music-id="<?php echo $music['ID']; ?>" class="btn-primary btn-remove-fav">🗑 حذف</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <div>شما علاقه مندی ندارید !</div>
                    <?php endif; ?>
                </div>
            </div>            
        </main>
    <?php require "./parts/footer.php"; ?>