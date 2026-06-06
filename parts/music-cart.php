<?php
/**
* @var array $music
*/
?>
<div class="music-card" data-music-id="<?php echo $music['music_id']; ?>">
   <div class="card-image">
      <img src="<?php echo isset($music['music_cover']) && $music['music_cover'] ? $music['music_cover'] : site_url('public/img/default_cover.jpg'); ?>" alt="کاور آهنگ">
      <div class="play-overlay">
            <a href='<?php echo site_url('music/'.$music['music_slug']); ?>' class="play-btn">▶</a>
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