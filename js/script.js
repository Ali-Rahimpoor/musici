$(document).ready(function() {
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
    $(document).on('click','.need_login',function(e){
        e.preventDefault();
        alert('برای لایک کردن باید وارد حساب کاربری خود شوید');
    })
    function downloadFile(url) {
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const link = document.createElement('a');
            const objectUrl = URL.createObjectURL(blob);
            link.href = objectUrl;
            link.download = ''; // نام فایل رو میتونی مشخص کنی
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(objectUrl);
        })
        .catch(error => console.error('Download error:', error));
    }
   $(document).on('click','.music-actions a',function(e){
    let music_id = $(e.target).attr('data-music-id');
    let downloadLink = $(this).attr('href');
    $.ajax({
        url:"./ajax/music/download_count.php",
        type:"POST",
        data:{music_id:music_id},
    
        success:function(res){
            if(res.download_count){
                $(`.download_count[data-music-id="${music_id}"]`).text(res.download_count);
            }
        },
         complete: function(){
            downloadFile(downloadLink);
        }
    });
    });
    $(document).on('click','.pagination button',function(e){
        e.preventDefault();
        // console.log(e.target.textContent);
        $("#page_input").val(e.target.textContent);
        loadMusicData();
    })
    function copyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        toaster.success('موفقیت','آدرس با موفقیت ذخیره شد');
    }
    $('.share-btn').on('click',function(e){
        e.preventDefault();
        let url = window.location.href;
        copyToClipboard(url);
    });
    // بارگذاری اولیه
    if($('form.filters-section').length){
        loadMusicData();
    }
    if($(".music-related").length){
        loadRelated_musics();
    }
    function loadRelated_musics() {        
        let artist_id = $('.music-related').data('artist-id');
        let music_id = $('.music-related').data('music-id');
        
        $.ajax({
            type: "GET",
            url: './ajax/music/get_related_music.php',
            data: {
                artist_id,
                music_id
            },
            dataType: 'json',
            beforeSend: function() {
                 $('.music-grid').addClass('loading');
                 $('.music-grid').html(`
                    <div class="spinner-container">
                        <div style="text-align: center;">
                            <div class="spinner"></div>
                            <div class="spinner-text">در حال بارگذاری...</div>
                        </div>
                    </div>
                `);
            },
            complete: function() {
                 $('.music-grid').removeClass('loading');
            },
            success: function(response) {
                if (response.success) {
                    $('.music-grid').find('.music-card, .item, .card').addClass('music-card');
                    $('.music-grid').html(response.summary);
                    $('.pagination').html(response.pagination);
                    // به‌روزرسانی شمارنده نتایج
                    if (response.count !== undefined) {
                        $('.result-count').html(`
                            <span>${response.count} <span>  تا پیدا شد `);
                        if(response.count === 0) {
                            $('.result-count').addClass('no-result');
                        } else {
                            $('.result-count').removeClass('no-result');
                        }
                    }
                } else {
                    $('.music-grid').html('<div class="error-message">❌ خطا در بارگذاری موزیک‌ها</div>');
                }
            },
            error: function() {
                $('.music-grid').html('<div class="error-message">❌ خطا در ارتباط با سرور</div>');
            }
        });
    }
    // تابع بارگذاری موزیک‌ها
    function loadMusicData() {
        let formData = $('form.filters-section').serialize();
        
        $.ajax({
            type: "GET",
            url: './ajax/music/get_all.php',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                 $('.music-grid').addClass('loading');
                 $('.music-grid').html(`
                    <div class="spinner-container">
                        <div style="text-align: center;">
                            <div class="spinner"></div>
                            <div class="spinner-text">در حال بارگذاری...</div>
                        </div>
                    </div>
                `);
            },
            complete: function() {
                 $('.music-grid').removeClass('loading');
            },
            success: function(response) {
                if (response.success) {
                    $('.music-grid').find('.music-card, .item, .card').addClass('music-card');
                    $('.music-grid').html(response.summary);
                    $('.pagination').html(response.pagination);
                    // به‌روزرسانی شمارنده نتایج
                    if (response.count !== undefined) {
                        $('.result-count').html(`
                            <span>${response.count} <span>  تا پیدا شد `);
                        if(response.count === 0) {
                            $('.result-count').addClass('no-result');
                        } else {
                            $('.result-count').removeClass('no-result');
                        }
                    }
                } else {
                    $('.music-grid').html('<div class="error-message">❌ خطا در بارگذاری موزیک‌ها</div>');
                }
            },
            error: function() {
                $('.music-grid').html('<div class="error-message">❌ خطا در ارتباط با سرور</div>');
            }
        });
    }
    
    // ارسال فرم با Ajax
    $('form.filters-section').on('submit', function(e) {
        e.preventDefault();
        loadMusicData();
    });
    // دکمه حذف فیلترها
    $('.btn-reset').on('click', function() {
        // ریست کردن تمام فیلدهای فرم
        $('form.filters-section')[0].reset();
        var currentUrl = window.location.href;
        var cleanUrl = currentUrl.split('?')[0];
        window.history.pushState({}, '', cleanUrl);
        $("#artist_input").val('all');
        $("#page_input").val('1');
        // تنظیم مجدد دسته بندی به all
        // $('#categories').val('all');
        // $('#categories').html(`<option>همه دسته بندی ها</option>`);
        // بارگذاری مجدد
        loadCategories();
        loadArtists();
        loadMusicData();
    });
    // جستجوی زنده (اختیاری - با تاخیر)
    let searchTimeout;
    $('input[name="search"]').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadMusicData();
        }, 500);
    });
    // تغییر در selectها با بارگذاری مجدد
    $('select').on('change', function() {
        loadMusicData();
    });
    loadCategories();
    function loadCategories(){
        $.ajax({
            type:"POST",
            url:"./ajax/category/get_categories.php",
            success:function(res){
                let html = '<option value="">همه دسته بندی ها</option>';
                if(res.success){
                    res.categories.forEach(cat => {
                        html += `<option value="${cat.ID}">${cat.title}</option>`;
                    });
                }
                $("#categories").html(html);
            }
        })
    }
    function loadArtists(){
        $.ajax({
            type:"POST",
            url:"./ajax/artist/get_artists.php",
            success:function(res){
                let html = '<option value="all">همه خواننده ها</option>';
                if(res.success){
                    res.artists.forEach(cat => {
                        html += `<option value="${cat.ID}">${cat.full_name}</option>`;
                    });
                }
                $("#artists").html(html);
            }
        })
    }
    loadArtists();
    $('#artists').on('change',function(e){
        e.preventDefault();
        $("#artist_input").val($(this).val());
        var currentUrl = window.location.href;
        var cleanUrl = currentUrl.split('?')[0];
        window.history.pushState({}, '', cleanUrl);
        loadMusicData();
    })
});

