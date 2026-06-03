<?php require "./parts/header.php";
/**
 * @var string $user_role
 */

is_user_admin($user_role);
    $res = db_query("SELECT * FROM artists");
    $artists='';
    if($res && $res->num_rows){
        $artists = mysqli_fetch_all($res,MYSQLI_ASSOC);
    }
?>
        <!-- سایدبار -->
        <?php require "./parts/sidebar.php" ?>    
        
            <div class="page-content active" id="add-category-page">
                <div class="form-container">
                    <h2>اضافه کردن دسته‌بندی جدید</h2>
                    <form method="POST"action='<?php echo site_url('ajax/artist/addArtist.php'); ?>' id="addArtistForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="">نام خواننده</label>
                            <input type="text" name='fullname' class="form-input" id="artistName" placeholder="مثال: علی رحیم پور">
                            <label for="">تصویر خواننده</label>
                            <input id='upload_artist_avatar' type="file" name='upload_artist_avatar'>
                        </div>
                        
                        <button type="submit" class="btn-primary">ایجاد</button>
                    </form>
                    
                    <div class="categories-list">
                        <h3>خواننده ها موجود</h3>
                        <div id="existingArtists">
                            <?php if(!$artists): ?>
                                <div>خواننده ای یافت نشد</div>

                                <?php else: ?>
                                    <?php foreach($artists as $cat): ?>
                                        <div class="category-tag"><?php echo $cat['full_name']; ?><button data-id='<?php echo $cat['ID']; ?>' class="delete-artist">✖</button>
                                      <img width="50" style="border-radius: 100px;" height="50" src="<?php echo site_url($cat['avatar']); ?>" alt=""> 
                                    </div>  
                                     
                                    <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
    <?php require "./parts/footer.php"; ?>