<?php
   $comments =[];
   $replies=[];
   $sql = "SELECT * FROM view_comments WHERE music_id='$music_id' AND comment_status = 'publish' AND comment_parent_id = 0 ORDER BY comment_updated_at DESC";
   $res = db_query($sql);
   if($res && $res-> num_rows){
      $comments= mysqli_fetch_all($res,MYSQLI_ASSOC);
      $commentIds = array_column($comments, 'comment_id');
        if(!empty($commentIds)){
            $idsString = implode(',', $commentIds);
            // دریافت پاسخ‌ها
            $replySql = "SELECT comment_comment,music_id,user_id,user_avatar,comment_id,comment_parent_id,user_name,user_role,comment_updated_at FROM view_comments WHERE music_id='$music_id' AND comment_status = 'publish' AND comment_parent_id IN ($idsString) ORDER BY comment_updated_at DESC";
            $replyRes = db_query($replySql);
            if($replyRes && $replyRes->num_rows){
                  $allReplies = mysqli_fetch_all($replyRes, MYSQLI_ASSOC);
                  // گروه‌بندی پاسخ‌ها بر اساس parent_id
                  foreach($allReplies as $reply){
                     $replies[$reply['comment_parent_id']][] = $reply;
                  }
            }
         }
   }
?>
<div class="comments-section">
      <h2 class="section-title">💬 دیدگاه شنوندگان</h2>
      <?php if($is_login): ?>
      <form data-music-id=<?php echo $music_id; ?> data-user-id="<?php echo $userId ?>" class="comment-form" id='comment-form'>
         <textarea name='comment' placeholder="نظر یا پیشنهاد خود را با ما به اشتراک بگذارید..." rows="4"></textarea>
         <button type="submit" class="btn-submit-comment">ارسال نظر</button>
      </form>
      <?php else: ?>
         <div>
            <p>برای ثبت نظر باید وارد حساب کاربری خود بشید</p>
            <a href="<?php echo panel_url('login.php') ?>">ورود به حساب کاربری</a>
         </div>
      <?php endif; ?>
      <div class="comments-list">
         <?php if($comments): ?>
         <?php foreach($comments as $comment){
            include ABSPATH.'./parts/comment-item.php';
         }
         ?>
         <?php else: ?>
            <div>کامنتی نیست !</div>
            <div>تو اولین نفر باش !</div>
         <?php endif; ?>
      </div>
</div>