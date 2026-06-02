    <?php require "./parts/header.php"; ?>
        <!-- سایدبار -->
        <?php require "./parts/sidebar.php" ?>    
        <!-- محتوای اصلی --> 
            <!-- ==================== صفحه 1: داشبورد اصلی ==================== -->
            <div class="page-content active" id="dashboard-page">
                <!-- کارت‌های آماری -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">🎵</div>
                        <div class="stat-info">
                            <h3 class="stat-value" id="totalMusicCount">0</h3>
                            <p class="stat-label">کل موزیک‌ها</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-info">
                            <h3 class="stat-value" id="totalUsersCount">0</h3>
                            <p class="stat-label">کاربران فعال</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">❤️</div>
                        <div class="stat-info">
                            <h3 class="stat-value" id="totalLikesCount">0</h3>
                            <p class="stat-label">کل لایک‌ها</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📀</div>
                        <div class="stat-info">
                            <h3 class="stat-value" id="totalCategoriesCount">0</h3>
                            <p class="stat-label">دسته‌بندی‌ها</p>
                        </div>
                    </div>
                </div>
                
                <!-- نمودار و بخش اصلی -->
                <div class="dashboard-grid">
                    <div class="chart-container">
                        <div class="card-header">
                            <h3>آمار پخش موزیک</h3>
                            <select class="chart-select" id="chartPeriod">
                                <option value="week">این هفته</option>
                                <option value="month">این ماه</option>
                                <option value="year">امسال</option>
                            </select>
                        </div>
                        <div class="chart-placeholder" id="musicChart">
                            <!-- نمودار با JS شما رندر می‌شود -->
                            <div class="chart-bars">
                                <div class="bar-item" style="height: 60%"></div>
                                <div class="bar-item" style="height: 45%"></div>
                                <div class="bar-item" style="height: 80%"></div>
                                <div class="bar-item" style="height: 35%"></div>
                                <div class="bar-item" style="height: 70%"></div>
                                <div class="bar-item" style="height: 55%"></div>
                                <div class="bar-item" style="height: 90%"></div>
                            </div>
                            <div class="chart-labels">
                                <span>شنبه</span><span>یکشنبه</span><span>دوشنبه</span>
                                <span>سه‌شنبه</span><span>چهارشنبه</span><span>پنجشنبه</span><span>جمعه</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- موزیک‌های محبوب -->
                    <div class="top-music">
                        <div class="card-header">
                            <h3>محبوب‌ترین موزیک‌ها</h3>
                            <button class="btn-link">مشاهده همه</button>
                        </div>
                        <div class="music-list" id="topMusicList">
                            <div class="music-item">
                                <span class="music-rank">1</span>
                                <div class="music-info">
                                    <h4>آهنگ نمونه ۱</h4>
                                    <p>خواننده نمونه</p>
                                </div>
                                <span class="music-plays">۱۲.۵K پخش</span>
                            </div>
                            <div class="music-item">
                                <span class="music-rank">2</span>
                                <div class="music-info">
                                    <h4>آهنگ نمونه ۲</h4>
                                    <p>خواننده نمونه</p>
                                </div>
                                <span class="music-plays">۹.۸K پخش</span>
                            </div>
                            <div class="music-item">
                                <span class="music-rank">3</span>
                                <div class="music-info">
                                    <h4>آهنگ نمونه ۳</h4>
                                    <p>خواننده نمونه</p>
                                </div>
                                <span class="music-plays">۷.۲K پخش</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- آخرین فعالیت‌ها -->
                <div class="recent-activities">
                    <div class="card-header">
                        <h3>آخرین فعالیت‌ها</h3>
                    </div>
                    <div class="activities-list" id="activitiesList">
                        <div class="activity-item">
                            <span class="activity-icon">➕</span>
                            <div class="activity-info">
                                <p>موزیک جدید اضافه شد</p>
                                <span class="activity-time">۲ دقیقه پیش</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php require "./parts/footer.php"; ?>