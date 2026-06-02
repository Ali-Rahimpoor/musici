<?php
require '../init.php';
check_user_login();
$user = get_user_by_id(get_current_user_id());
 $user_id = $user['ID'];
 $user_name = $user['name'] ?? "کاربر ";
 $user_username = $user['username'] ?? "";
 $user_password = $user['password'] ?? "";
 $user_cover = $user['avatar'] ?? "../public/img/default_avatar.jpg";
 $user_role = $user['role'] ;
 $siteName = get_setting('site_name', 'موزیکی');
 $siteDescription = get_setting('site_description', 'پلتفرم پخش آنلاین موزیک');
 $is_maintance = get_setting('site_status','active');
 if($is_maintance == 'maintenance' && $user_role !='admin'){
    redirect('/myphp/musici/maintance.php');
 }
//  print_r($siteName);exit;
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteName . " | " . $siteDescription ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($siteDescription); ?>">
    <link rel="stylesheet" href="../public/panel.css">
    <link rel="stylesheet" href="../public/croppie.css">
    <link rel="stylesheet" href="../public/player.css">
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        