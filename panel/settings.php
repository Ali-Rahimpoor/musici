<?php 
require "./parts/header.php";

/**
 * @var string $user_role
 */

is_user_admin($user_role);

// پردازش ذخیره تنظیمات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $settingsToUpdate = [
        'site_name' => $_POST['site_name'] ?? '',
        'site_description' => $_POST['site_description'] ?? '',
        'site_status' => $_POST['site_status'] ?? 'active',
        'otp_duration' => intval($_POST['otp_duration'] ?? 120),
        'otp_length' => intval($_POST['otp_length'] ?? 4),
        'home_music_count' => intval($_POST['home_music_count'] ?? 12)
    ];
    
    // اعتبارسنجی
    $errors = [];
    if (empty($settingsToUpdate['site_name'])) {
        $errors[] = "نام سایت نمی‌تواند خالی باشد";
    }
    if ($settingsToUpdate['otp_duration'] < 30 || $settingsToUpdate['otp_duration'] > 600) {
        $errors[] = "مدت زمان کد تایید باید بین 30 تا 600 ثانیه باشد";
    }
    if ($settingsToUpdate['otp_length'] < 4 || $settingsToUpdate['otp_length'] > 8) {
        $errors[] = "تعداد ارقام کد تایید باید بین 4 تا 8 باشد";
    }
    if ($settingsToUpdate['home_music_count'] < 1 || $settingsToUpdate['home_music_count'] > 50) {
        $errors[] = "تعداد موزیک‌های صفحه اصلی باید بین 1 تا 50 باشد";
    }
    
    if (empty($errors)) {
        $siteSettings = new SiteSettings();
        if ($siteSettings->updateMultiple($settingsToUpdate)) {
            $success = "تنظیمات با موفقیت ذخیره شد";
            // بارگذاری مجدد تنظیمات
            $siteSettings = new SiteSettings();
        } else {
            $errors[] = "خطا در ذخیره تنظیمات";
        }
    }
}

// دریافت تنظیمات جاری
$currentSettings = $siteSettings->getAll();
?>
<!-- سایدبار -->
<?php require "./parts/sidebar.php" ?>    
<!-- محتوای اصلی --> 
<div class="page-content active" id="settings-page">
    <div class="settings-container">
        <h2>تنظیمات سایت</h2>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="settingsForm">
            <div class="settings-section">
                <h3>تنظیمات عمومی سایت</h3>
                <div class="setting-item">
                    <label for="site_name">نام سایت <span class="required">*</span></label>
                    <input type="text" class="form-input" id="site_name" name="site_name" 
                           value="<?php echo htmlspecialchars($currentSettings['site_name'] ?? 'نئوتک موزیک'); ?>" required>
                    <small>این نام در هدر سایت و title صفحه نمایش داده می‌شود</small>
                </div>
                
                <div class="setting-item">
                    <label for="site_description">توضیحات متا سایت</label>
                    <textarea class="form-textarea" id="site_description" name="site_description" rows="3"><?php 
                        echo htmlspecialchars($currentSettings['site_description'] ?? 'پلتفرم پخش آنلاین موزیک'); 
                    ?></textarea>
                    <small>این توضیحات در نتایج گوگل نمایش داده می‌شود (حدود 150-160 کاراکتر)</small>
                </div>
                
                <div class="setting-item">
                    <label for="site_status">وضعیت سایت</label>
                    <select class="form-input" id="site_status" name="site_status">
                        <option value="active" <?php echo ($currentSettings['site_status'] ?? 'active') == 'active' ? 'selected' : ''; ?>>فعال</option>
                        <option value="maintenance" <?php echo ($currentSettings['site_status'] ?? 'active') == 'maintenance' ? 'selected' : ''; ?>>در حال بروزرسانی</option>
                    </select>
                    <small>در حالت "در حال بروزرسانی"، کاربران غیرادمین صفحه مخصوص می‌بینند</small>
                </div>
            </div>
            
            <div class="settings-section">
                <h3>تنظیمات امنیتی - کد تایید تلفن</h3>
                <div class="setting-item">
                    <label for="otp_duration">مدت زمان اعتبار کد تایید (ثانیه)</label>
                    <input type="number" class="form-input" id="otp_duration" name="otp_duration" 
                           value="<?php echo htmlspecialchars($currentSettings['otp_duration'] ?? 120); ?>"
                           min="30" max="600" step="10" required>
                    <small>پیش‌فرض: 120 ثانیه (2 دقیقه)</small>
                </div>
                
                <div class="setting-item">
                    <label for="otp_length">تعداد ارقام کد تایید</label>
                    <input type="number" class="form-input" id="otp_length" name="otp_length" 
                           value="<?php echo htmlspecialchars($currentSettings['otp_length'] ?? 4); ?>"
                           min="4" max="8" step="1" required>
                    <small>پیش‌فرض: 4 رقم (امنیت: 6 رقم پیشنهاد می‌شود)</small>
                </div>
            </div>
            
            <div class="settings-section">
                <h3>تنظیمات نمایش محتوا</h3>
                <div class="setting-item">
                    <label for="home_music_count">تعداد موزیک‌های صفحه اصلی</label>
                    <input type="number" class="form-input" id="home_music_count" name="home_music_count" 
                           value="<?php echo htmlspecialchars($currentSettings['home_music_count'] ?? 12); ?>"
                           min="1" max="50" step="1" required>
                    <small>تعداد آهنگ‌هایی که در صفحه اصلی نمایش داده می‌شوند</small>
                </div>
            </div>
            
            <div class="settings-section">
                <h3>تنظیمات پیشرفته</h3>
                <!-- <div class="setting-item checkbox-item">
                    <label class="checkbox-custom-label">
                        <input type="checkbox" class="hidden-checkbox" id="debug_mode" name="debug_mode" 
                               <?php echo defined('SMS_DEBUG_PHONE') && SMS_DEBUG_PHONE ? 'checked' : ''; ?>>
                        <span class="custom-checkbox"></span>
                        حالت دیباگ SMS (ارسال کد به شماره مشخص)
                    </label>
                </div> -->
            </div>
            
            <div class="form-actions">
                <button type="submit" name="save_settings" class="btn-primary btn-save-settings">
                    ذخیره تنظیمات
                </button>
                <button type="button" class="btn-secondary btn-reset-default" onclick="resetToDefault()">
                </div>
        </form>
    </div>
