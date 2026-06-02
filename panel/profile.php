<?php
 require "./parts/header.php"; 
?>
        <!-- سایدبار -->
        <?php require "./parts/sidebar.php" ?>    
        <!-- محتوای اصلی --> 
            <div class="page-content active" id="profile-page">
                <div class="profile-container">
                    <div class="profile-header">
                        <div class="profile-avatar-large">
                            <img id='user_avatar' src="<?php echo $user_cover ?>" alt="avatar">
                            <label for='avatar' class="change-avatar-btn">📷 تغییر عکس</label>
                            <input type="file" id='avatar' accept="image/jpeg, image/png, image/webp" name='avatar' style="display: none">
                        </div>
                        <div class="profile-info">
                            <h2 id="profileFullName"><?php echo $user_name ;?></h2>
                            <p class="profile-role" id="profileRole"><?php echo $user_role ?></p>
                        </div>
                    </div>
                    
                    <div id="cropper-wrapper">
                        <div class="cropper-box">
                            <div class="croppie">

                            </div>
                            <div class="cropper-footer">
                                <button href="#" class="btn btn-primary crop-croppie">برش و آپلود</button>
                                <input type="hidden" value="<?php echo $user_id; ?>" class="user_edit_id">
                                <button class="btn btn-delete close-croppie-modal">انصراف</button>
                            </div>
                        </div>
                    </div>
                    
                    <form class="profile-form" id="profileForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label>نام و نام خانوادگی</label>
                                <input type="text" class="form-input" id="firstName" placeholder="نام">
                            </div>
                        </div>
                        <div class="form-row">
                        <div class="form-group">
                            <label>شماره تلفن</label>
                            <input type="tel" class="form-input" id="phone" placeholder="۰۹۱۲ XXX XXXX">
                        </div>
                        </div>
                        <button type="submit" class="btn-primary btn-save-profile">ذخیره تغییرات</button>
                    </form>
                    
                    <div class="change-password-section">
                        <h3>تغییر رمز عبور</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>رمز عبور فعلی</label>
                                <input type="password" class="form-input" id="currentPassword" placeholder="••••••••">
                            </div>
                            <div class="form-group">
                                <label>رمز عبور جدید</label>
                                <input type="password" class="form-input" id="newPassword" placeholder="••••••••">
                            </div>
                            <div class="form-group">
                                <label>تکرار رمز جدید</label>
                                <input type="password" class="form-input" id="confirmPassword" placeholder="••••••••">
                            </div>
                        </div>
                        <button class="btn-secondary btn-change-password">تغییر رمز عبور</button>
                    </div>
                </div>
            </div>
    <?php require "./parts/footer.php"; ?>