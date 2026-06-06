<?php
 require "./init.php";
 $userId = get_current_user_id();
 $user='';
 if($userId){
    $user = get_user_by_id($userId);
 }
 $music_id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : 0;
 $sql = "SELECT * FROM view_musics WHERE music_id = '$music_id'";
//  die($sql);
 $res = db_query($sql);
 $music='';
 if($res){
   $music = mysqli_fetch_assoc($res);
 }
 $is_login = false;
 if($userId){
    $is_login=true;
 }
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پخش موزیک | تجربه شنیداری بی‌نظیر</title>
    <link rel="stylesheet" href="./public/style.css">
    <link rel="stylesheet" href="./public/player.css">
    <style>
        .comment-user-answer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid var(--color-border);
    border-radius: 10px;
    position: relative;
}

.comment-user-answer .user-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.comment-user-answer .user-name {
    font-weight: 600;
    font-size: 13px;
    color: #4a90e2;
    background: #eef3fc;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.comment-user-answer .user-name::before {
    content: "✍️";
    font-size: 12px;
}

.comment-user-answer .comment-date {
    font-size: 11px;
    color: #94a3b8;
}

.comment-user-answer .answer-badge {
    background: #e6f7e6;
    color: #2e7d32;
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 15px;
    font-weight: 500;
}

