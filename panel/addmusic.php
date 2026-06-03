<?php
require "./parts/header.php"; 
/**
 * @var string $user_role
 */

is_user_admin($user_role);
$title = '';
$content = '';
$music_cover='';
$music_url = '';
$music_id=0;
$category='';
$artist='';
$music_year='';
if(isset($_GET['action']) && $_GET['action']=='edit' && isset($_GET['id']) ){
    $music_id = $_GET['id'];
    $music = get_record_by('musics','ID',$music_id);
    // print_r($music);exit;
    if($music){
        $title = $music['title'];
        $content = $music['content'];
        $music_cover = $music['cover'];
        $music_url=$music['music_url'];
        $music_year = $music['created_at'];

        $cat_sql = "SELECT category_id FROM music_category WHERE music_id = $music_id";
        $cat_result = db_query($cat_sql);
        if($cat_result && $cat_result->num_rows){
            $category = (int)mysqli_fetch_column($cat_result);
            // print_r($category);exit;
        }
        $artist_sql = "SELECT artist_id FROM music_artist WHERE music_id = $music_id";
        $artist_res = db_query($artist_sql);
        if($artist_res && $artist_res->num_rows){
            $artist = (int)mysqli_fetch_column($artist_res);
        }
    }else{
        $music_id=0;
    }
}
?>
<!-- سایدبار -->
<?php require "./parts/sidebar.php" ?>    
<!-- محتوای اصلی -->          
    <!-- ==================== صفحه 5: اضافه کردن موزیک (فقط ادمین) ==================== -->
<div class="page-content active" id="add-music-page">
    <div class="form-container">
        <?php if( isset($_GET['id'])&&$_GET['id']): ?>
        <button data-id="<?php echo $music_id; ?>" id='btn_delete_music' class="btn-primary">حذف این موزیک</button>
        <?php endif; ?>
        <h2 class="page-title">
            <span class="title-icon">🎵</span>
            <?php echo isset($_GET['id']) ? "ادیت موزیک" : "اضافه کردن موزیک"; ?>
        </h2>
        
        <form id="addMusicForm" enctype="multipart/form-data">
            <input type="hidden" id='music_id' value="<?php echo !empty($music_id) ? $music_id :0 ?>">
            <input type="hidden" id='hidden_music_url' name='hidden_music_url' value="<?php echo $music_url ?>">
            <input type="hidden" id='hidden_music_cover' name='hidden_music_cover' value="<?php echo $music_cover; ?>">
            <input type="hidden" id='hidden_music_category' value="<?php echo $category ?>">
            <input type="hidden" id='hidden_music_artist' value="<?php echo $artist ?>">
            <!-- نام آهنگ -->
            <div class="form-group">
                <label class="form-label">
                    <span class="label-icon">🎤</span>
                    نام آهنگ
                </label>
                <input value="<?php echo $title ?>" type="text" class="form-input" id="musicName" placeholder="نام آهنگ" name='title' >
            </div>
            
            <!-- توضیحات -->
            <div class="form-group">
                <label class="form-label">
                    <span class="label-icon">📝</span>
                    توضیحات
                </label>
                <textarea name="content" class="form-textarea" id="musicDescription" placeholder="توضیحات درباره آهنگ..." rows="4"><?php echo $content; ?></textarea>
            </div>
                 <div class="form-group">
                    <label class="form-label">
                        <span class="label-icon">🎵</span>
                        خواننده
                    </label>                    
                    <select name='artist' class="form-input" id="musicArtist" >
                      
                    </select>        
                </div>
            <!-- دسته‌بندی و سال -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        <span class="label-icon">📁</span>
                        دسته‌بندی
                    </label>                    
                    <select name='category' class="form-input" id="musicCategory" >
                      
                    </select>        
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <span class="label-icon">📅</span>
                        سال انتشار
                    </label>
                    <input value="<?php echo $music_year ?>" type="text" name='created_at' class="form-input" id="musicYear" placeholder="سال انتشار">
                   

                </div>
            </div>
            
            <!-- آپلودر فایل موزیک -->
            <div class="form-group">
                <label class="form-label">
                    <span class="label-icon">🎵</span>
                    فایل موزیک (MP3)
                </label>
                <label for='musicFile' class="upload-area" id="musicUploadArea">
                    <input value="" type="file" name='music' id="musicFile" accept="audio/mp3,audio/mpeg" style="display:none">
                    <div class="upload-content">
                        <span class="upload-icon">🎵</span>
                        <p class="upload-text">برای آپلود فایل موزیک کلیک یا بکشید اینجا</p>
                        <p class="upload-hint">MP3 - حداکثر 20 مگابایت</p>
                    </div>
                    <div class="upload-preview hidden" id="musicPreview">
                        <span class="preview-icon">🎵</span>
                        <span class="preview-name" id="musicFileName"></span>
                        <button type="button" class="remove-file" onclick="removeMusicFile()">✖</button>
                    </div>
                </label>
                <div class="progress-bar hidden" id="musicProgress">
                    <div class="progress-fill"></div>
                    <span class="progress-text">۰%</span>
                </div>
            </div>
            
            <!-- آپلودر تصویر کاور -->
            <div class="form-group">
                <label class="form-label">
                    <span class="label-icon">🖼️</span>
                    عکس کاور
                </label>
                <label for='imageFile' class="upload-area" id="imageUploadArea">
                    <input value="" type="file" id="imageFile" accept="image/jpeg,image/png,image/jpg" style="display:none">
                    <div class="upload-content">
                        <span class="upload-icon">🖼️</span>
                        <p class="upload-text">برای آپلود عکس کاور کلیک یا بکشید اینجا</p>
                        <p class="upload-hint">JPG, PNG - حداکثر 20 مگابایت - ۵۰۰x۵۰۰ پیکسل</p>
                    </div>
                    <div class="upload-preview hidden" id="imagePreview">
                        <img id="previewImg" src="" alt="preview">
                        <button type="button" class="remove-file" onclick="removeImageFile()">✖</button>
                    </div>
                </label>
                <div class="progress-bar hidden" id="imageProgress">
                    <div class="progress-fill"></div>
                    <span class="progress-text">۰%</span>
                </div>
            </div>
            
            <button type="submit" class="btn-submit" id="submitMusicBtn">
                <span class="btn-icon">➕</span>
                اضافه کردن موزیک
            </button>
        </form>
    </div>
</div>
   
    <?php require "./parts/footer.php"; ?>