</div>

<style>
.alert {
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.required {
    color: #dc3545;
}
/* 
.setting-item {
    margin-bottom: 20px;
}

.setting-item label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.setting-item small {
    display: block;
    margin-top: 5px;
    color: #6c757d;
    font-size: 12px;
}

.form-input, .form-textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.checkbox-item {
    display: flex;
    align-items: center;
}

.checkbox-custom-label {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.custom-checkbox {
    width: 20px;
    height: 20px;
    border: 2px solid #007bff;
    border-radius: 4px;
    margin-right: 10px;
    display: inline-block;
    position: relative;
}

.hidden-checkbox:checked + .custom-checkbox::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #007bff;
    font-size: 14px;
}

.hidden-checkbox {
    display: none;
}

.form-actions {
    margin-top: 30px;
    display: flex;
    gap: 15px;
}

.btn-primary, .btn-secondary {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.settings-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.settings-section h3 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
} */
</style>

<script>
function resetToDefault() {
    if (confirm('آیا مطمئن هستید؟ تمام تنظیمات به حالت پیش‌فرض بازمی‌گردد.')) {
        document.getElementById('site_name').value = 'نئوتک موزیک';
        document.getElementById('site_description').value = 'پلتفرم پخش آنلاین موزیک';
        document.getElementById('site_status').value = 'active';
        document.getElementById('otp_duration').value = '120';
        document.getElementById('otp_length').value = '4';
        document.getElementById('home_music_count').value = '12';
    }
}

// اعتبارسنجی فرم قبل از ارسال
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    const otpDuration = parseInt(document.getElementById('otp_duration').value);
    const otpLength = parseInt(document.getElementById('otp_length').value);
    const homeMusicCount = parseInt(document.getElementById('home_music_count').value);
    
    if (isNaN(otpDuration) || otpDuration < 30 || otpDuration > 600) {
        e.preventDefault();
        alert('مدت زمان کد تایید باید بین 30 تا 600 ثانیه باشد');
        return false;
    }
    
    if (isNaN(otpLength) || otpLength < 4 || otpLength > 8) {
        e.preventDefault();
        alert('تعداد ارقام کد تایید باید بین 4 تا 8 باشد');
        return false;
    }
    
    if (isNaN(homeMusicCount) || homeMusicCount < 1 || homeMusicCount > 50) {
        e.preventDefault();
        alert('تعداد موزیک‌های صفحه اصلی باید بین 1 تا 50 باشد');
        return false;
    }
});
</script>

<?php require "./parts/footer.php"; ?>