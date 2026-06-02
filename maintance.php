<?php
// maintenance.php
require "./init.php";

$siteStatus = get_setting('site_status', 'active');

if ($siteStatus !== 'maintenance') {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>در حال بروزرسانی</title>
    <style>
      @font-face {
         font-family: 'balo';
         src: url('./public/font/BalooBhaijaan2-Bold.ttf') format('truetype');
      }
        body {
            font-family: 'balo', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .maintenance-box {
            text-align: center;
            padding: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
        h1 { font-size: 48px; margin-bottom: 20px; }
        p { font-size: 18px; opacity: 0.9; }
    </style>
</head>
<body>
    <div class="maintenance-box">
        <h1>🔧 در حال بروزرسانی</h1>
        <p>سایت در حال بروزرسانی می‌باشد. لطفاً چند دقیقه دیگر مراجعه فرمایید.</p>
        <p>با تشکر از صبر و شکیبایی شما</p>
    </div>
</body>
</html>