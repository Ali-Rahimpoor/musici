jQuery(document).ready(function($){
   const toaster = new Toaster({
            position: 'top-right',
            duration: 3000,
            animation: 'slide',
            maxToasts: 5,
            rtl: true,
            pauseOnHover: true,
            closeButton: true,
            progressBar: true
   });
    let step = 1;
    let phoneNumber = '';
    let timerInterval;
    let timeLeft = 120;

 function checkHashForRegister() {
    if(window.location.hash === '#register') {
        // alert(1);
        $('.auth-tab[data-tab="register-tab"]').click();
    }
}
$(document).ready(function(){
    // alert(1);
    checkHashForRegister();
});

function enableSkeletonMode($form) {
    // اضافه کردن کلاس skeleton-mode به فرم
    $form.addClass('skeleton-mode');
    
    // غیرفعال کردن همه اینپوت‌ها
    $form.find('input, select, textarea, button').prop('disabled', true);
    
    // اضافه کردن افکت اسکلتون به همه فیلدها
    $form.find('.input-group, .checkbox-custom-label').each(function() {
        $(this).addClass('skeleton-item');
    });
}

// غیرفعال کردن اسکلتون
function disableSkeletonMode($form) {
    $form.removeClass('skeleton-mode');
    $form.find('input, select, textarea, button').prop('disabled', false);
    $form.find('.input-group, .checkbox-custom-label').removeClass('skeleton-item');
}

// نمایش خطا در فیلد
function showFieldError($field, message) {
    $field.addClass('error');
    
    // حذف پیام خطای قبلی
    $field.parent().find('.error-message').remove();
    
    // اضافه کردن پیام خطا
    $field.after('<div class="error-message">' + message + '</div>');
    
    // بعد از ۳ ثانیه پاک شدن خودکار
    setTimeout(function() {
        $field.removeClass('error');
        $field.parent().find('.error-message').fadeOut(300, function() {
            $(this).remove();
        });
    }, 4000);
}

// حذف خطا از فیلد
function removeFieldError($field) {
    $field.removeClass('error');
    $field.parent().find('.error-message').remove();
}

// اعتبارسنجی شماره تلفن ایرانی
function validatePhone(phone) {
    var phoneRegex = /^09[0-9]{9}$/;
    var cleanedPhone = phone.replace(/\s/g, '');
    return phoneRegex.test(cleanedPhone);
}

// نمایش توست نوتیفیکیشن
function showToast(message, type) {
    // حذف توست قبلی
    $('.toast-message').remove();
    
    var toast = $('<div class="toast-message ' + type + '">' + message + '</div>');
    $('body').append(toast);
    
    setTimeout(function() {
        toast.fadeOut(300, function() {
            $(this).remove();
        });
    }, 3000);
}
   $('.auth-tab').on('click', function() {
      // 1. حذف کلاس active از تمام تب‌ها
      $('.auth-tab').removeClass('active');
      
      // 2. اضافه کردن کلاس active به تب کلیک شده
      $(this).addClass('active');
      
      // 3. گرفتن مقدار data-tab برای شناسایی پنل مربوطه
      var tabId = $(this).data('tab');
      
      // 4. مخفی کردن همه پنل‌ها
      $('.auth-panel').removeClass('active');
      
      // 5. نمایش پنل مربوط به تب کلیک شده
      $('#' + tabId).addClass('active');
   });
   $('#register_form').submit(function(e) {
    e.preventDefault();
    
    let _btn = $(this).find('button.register-btn');
    let _data = $(this).serialize();
    let _this = $(this);
    
    // ===== اعتبارسنجی سمت کلاینت =====
    let isValid = true;
    
    // بررسی نام
    let name = $('input[name="name"]', _this).val();
    if(!name) {
        showFieldError($('input[name="name"]', _this), 'لطفاً نام خود را وارد کنید');
        isValid = false;
    } else {
        removeFieldError($('input[name="name"]', _this));
    }
    
    // بررسی شماره تلفن
    let phone = $('input[name="phone"]', _this).val();
    if(!phone) {
        showFieldError($('input[name="phone"]', _this), 'شماره تلفن را وارد کنید');
        isValid = false;
    } else {
        removeFieldError($('input[name="phone"]', _this));
    }
    
    // بررسی نام کاربری
    let username = $('input[name="username"]', _this).val();
    if(!username) {
        showFieldError($('input[name="username"]', _this), 'نام کاربری را وارد کنید');
        isValid = false;
    } else {
        removeFieldError($('input[name="username"]', _this));
    }
    
    // بررسی رمز عبور
    let password = $('input[name="password"]', _this).val();
    let confirmPass = $('.reg-confirm-input', _this).val();
    
    if(!password) {
        showFieldError($('input[name="password"]', _this), 'رمز عبور را وارد کنید');
        isValid = false;
    } else {
        removeFieldError($('input[name="password"]', _this));
    }
    
    if(password !== confirmPass) {
        showFieldError($('.reg-confirm-input', _this), 'رمز عبور با تکرار آن مطابقت ندارد');
        isValid = false;
    } else {
        removeFieldError($('.reg-confirm-input', _this));
    }
    
    // بررسی قوانین
    let termsAccepted = $('.terms-checkbox', _this).is(':checked');
    if(!termsAccepted) {
        showToast('لطفاً قوانین و مقررات را بپذیرید', 'error');
        isValid = false;
    }
    console.log(1);
    if(isValid === false){ return;}
    console.log(2);
    // ===== فعال کردن اسکلتون و لودینگ =====
    enableSkeletonMode(_this);
    
    $.ajax({
        type: "POST",
        url: "../ajax/login/register.php",
        data: _data,
        beforeSend: function() {
            // اضافه کردن کلاس loading به فرم
            _this.addClass('loading');
            
            // غیرفعال کردن دکمه ثبت‌نام
            _btn.prop('disabled', true);
            
            // تغییر متن دکمه به حالت لودینگ
            let originalText = _btn.html();
            _btn.data('original-text', originalText);
            _btn.html('<span class="spinner"></span> در حال ثبت‌نام...');
        },
        complete: function() {
            // حذف کلاس loading از فرم
            _this.removeClass('loading');
            
            // فعال کردن دکمه
            _btn.prop('disabled', false);
            _btn.html(_btn.data('original-text'));
            
            // غیرفعال کردن اسکلتون
            disableSkeletonMode(_this);
        },
        success: function(res) {
            console.log(res);
            
            // نمایش پیام موفقیت
            showToast(res.message, 'success');
            
            // پاک کردن فرم
            // _this[0].reset();
            
            // تغییر تب به ورود بعد از ۲ ثانیه
            setTimeout(function() {
                // اگر تابع تغییر تب دارید
                $('.auth-tab[data-tab="username-tab"]').click();
            }, 2000);
            
            // اگر ریدایرکت نیاز است
            // if(res.redirect) {
            //     window.location.href = res.redirect;
            // }
        },
        error: function(jqXHR) {
            let errorMessage = 'خطا در ثبت‌نام';
            if(jqXHR.responseJSON && jqXHR.responseJSON.message) {
                errorMessage = jqXHR.responseJSON.message;
            }
            showToast(errorMessage, 'error');
            
            // نمایش خطاهای دریافتی روی فیلدهای مربوطه
            if(jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                $.each(jqXHR.responseJSON.errors, function(field, message) {
                    let $field = $('input[name="' + field + '"]', _this);
                    if($field.length) {
                        showFieldError($field, message);
                    }
                });
            }
        }
    });
   });
   $("#auth-form").submit(function(e){
      e.preventDefault();
    let _btn = $(this).find('button.login-btn');
    let _data = $(this).serialize();
    let _this = $(this);
    $.ajax({
      type:"POST",
      url:"../ajax/login/login.php",
      data:_data,
      beforeSend:function(){
      $(_this).addClass('loading');
      _btn.prop('disabled', true);
      },
      complete:function(){
         $(_this).removeClass('loading');
      _btn.prop('disabled', false);
      },
      success:function(res){
           console.log(res);
           showToast(res.message, 'success');      
           if(res.redirect) {
               setTimeout(function() {
                  window.location.href = res.redirect;
               }, 2000);
            }
      },
      error: function(jqXHR) {
            let errorMessage = 'خطا در ورود';
            if(jqXHR.responseJSON && jqXHR.responseJSON.message) {
                errorMessage = jqXHR.responseJSON.message;
            }
            showToast(errorMessage, 'error');
            
            // نمایش خطاهای دریافتی روی فیلدهای مربوطه
            if(jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                $.each(jqXHR.responseJSON.errors, function(field, message) {
                    let $field = $('input[name="' + field + '"]', _this);
                    if($field.length) {
                        showFieldError($field, message);
                    }
                });
            }
        }
    })
   })
     $(document).on('click', '.send-otp-btn', function(e) {
        e.preventDefault();
        
        let $btn = $(this);
        let phone = $('.phone-input').val();
        
        // اعتبارسنجی شماره
        let phoneRegex = /^09[0-9]{9}$/;
        if(!phoneRegex.test(phone)) {
            showToast('شماره تلفن نامعتبر است', 'error');
            return;
        }
        
        phoneNumber = phone;
        
        $.ajax({
            type: "POST",
            url: "../ajax/login/send_otp.php", // آدرس فایل PHP خودت رو بزار
            data: { phone: phone, action: 'send' },
            beforeSend: function() {
                $btn.prop('disabled', true);
                $btn.html('<span class="loading-spinner"></span> در حال ارسال...');
            },
            success: function(res) {
                console.log(res);
                showToast(res.message, 'success');
                 if(res.expire) {
                    startTimer(res.expire);
                } else {
                    startTimer(120); // پیش‌فرض 120 ثانیه
                }
                $('.otp-group').removeClass('hidden');
                $('.send-otp-btn').addClass('hidden');
                $('.verify-otp-btn').removeClass('hidden');
                $('.auth-desc').text('کد تایید 4 رقمی ارسال شد');
                $('.otp_code').html(res.code);
            },
            error: function(jqXHR) {
               let errorMessage = jqXHR.responseJSON.message;
                showToast(errorMessage, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false);
                $btn.html('ارسال کد تایید');
            }
        });
    });
    $(document).on('click', '.resend-otp-btn', function(e) {
    e.preventDefault();
    $('.send-otp-btn').click(); // شبیه‌سازی کلیک روی دکمه ارسال
    $(this).addClass('hidden');
});
    // تایید کد و ورود
    $(document).on('click', '.verify-otp-btn', function(e) {
        e.preventDefault();
        
        let $btn = $(this);
        let otpCode = $('.otp-input').val();
        
        
        
        $.ajax({
            type: "POST",
            url: "../ajax/login/verify.php",
            data: { 
                phone: phoneNumber, 
                otp: otpCode, 
                action: 'verify' 
            },
            beforeSend: function() {
                $btn.prop('disabled', true);
                $btn.html('<span class="loading-spinner"></span> در حال بررسی...');
            },
            success: function(res) {
                showToast(res.message, 'success');
                clearTimer();
                if(res.redirect) {
                    setTimeout(function() {
                        window.location.href = res.redirect;
                    }, 1500);
                }
            },
            error: function(jqXHR) {
                 let errorMessage = jqXHR.responseJSON.message;
                showToast(errorMessage, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false);
                $btn.html('تایید و ورود');
            }
        });
    });


    function startTimer(seconds) {
    clearTimer(); // تایمر قبلی رو پاک کن
    timeLeft = seconds;
    updateTimerDisplay();
    
    timerInterval = setInterval(function() {
        if(timeLeft <= 1) {
            clearTimer();
            $('.timer').text('0');
            $('.timer-text').text('ارسال مجدد کد');
            $('.resend-otp-btn').removeClass('hidden');
        } else {
            timeLeft--;
            updateTimerDisplay();
        }
    }, 1000);
}

// بروزرسانی نمایش تایمر
function updateTimerDisplay() {
    $('.timer').text(timeLeft);
}

// پاک کردن تایمر
function clearTimer() {
    if(timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
}
});
