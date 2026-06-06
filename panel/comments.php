<?php
   require './parts/header.php';
?>
   <?php require "./parts/sidebar.php" ?>    
   <?php 
   is_user_admin($user_role);
      $comments = [];
      $sql = "SELECT * FROM comments WHERE status='pending'";
      $res = db_query($sql);
      if($res && $res->num_rows){
         $comments = mysqli_fetch_all($res,MYSQLI_ASSOC);
      }
   ?>
   <div class="page-content active" id='comment-page'>
   <div class="comments-grid">
      <?php if($comments): ?>
         <?php foreach($comments as $comment): 
            
              $author_id = $comment['user_id'];
              $author_res = db_query("SELECT name,avatar from users WHERE ID = '$author_id'");
               if($author_res && $author_res->num_rows){
                  $author = mysqli_fetch_assoc($author_res);
                  $comment['author_name'] = $author['name'];
                  $comment['author_avatar']= $author['avatar'];
               }
               ?>
            
            <div class="comment-item">
               <div class="author-container">
                  <span class="author-name"><?php echo $comment['author_name'] ?></span>
                  <img class="author-img" src="<?php echo !empty($comment['author_avatar']) ? $comment['author_avatar'] : site_url("public\img\default_avatar.jpg") ;?>" alt="">
               </div>
               <span class="comment-title"><?php echo $comment['comment']; ?></span>
               <div class="set-comment-status" data-comment-id="<?php echo $comment['ID']; ?>" data-user-id="0" data-music-id="0">
                  <!-- GO TO JS ! -->
                  <button class="publish-comment" id="publish-comment">تایید</button>
                  <button class="delete-comment" id='delete-comment'>عدم تایید</button>
               </div>
            </div>
         <?php endforeach; ?>
      <?php else: ?>
         <div>کامنت نداری</div>
      <?php endif; ?>
   </div>
</div>
   </div>

<?php require './parts/footer.php'; ?>