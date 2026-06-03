<aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo"><a href="/myphp/musici" class="logo-accent">موزیکی</a></div>
                <div class="user-badge">
                    <span class="user-role" id="userRoleDisplay">ادمین</span>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href='index.php' class="nav-item" data-page="dashboard">
                    <span class="nav-icon">📊</span>
                    <span  class="nav-text">داشبورد اصلی</span>
                </a>
                <a href='profile.php' class="nav-item" data-page="profile">
                    <span class="nav-icon">👤</span>
                    <span  class="nav-text">پروفایل کاربری</span>
                </a>
                <a href='favorites.php' class="nav-item" data-page="favorites">
                    <span class="nav-icon">❤️</span>
                    <span  class="nav-text">علاقه‌مندی‌ها</span>
                </a>
                
                <!-- بخش مدیریت (فقط برای ادمین) -->
                <div class="admin-section " id="adminSection">
                    <div class="nav-divider">مدیریت</div>
                    <a href='settings.php' class="nav-item" data-page="settings">
                        <span class="nav-icon">⚙️</span>
                        <span  class="nav-text">تنظیمات سایت</span>
                    </a>
                    <a href='addmusic.php' class="nav-item" data-page="add-music">
                        <span class="nav-icon">🎵</span>
                        <span  class="nav-text">اضافه کردن موزیک</span>
                    </a>
                    <a href='artist.php' class="nav-item" data-page="add-artist">
                        <span class="nav-icon">🎵</span>
                        <span  class="nav-text">اضافه کردن خواننده</span>
                    </a>
                    <a href='category.php' class="nav-item" data-page="add-category">
                        <span class="nav-icon">📁</span>
                        <span  class="nav-text">اضافه کردن دسته‌بندی</span>
                    </a>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <button class="logout-btn">
                    <span class="nav-icon">🚪</span>
                    <span href='#' class="nav-text">خروج از حساب</span>
                </button>
            </div>
        </aside>
                <main class="main-content">
            
            <!-- هدر داشبورد -->
            <header class="dashboard-header">
                <div class="header-title">
                    <h1 id="pageTitle">داشبورد اصلی</h1>
                    <p id="pageSubtitle">خوش آمدید، <span id="userName">کاربر عزیز</span></p>
                </div>
                <div class="header-actions">
                    <div class="notification-bell">🔔</div>
                    <div class="user-avatar">
                        <img src="<?php echo $user_cover;?>" alt="avatar">
                    </div>
                </div>
            </header>