.comment-user-answer .answer-badge::before {
    content: "✅ ";
}
        /* استایل‌های حرفه‌ای صفحه تک موزیک - سطح جهانی */
        .reply-btn {
            background: transparent;
            border: 1px solid #e0e0e0;
            color: #4a90e2;
            font-size: 13px;
            padding: 4px 12px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            
        }

        .reply-btn:hover {
            background: #4a90e2;
            color: white;
            border-color: #4a90e2;
        }

        /* فرم پاسخ */
        .reply-form-container {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed #e0e0e0;
            display: none;
        }
        .reply-form-container.active{
            display: block;
        }


        .reply-textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            resize: vertical;
        }

        .reply-textarea:focus {
            outline: none;
            border-color: #4a90e2;
        }

        /* دکمه‌های فرم پاسخ */
        .reply-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 8px;
        }

        .btn-cancel-reply {
            background: #f5f5f5;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-cancel-reply:hover {
            background: #e0e0e0;
        }

        .btn-submit-reply {
            background: #4a90e2;
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-submit-reply:hover {
            background: #357abd;
        }
        /* انیمیشن‌های پیشرفته */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(72, 187, 150, 0.3); }
            50% { box-shadow: 0 0 40px rgba(72, 187, 150, 0.6); }
        }
        
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .single-music-page {
            min-height: 100vh;
            background: radial-gradient(circle at 20% 50%, rgba(17, 24, 28, 0.8), var(--color-bg-dark));
            padding: 2rem 0 5rem;
            position: relative;
        }
        
        .single-music-page::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(72,187,150,0.05)"/><circle cx="80" cy="40" r="3" fill="rgba(72,187,150,0.05)"/><circle cx="50" cy="80" r="2" fill="rgba(72,187,150,0.05)"/></svg>') repeat;
            pointer-events: none;
            z-index: 0;
        }
        
        /* دکمه بازگشت پیشرفته */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(17, 24, 28, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid var(--color-border);
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            color: var(--color-text-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .back-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: var(--color-accent);
            border-radius: 50%;
            transition: all 0.6s ease;
            transform: translate(-50%, -50%);
            z-index: -1;
        }
        
        .back-button:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .back-button:hover {
            border-color: var(--color-accent);
            color: var(--color-bg-dark);
            transform: translateX(-8px);
        }
        
        /* بخش اصلی موزیک - طراحی سینمایی */
        .music-hero {
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 2.5rem;
            background: rgba(17, 24, 28, 0.7);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            border: 1px solid var(--color-border);
            overflow: hidden;
            margin-bottom: 4rem;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
        }
        
        /* بخش کاور با افکت‌های ویژه */
        .music-cover-section {
            background: linear-gradient(135deg, rgba(26, 37, 37, 0.9), rgba(15, 20, 24, 0.95));
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .music-cover-section::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(72, 187, 150, 0.1) 0%, transparent 70%);
            animation: spin-slow 20s linear infinite;
        }
        
        .cover-wrapper {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        
        .cover-image {
            width: 100%;
            border-radius: 1.5rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), 0 0 0 3px rgba(72, 187, 150, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }
        
        .cover-image:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6), 0 0 0 5px var(--color-accent);
        }
        
        .vinyl-effect {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 90%;
            height: 90%;
            background: radial-gradient(circle, transparent 60%, rgba(0,0,0,0.3) 100%);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }
        
        /* پلیر حرفه‌ای */
        .music-player {
            width: 100%;
            margin-top: 2rem;
            background: rgba(0, 0, 0, 0.3);
            padding: 1.5rem;
            border-radius: 1.5rem;
            backdrop-filter: blur(10px);
        }
        
        .player-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .control-btn {
            background: rgba(72, 187, 150, 0.2);
            border: 2px solid var(--color-accent);
            width: 55px;
            height: 55px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--color-text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .control-btn:hover {
            background: var(--color-accent);
            color: var(--color-bg-dark);
            transform: scale(1.1);
            box-shadow: 0 0 20px var(--color-accent);
        }
        
        .play-btn {
            width: 70px;
            height: 70px;
            background: var(--color-accent);
            color: var(--color-bg-dark);
            font-size: 2rem;
            animation: glow 2s infinite;
        }
        
        .progress-container {
            width: 100%;
            margin: 1rem 0;
        }
        
        .progress-bar {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .progress {
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, var(--color-accent), var(--color-primary));
            border-radius: 3px;
            position: relative;
            transition: width 0.1s linear;
        }
        
        .progress::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            background: var(--color-accent);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--color-accent);
        }
        
        .time-info {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: var(--color-text-secondary);
            font-family: monospace;
        }
        
        /* اطلاعات موزیک - طراحی لوکس */
        .music-info-section {
            padding: 2.5rem;
            position: relative;
        }
        
        .music-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .music-title-main {
            font-size: 2.8rem;
            font-weight: bold;
            background: linear-gradient(135deg, var(--color-accent), var(--color-text-primary));
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin: 0;
            letter-spacing: -0.02em;
        }
        
        .music-badge {
            background: linear-gradient(135deg, var(--color-accent), var(--color-primary));
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: bold;
            color: var(--color-bg-dark);
            box-shadow: 0 0 15px rgba(72, 187, 150, 0.3);
        }
        
        .artist-info {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            margin-bottom: 2rem;
            padding: 1rem 0;
            border-top: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
        }
        
        .artist-avatar-small {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-accent), var(--color-primary));
            object-fit: cover;
            border: 3px solid var(--color-accent);
        }
        
        .artist-label {
            color: var(--color-text-secondary);
            font-size: 0.85rem;
            display: block;
        }
        
        .artist-name-link {
            color: var(--color-accent);
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .artist-name-link:hover {
            color: var(--color-primary);
            transform: translateX(5px);
            display: inline-block;
        }
        
        .music-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 1rem;
            transition: all 0.3s ease;
        }
        
        .detail-item:hover {
            background: rgba(72, 187, 150, 0.1);
            transform: translateX(5px);
        }
        
        .detail-label {
            color: var(--color-text-secondary);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .detail-value {
            font-weight: bold;
            color: var(--color-accent);
        }
        
        /* دکمه‌های اکشن پیشرفته */
        .music-actions-large {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .action-btn-large {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.9rem;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--color-border);
            border-radius: 1rem;
            color: var(--color-text-primary);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .action-btn-large::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(72, 187, 150, 0.3);
            border-radius: 50%;
            transition: all 0.5s ease;
            transform: translate(-50%, -50%);
        }
        
        .action-btn-large:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .action-btn-large:hover {
            border-color: var(--color-accent);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        .like-btn:hover {
            background: rgba(248, 113, 113, 0.2);
            border-color: var(--color-error);
        }
        
        .download-btn:hover {
            background: var(--color-primary);
            border-color: var(--color-primary);
        }
        
        .share-btn:hover {
            background: var(--color-accent);
            border-color: var(--color-accent);
            color: var(--color-bg-dark);
        }
        
        /* توضیحات موزیک */
        .music-description-full {
            background: linear-gradient(135deg, rgba(0,0,0,0.3), rgba(0,0,0,0.1));
            padding: 1.5rem;
            border-radius: 1rem;
            border-left: 4px solid var(--color-accent);
        }
        
        .music-description-full h3 {
            margin-bottom: 0.8rem;
            color: var(--color-accent);
            font-size: 1.1rem;
        }
        
        .music-description-full p {
            color: var(--color-text-secondary);
            line-height: 1.8;
            text-align: justify;
        }
        
        /* بخش آرتیست‌ها */
        .featured-artists, .related-musics, .comments-section {
            margin-top: 4rem;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }
        
        .section-title {
            font-size: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid var(--color-accent);
            display: inline-block;
            background: linear-gradient(135deg, var(--color-accent), var(--color-text-primary));
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        
        .artists-list {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }
        
        .artist-card-mini {
            text-align: center;
            transition: all 0.4s ease;
            cursor: pointer;
        }
        
        .artist-card-mini:hover {
            transform: translateY(-10px);
        }
        
        .artist-img-mini {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-accent), var(--color-primary));
            object-fit: cover;
            border: 3px solid var(--color-border);
            transition: all 0.3s ease;
        }
        
        .artist-card-mini:hover .artist-img-mini {
            border-color: var(--color-accent);
            box-shadow: 0 0 20px rgba(72, 187, 150, 0.5);
        }
        
        .artist-name-mini {
            display: block;
            margin-top: 0.8rem;
            color: var(--color-text-secondary);
            font-weight: 500;
        }
        
        /* موزیک‌های مرتبط */
        .related-musics .music-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.5rem;
        }
        
        /* بخش نظرات پیشرفته */
        .comments-section {
            background: rgba(17, 24, 28, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            padding: 2rem;
            border: 1px solid var(--color-border);
        }
        
        .comment-form {
            margin-bottom: 2rem;
        }
        
        .comment-form textarea {
            width: 100%;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--color-border);
            border-radius: 1rem;
            color: var(--color-text-primary);
            font-family: inherit;
            margin-bottom: 1rem;
            resize: vertical;
            transition: all 0.3s ease;
        }
        
        .comment-form textarea:focus {
            outline: none;
            border-color: var(--color-accent);
            box-shadow: 0 0 15px rgba(72, 187, 150, 0.3);
        }
        
        .btn-submit-comment {
            background: linear-gradient(135deg, var(--color-accent), var(--color-primary));
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            color: var(--color-bg-dark);
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-submit-comment:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(72, 187, 150, 0.3);
        }
        
        .comment-item {
            background: rgba(0, 0, 0, 0.3);
            padding: 1.2rem;
            border-radius: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .comment-item:hover {
            border-color: var(--color-accent);
            transform: translateX(10px);
        }
        
        .comment-user {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
        }
        
        .user-name {
            font-weight: bold;
            color: var(--color-accent);
        }
        
        .comment-date {
            font-size: 0.75rem;
            color: var(--color-text-secondary);
        }
        
        .comment-text {
            color: var(--color-text-secondary);
            line-height: 1.6;
        }
        
        /* ریسپانسیو برای دسکتاپ - بهینه‌سازی */
        @media (min-width: 1400px) {
            .container {
                max-width: 1400px;
            }
            
            .music-title-main {
                font-size: 3.2rem;
            }
        }
        
        /* اسکرول بار سفارشی */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--color-bg-dark);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--color-accent);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary);
        }
        
    </style>
