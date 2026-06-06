<?php 
/**
 * @var array $comment
 */
?>
<div class="comment-container">
<div class="comment-item">
   
   <div class="comment-user">
         <div style="gap: 12px; display:flex; align-items:center;">
         <span class=""><img width="25" height="25" src="<?php echo !empty($comment['user_avatar']) ? $comment['user_avatar'] : site_url('public/img/default_avatar.jpg'); ?>" alt=""></span>
         <span class="user-name"><?php echo $comment['user_name']; ?></span>
         </div>
         <div style="gap: 12px; display:flex; align-items:center;">
         <span class="comment-date"><?php echo jdate('Y/m/d H:i',strtotime($comment['comment_updated_at'] ));?></span>
         <button class="reply-btn">پاسخ</button>
         </div>
   </div>
   <p class="comment-text">
      <?php echo autop($comment['comment_comment']); ?>
   </p>

<form class="reply-form-container" data-user-id="<?php echo $comment['user_id'] ?>" data-music-id="<?php echo $comment['music_id'] ?>" data-parent-id="<?php echo $comment['comment_id']; ?>">
      <textarea class="reply-textarea" placeholder="پاسخ خود را بنویسید..." rows="2"></textarea>
      <div class="reply-actions">
         <button class="btn-cancel-reply">لغو</button>
         <button type="submit" class="btn-submit-reply">ارسال</button>
      </div>
   </form>
</div>
   <!-- ANSWER COMMENT -->
 <?php if(isset($replies[$comment['comment_id']]) && !empty($replies[$comment['comment_id']])): ?>
      <div class="replies-list">
         <?php foreach($replies[$comment['comment_id']] as $reply): ?>
            <?php include ABSPATH . "parts/comment-answer-item.php"; ?>
         <?php endforeach; ?>
      </div>
<?php endif; ?>
</div>