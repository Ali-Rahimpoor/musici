<div class="reply-item">
      <div class="comment-user-answer">
      <div style="gap: 12px; display:flex; align-items:center;">
         <span><img width="25" height="25" src="<?php echo htmlspecialchars( !empty($reply['user_avatar']) ? $reply['user_avatar'] : site_url('public/img/default_avatar.jpg')); ?>" alt=""></span>
         <span class="user-name"><?php echo htmlspecialchars($reply['user_name']); ?></span>
         <?php if($reply['user_role'] == 'admin'): ?>
            <span class="admin-badge">مدیر</span>
         <?php endif; ?>
      </div>
      <div style="gap: 12px; display:flex; align-items:center;">
         <span class="comment-date"><?php echo jdate('Y/m/d H:i', strtotime($reply['comment_updated_at'])); ?></span>
      </div>
   </div>
      <p class="comment-text"><?php echo autop(htmlspecialchars($reply['comment_comment'])); ?></p>
</div>