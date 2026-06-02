<?php
 require "./init.php";
 $userId = get_current_user_id();
 $user='';
 if($userId){
    $user = get_user_by_id($userId);
 }
// print_r($user);exit;
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>موزیکی | پخش آنلاین آهنگ</title>
    <link rel="stylesheet" href="./public/style.css">
    <link rel="stylesheet" href="./public/player.css">
</head>
<body>

<div class="container">
    <!-- هدر با بخش کاربری -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <span class="logo-icon">🎵</span>
                <span class="logo-text">موسیقی‌ما</span>
            </div>
                <!-- Menu -->
            <!-- <nav class="main-nav"></nav> -->
            <div class="user-section">
                <!-- حالت 1: کاربر وارد نشده - پیش‌فرض -->
                 <?php if(!$user): ?>
                <div class="user-guest">
                    <a href="./panel/login.php#login" class="btn-login">ورود</a>
                    <a href="./panel/login.php#register" class="btn-register">ثبت‌نام</a>
                </div>
                
                <!-- حالت 2: کاربر وارد شده (برای نمایش در سایت واقعی با کامنت خارج کنید) -->
                <?php else: ?>
                <div class="user-loggedin">
                    <span class="user-welcome">خوش آمدید، <?php echo $user['name'] ?></span>
                    <a href="./panel" class="btn-dashboard">داشبورد</a>
                    <div class="user-avatar">
                        <img src="<?php echo isset($user['avatar']) ? $user['avatar'] : './public/img/default_avatar.jpg' ?>" alt="">
                    </div>
                </div>
               <?php endif; ?>
            </div>
        </div>
    </header>
    <!-- بخش فیلترها -->
    <form action="" class="filters-section">
        
        <input type="hidden" id="page_input" value="1" name='page'>
         <div class="filter-group">
            <label>تعداد موزیک در صفحه</label>
            <select name="per_page" id="per_page">
                <option value="4">ردیف 4 تایی</option>
                <option value="2">ردیف 2 تایی</option>
                <option value="8">ردیف 8 تایی</option>
                <option value="12">ردیف 12 تایی</option>
            </select>
        </div>
        <div class="filter-group">
            <label>🔍 جستجوی آهنگ یا خواننده</label>
            <input name='search' type="text" placeholder="مثال: جدایی, شادمهر ...">
        </div>
        
        <div class="filter-group">
            <label>🎸 دسته بندی</label>
            <select name='category' id='categories'>
                <option value="all">همه دسته‌ها</option>
                
            </select>
        </div>
        
        <div class="filter-group">
            <label>🎚️ مرتب سازی بر اساس</label>
            <select name='orderby'>
                <option value="newest">جدیدترین</option>
                <option value="popular">محبوب‌ترین</option>
                <option value="most_download">بیشترین دانلود</option>
            </select>
        </div>
        
        <div class="filter-group">
            <button class="btn-reset">حذف فیلترها</button>
        </div>
    </form>

    <!-- تعداد نتایج و صفحه بندی -->
     <div class="top">
        <div class="result-count"></div>
        <div class="pagination"></div>
     </div>
    
    <!-- لیست موزیک‌ها -->
    <div class="music-grid">
        
    </div>
    
    <!-- فوتر -->
    <footer class="footer">
        <p>❤️ تمامی حقوق برای سایت موسیقی‌ما محفوظ است | پخش آنلاین آهنگ‌های به روز</p>
    </footer>
</div>
<script src="./js/jquery-3.7.1.min.js"></script>
<script src="./js/script.js"></script>
<script src="./js/player.js"></script>
</body>
</html>