function updateDownloadCount(musicId) {
    $.ajax({
        type: "POST",
        url: './ajax/music/update_download.php',
        data: { music_id: musicId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $(`span[data-music-id="${musicId}"].download_count`).text(response.new_count);
            }
        }
    });
}

// لایک کردن
$(document).on('click', '.like-btn', function() {
    let musicId = $(this).data('music-id');
    let $btn = $(this);
    
    $.ajax({
        type: "POST",
        url: './ajax/music/like.php',
        data: { music_id: musicId },
        dataType: 'json',
        success: function(response) {
                if(response.status=='active'){
                    //  لایک
                    $btn.find('.like-icon').css('transform', 'scale(1.3)');
                    setTimeout(() => {
                        $btn.find('.like-icon').css('transform', 'scale(1)');
                    }, 200);
                    $(`.like_count_display[data-music-id="${musicId}"]`).text(response.new_count);
                    $btn.addClass('liked');
                }else{
                     $btn.find('.like-icon').css('transform', 'scale(0.7)');
                    setTimeout(() => {
                        $btn.find('.like-icon').css('transform', 'scale(1)');
                    }, 200);
                    $(`.like_count_display[data-music-id="${musicId}"]`).text(response.new_count);
                    $btn.removeClass('liked');
                }
                
                
                
            
        }
    });
});