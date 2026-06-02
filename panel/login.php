<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود / ثبت‌نام | نئوتک</title>
    <link rel="stylesheet" href="../public/login.css">
</head>
<body class="auth-body">
    <div class="auth-fullpage">
        <div class="auth-main-card">
            
            <!-- تب‌ها (کنترل با JS شما) -->
            <div class="auth-tabs-wrapper">
                <div class="auth-tabs">
                    <button class="auth-tab active" data-tab="phone-tab">ورود با تلفن</button>
                    <button class="auth-tab " data-tab="username-tab">ورود با کاربری</button>
                    <button class="auth-tab " data-tab="register-tab">ثبت‌نام</button>
                </div>
            </div>

            <!-- ============= پنل 1: ورود با شماره تلفن ============= -->
            <div class="auth-panel active" id="phone-tab">
                <div class="auth-icon-large">📱</div>
                <h2 class="auth-title">ورود با شماره تلفن</h2>
                <p class="auth-desc">کد تایید به شماره شما پیامک می‌شود</p>

                <form id='auth-phone' class="auth-form">
                    <div class="input-group">
                        <label class="input-label">شماره تلفن همراه</label>
                        <div class="input-with-icon">
                            <span class="input-icon">📞</span>
                            <input type="tel" class="auth-input phone-input" placeholder="0912 123 4567">
                        </div>
                    </div>

                    <div class="input-group otp-group hidden">
                        <label class="input-label">کد تایید</label>
                        <div class="flex_bet">
                            <span class="otp_code"></span>
                            <span class="timer">120</span>
                        </div>
                         <button class="resend-otp-btn hidden" style="background:none; border:none; color:#00d4ff; cursor:pointer;">ارسال مجدد کد</button>
                        <div class="input-with-icon">
                            <span class="input-icon">🔢</span>
                            <input type="text" class="auth-input otp-input" placeholder="_ _ _ _ _" maxlength="6">
                        </div>
                    </div>

                    <button class="btn-auth btn-primary send-otp-btn">ارسال کد تایید</button>
                    <button class="btn-auth btn-primary verify-otp-btn hidden">تایید و ورود</button>

                </form>
            </div>

            <!-- ============= پنل 2: ورود با نام کاربری و رمز عبور ============= -->
            <div class="auth-panel" id="username-tab">
                <div class="auth-icon-large">👤</div>
                <h2 class="auth-title">ورود به حساب کاربری</h2>
                <p class="auth-desc">با نام کاربری و رمز عبور وارد شوید</p>

                <form id='auth-form' class="auth-form">
                    <div class="input-group">
                        <label class="input-label">نام کاربری</label>
                        <div class="input-with-icon">
                            <span class="input-icon">👨‍💻</span>
                            <input type="text" name='username' class="auth-input username-input" placeholder="username یا ایمیل">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">رمز عبور</label>
                        <div class="input-with-icon">
                            <span class="input-icon">🔒</span>
                            <input name='password' type="password" class="auth-input password-input" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="form-extra">
                        <label class="checkbox-custom-label">
                            <input type="checkbox" class="hidden-checkbox remember-checkbox">
                            <span class="custom-checkbox"></span>
                            <span class="checkbox-text">مرا به خاطر بسپار</span>
                        </label>
                        <a href="#" class="forgot-pass">فراموشی رمز؟</a>
                    </div>
                    
                    <button class="btn-auth btn-primary login-btn">ورود به حساب</button>
                </form>
            </div>

            <!-- ============= پنل 3: ثبت‌نام کاربر جدید ============= -->
            <div class="auth-panel" id="register-tab">
                <div class="auth-icon-large">✨</div>
                <h2 class="auth-title">عضویت در موزیکی</h2>
                <p class="auth-desc">همین حالا ثبت‌نام کنید</p>

                <form id='register_form' class="auth-form skeleton-mode">
                    <div class="input-group">
                        <label class="input-label">نام و نام خانوادگی</label>
                        <div class="input-with-icon">
                            <span class="input-icon">👤</span>
                            <input name='name' type="text" class="auth-input fullname-input" placeholder="علی رضایی">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">شماره تلفن</label>
                        <div class="input-with-icon">
                            <span class="input-icon">📱</span>
                            <input name='phone' type="tel" class="auth-input reg-phone-input" placeholder="0912 123 4567">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">نام کاربری</label>
                        <div class="input-with-icon">
                            <span class="input-icon">🏷️</span>
                            <input name='username' type="text" class="auth-input reg-username-input" placeholder="alirezaei">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">رمز عبور</label>
                        <div class="input-with-icon">
                            <span class="input-icon">🔒</span>
                            <input name='password' type="password" class="auth-input reg-password-input" placeholder="حداقل ۶ کاراکتر">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">تکرار رمز عبور</label>
                        <div class="input-with-icon">
                            <span class="input-icon">✓</span>
                            <input type="password" class="auth-input reg-confirm-input" placeholder="تکرار رمز عبور">
                        </div>
                    </div>

                    <label class="checkbox-custom-label">
                        <input type="checkbox" class="hidden-checkbox terms-checkbox">
                        <span class="custom-checkbox"></span>
                        <span class="checkbox-text">با <a href="#" class="link-accent">قوانین و مقررات</a> موافقم</span>
                    </label>

                    <button class="btn-auth btn-primary register-btn">ثبت‌نام و ادامه</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.min.js"></script>
<script src="../js/toaster.js"></script>
<script src="../js/login.js"></script>
</html>