</head>
<body>
    <div class="single-music-page">
        <a href="<?php echo site_url(''); ?>" class="back-button">
                <span>←</span> بازگشت به لیست پخش
            </a>
      <?php if($music): ?>
        <div class="container">
            <!-- دکمه بازگشت با افکت حرفه‌ای -->
          

            <!-- بخش اصلی موزیک - طراحی سینمایی و لوکس -->
            <div class="music-hero">
                <!-- سمت راست - کاور آلبوم و پلیر پیشرفته -->
                <div class="music-cover-section">
                    <div class="cover-wrapper">
                        <img src="<?php echo $music['music_cover'] ?>" alt="Music Cover" class="cover-image">
                        <div class="vinyl-effect"></div>
                    </div>
                    
                    <div class="music-player">
                        <div class="player-controls">                            
                            <button class="control-btn play-now-btn play-btn" data-music-url="<?php echo htmlspecialchars($music['music_url']); ?>">▶</button>
                        </div>
                    </div>
                </div>

                <!-- سمت چپ - اطلاعات کامل موزیک -->
                <div class="music-info-section">
                    <div class="music-header">
                        <h1 class="music-title-main"><?php echo $music['music_title'] ?></h1>
                        <?php if($music['music_like_count'] > 10): ?>
                        <span class="music-badge">موزیک محبوب</span>
                        <?php endif; ?>
                    </div>

                    <div class="artist-info">
                        <img src="<?php echo $music['artist_avatar']; ?>" alt="Artist Avatar" class="artist-avatar-small">
                        <div>
                            <span class="artist-label">خواننده و آهنگساز:</span>
                            <a href="<?php echo site_url('?artist='.$music['artist_id']); ?>" class="artist-name-link"><?php echo $music['artist_name']; ?></a>
                        </div>
                    </div>

                    <div class="music-details">
                        <div class="detail-item">
                            <span class="detail-label">🎵 ژانر:</span>
                            <span class="detail-value"><?php echo $music['category_title']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">📅 تاریخ انتشار:</span>
                            <span class="detail-value"><?php echo jdate('Y/m/d',strtotime($music['music_created_at'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">⏱ مدت زمان:</span>
                            <span class="detail-value"><?php $music['music_duration']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">❤️ تعداد لایک:</span>
                            <span class="detail-value"><?php echo number_format($music['music_like_count']); ?></span>
                        </div>                        
                        <div class="detail-item">
                            <span class="detail-label">🔥 تعداد دانلود:</span>
                            <span class="detail-value"><?php echo $music['music_download_count'] ?></span>
                        </div>
                    </div>

                    <div class="music-actions-large">
                       <?php if($is_login): ?>
                        <button class="action-btn like-btn <?php echo $is_liked !=0 ? 'liked' : ''; ?> " data-music-id="<?php echo $music['music_id']; ?>">
                            <span class="like-icon">❤️</span>
                        </button>
                        <?php else: ?>
                        <button class="need_login action-btn">
                            <span class="like-icon">❤️</span>
                        </button>
                        <?php endif; ?>
                        <a data-music-id='<?php echo $music['music_id']; ?>' download href="<?php echo htmlspecialchars($music['music_url']); ?>" class="download-btn action-btn">
                            ⬇️
                            دانلود
                        </a>
                        <button  class="action-btn-large share-btn">
                            کپی کردن آدرس
                        </button>
                    </div>

                    <div class="music-description-full">
                        <h3>📖 درباره این اثر:</h3>
                        <?php echo autop($music['music_content']); ?>
                    </div>
                </div>
            </div>

            <!-- بخش آرتیست‌های همکار -->
            <div class="featured-artists">
                <h2 class="section-title">🎤 هنرمند این موزیک</h2>
                <div class="artists-list">
                  <?php include ABSPATH . "parts/artist-cart.php"; ?>
                </div>  
            </div>

            <!-- بخش موزیک‌های مرتبط و پیشنهادی -->
            <div class="related-musics">
                <h2 class="section-title">🎶 آهنگ‌های مشابه که ممکن است دوست داشته باشید</h2>
                <div class="music-grid music-related" data-music-id='<?php echo $music['music_id']; ?>' data-artist-id='<?php echo $music['artist_id']; ?>'>
                    
                </div>
            </div>

            <!-- بخش نظرات کاربران با طراحی مدرن -->
            <?php include ABSPATH.'parts/comments.php'; ?>
        </div>
      <?php else: ?>
         <div>موزیکی یافت نشد</div>
      <?php endif; ?>
    </div>
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="./js/toaster.js"></script>
    <script src="./js/script.js"></script>
    <script src="./js/player.js"></script>
</body>
</html>