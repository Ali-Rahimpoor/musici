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
   $(document).on('click', '.btn-remove-fav', function() {
    let musicId = $(this).data('music-id');
    let $btn = $(this);
    let music_cart = $(this).closest('.favorite-card');
    $.ajax({
        type: "POST",
        url: '../ajax/music/like.php',
        data: { music_id: musicId },
        dataType: 'json',
        success: function(response) {
                if(response.status=='active'){
                   toaster.success('موزیک اضافه شد');
                }else{
                   toaster.success('موزیک حذف شد');
                    music_cart.addClass('hidden');
                }                                                        
        }
    });
});

    
    // ============= تغییر حالت active منو بر اساس صفحه فعلی =============
    function setActiveMenuItem() {
        let currentPage = window.location.pathname.split('/').pop();
        $('.nav-item').removeClass('active');        
        $('.nav-item').each(function() {
            let $link = $(this);
            let href = $link.attr('href');     
            if(href === currentPage) {
                $(this).addClass('active');
            }
        });
        
        if(currentPage === '' || currentPage === 'index.php') {
            $('.nav-item[data-page="dashboard"]').addClass('active');
        }
    }
    
    // ============= بررسی نقش کاربر و نمایش بخش ادمین =============
    function checkAdminAccess() {
        $.ajax({
            type: "GET",
            url: "../ajax/panel/get_user_role.php",
            dataType: "json",
            success: function(res) {
                if(res.role === 'admin') {
                    $('#adminSection').show();
                    $('#userRoleDisplay').text('ادمین');
                } else {
                    $('#adminSection').hide();
                    $('#userRoleDisplay').text('کاربر عادی');
                }
            },
            error: function() {
                $('#adminSection').hide();
            }
        });
    }
    $(".publish-comment , .delete-comment").click(function(e){
        e.preventDefault();
        let comment_id = $(this).closest('.set-comment-status').data('comment-id');
        let url = '404';
        if($(this).hasClass('publish-comment')){    
             url = "../ajax/comment/publish.php";
        }
        else if($(this).hasClass('delete-comment')){
             url = "../ajax/comment/delete.php";
        }
        $.ajax({
            type:"POST",
            url:url,
            data:{comment_id},
            success:function(res){
                toaster.success('موفقیت',res.message);
            }
        })
        
    })
    // ============= خروج از حساب =============
    $('.logout-btn').on('click', function(e) {
        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "../ajax/panel/logout.php",
            data: { action: 'logout' },
            beforeSend: function() {
                $('.logout-btn').prop('disabled', true);
                $('.logout-btn').html('<span class="loading-spinner"></span> در حال خروج...');
            },
            success: function(res) {
                if(res.success) {
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 1000);
                } else {
                    toaster.error('خطا در خروج', 'error');
                }
            },
            error: function() {
                toaster.error('خطا در ارتباط با سرور', 'error');
            },
            complete: function() {
                $('.logout-btn').prop('disabled', false);
                $('.logout-btn').html('<span class="nav-icon">🚪</span><a href="#" class="nav-text">خروج از حساب</a>');
            }
        });
    });
   let croppie;
    $("#avatar").change(function(e){
        let file = e.target.files[0];
        // console.log(file);
        $('#cropper-wrapper').addClass('show');
        // $('.croppie').croppie({
        //     url: URL.createObjectURL(file)
        // });
        croppie = new Croppie($('.croppie')[0],{
            url : URL.createObjectURL(file),
            viewport:{
                width:256,
                height:256,
                type:'circle',
            }
        });
    })
    $('.close-croppie-modal').click( function(){
        $('#cropper-wrapper').removeClass('show');
        croppie.destroy();
    } );
     $('.crop-croppie').click(function(e){
        e.preventDefault();
        croppie.result('blob').then(function(cropped_image){
            // console.log(cropped_image);
            let form_data = new FormData();
                form_data.append('user_avatar',cropped_image,"avatar.png");
            if($('input.user_edit_id').length){
                form_data.append('user_id',$('input.user_edit_id').val());
            }
            $.ajax({
                type:"POST",
                url : "../ajax/user/upload-avatar.php",
                data:form_data,
                catch:false,
                contentType:false,
                processData:false,
                beforeSend:function(){
                },
                complete:function(){
                    $("#cropper-wrapper").removeClass('show');
                },
                success:function(res){
                    toaster.success('موفقیت',res.message);
                    console.log(res);
                    $('#user_avatar').attr('src',res.avatar);
                    
                },
                error:function(jqXHR){
                    console.log(jqXHR.responseJSON);
                },
            })
        })
    })
    $('#addCategoryForm').submit(function(e){
        e.preventDefault();
        let _this = $(this);
        $.ajax({
            type:"POST",
            data:_this.serialize(),
            url:'../ajax/category/save.php',
            success:function(res){
                toaster.success(res.message);
                $('#existingCategories').append(res.html);
            },
            error:function(jqXHR){
                let errorMessage = jqXHR.responseJSON.message;
                toaster.error(errorMessage);
            }
        })
    })
    $(".delete-cat").click(function(e){
        let btn = e.target;
        let id = $(btn).data('id');
        let parentTag = btn.closest('.category-tag');
        // console.log(id);
        $.ajax({
            data:{id:id},
            url:'../ajax/category/delete.php',
            type:"POST",
            beforeSend:function(){
            },
            success:function(res){
                toaster.success(res.message);
                   parentTag.remove();
                    if($('#existingCategories .category-tag').length === 0) {
                        $('#existingCategories').append('<div>دسته بندی یافت نشد</div>');
                    }
            },
             error: function(jqXHR){
                let errorMsg = jqXHR.responseJSON?.message || 'خطا در حذف دسته بندی';
                toaster.error(errorMsg);
                parentTag.css('opacity', '1');
            },
            complete: function() {
                if(parentTag.length) {
                    btn.html('✖').attr('disabled', false);
                }
            }
        })
        
    })
    $(".delete-artist").click(function(e){
    let btn = e.target;
    let id = $(btn).data('id');
    let parentTag = btn.closest('.category-tag');
    
    // console.log(id);
        $.ajax({
            data:{id:id},
            url:'../ajax/artist/delete.php',
            type:"POST",
            beforeSend:function(){
            },
            success:function(res){
                toaster.success(res.message);
                    parentTag.remove();
                    if($('#existingCategories .category-tag').length === 0) {
                        $('#existingCategories').append('<div>دسته بندی یافت نشد</div>');
                    }
            },
                error: function(jqXHR){
                let errorMsg = jqXHR.responseJSON?.message || 'خطا در حذف دسته بندی';
                toaster.error(errorMsg);
                parentTag.css('opacity', '1');
            },
            complete: function() {
                if(parentTag.length) {
                    btn.html('✖').attr('disabled', false);
                }
            }
        }) 
    })
    let musicFile = null;
    let imageFile = null;
    // ============= آپلودر موزیک =============
    $('#musicFile').on('change', function(e) {
        const file = e.target.files[0];
        if(file && file.type === 'audio/mpeg') {
            if(file.size <= 20 * 1024 * 1024) {
                musicFile = file;
                $('#musicFileName').text(file.name);
                $('#musicPreview').removeClass('hidden');
                $('#musicUploadArea .upload-content').addClass('hidden');
            } else {
                toaster.error('حجم فایل نباید بیشتر از 20 مگابایت باشد', 'error');
                $(this).val('');
            }
        } else {
            toaster.error('لطفا فایل MP3 انتخاب کنید', 'error');
            $(this).val('');
        }
    });
    
    // ============= آپلودر تصویر =============
    $('#imageFile').on('change', function(e) {
        const file = e.target.files[0];
        if(file && (file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image/jpg')) {
            if(file.size <= 20 * 1024 * 1024) {
                imageFile = file;
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImg').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
                $('#imagePreview').removeClass('hidden');
                $('#imageUploadArea .upload-content').addClass('hidden');
            } else {
                toaster.error('حجم عکس نباید بیشتر از 20 مگابایت باشد', 'error');
                $(this).val('');
            }
        } else {
            toaster.error('لطفا عکس با فرمت JPG یا PNG انتخاب کنید', 'error');
            $(this).val('');
        }
    });
    
    // ============= حذف فایل موزیک =============
    window.removeMusicFile = function() {
        musicFile = null;
        $('#musicFile').val('');
        $('#musicPreview').addClass('hidden');
        $('#musicUploadArea .upload-content').removeClass('hidden');
    };
    
    // ============= حذف فایل تصویر =============
    window.removeImageFile = function() {
        imageFile = null;
        $('#imageFile').val('');
        $('#imagePreview').addClass('hidden');
        $('#imageUploadArea .upload-content').removeClass('hidden');
    };
    
    // ============= دریافت دسته‌بندی‌ها از سرور =============
    function loadCategories() {
        $.ajax({
            type: "GET",
            url: "../ajax/category/get_categories.php",
            dataType: "json",
            success: function(res) {
                if(res.success && res.categories) {
                    let options = '<option value="">انتخاب دسته‌بندی</option>';
                    $.each(res.categories, function(index, cat) {
                        options += `<option value="${cat.ID}">${cat.title}</option>`;
                    });
                    $('#musicCategory').html(options);
                }
            },
            error: function() {
                toaster.error('خطا در دریافت دسته‌بندی‌ها', 'error');
            }
        });
    }
    
    // ============= بارگذاری دسته‌بندی‌ها =============
    loadCategories();
      function loadArtists() {
        $.ajax({
            type: "GET",
            url: "../ajax/artist/get_artists.php",
            dataType: "json",
            success: function(res) {
                if(res.success && res.artists) {
                    let options = '<option value="">انتخاب خواننده</option>';
                    $.each(res.artists, function(index, cat) {
                        options += `<option value="${cat.ID}">${cat.full_name}</option>`;
                    });
                    $('#musicArtist').html(options);
                }
            },
            error: function() {
                toaster.error('خطا در دریافت دسته‌بندی‌ها', 'error');
            }
        });
    }
    loadArtists();
    // ============= ارسال فرم =============
    $("#addMusicForm").submit(function(e){
        e.preventDefault();
        
        // اعتبارسنجی فیلدها
        let title = $('#musicName').val().trim();
        let content = $('#musicDescription').val().trim();
        let category = $('#musicCategory').val();
        let artist = $('#musicArtist').val();
        let year = $('#musicYear').val().trim();
        let music_id = $('#music_id').val();


       

        // validate
        if(!title) {
            toaster.error('لطفا نام آهنگ را وارد کنید', 'error');
            $('#musicName').focus();
            return;
        }
        
        if(!content) {
            toaster.error('لطفا توضیحات را وارد کنید', 'error');
            $('#musicDescription').focus();
            return;
        }
        
        if(!category) {
            toaster.error('لطفا دسته‌بندی را انتخاب کنید', 'error');
            return;
        }        
        if(!artist){
            toaster.error('لطفا خوانننده را انتخاب کنید', 'error');
            return;
        }
        if(!musicFile){
            toaster.error('لطفا موزیک را انتخاب کنید', 'error');
            return;
        }
        if(!imageFile){
            toaster.error('لطفا تصویر را انتخاب کنید ', 'error');
            return;
        }
        
        
        // ساخت FormData
        let formData = new FormData();
        formData.append('title', title);
        formData.append('content', content);
        formData.append('artist',artist);
        formData.append('category', category);
        formData.append('created_at', year);
        formData.append('music', musicFile);
        formData.append('image', imageFile);
        formData.append('music_id',music_id);
        
        let $btn = $('#submitMusicBtn');
        
        $.ajax({
            type: "POST",
            url: "../ajax/music/add_music.php",
            data: formData,
            contentType: false,
            processData: false,
            xhr: function() {
                let xhr = new XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if(e.lengthComputable) {
                        let percent = Math.round((e.loaded / e.total) * 100);
                        $('#musicProgress, #imageProgress').removeClass('hidden');
                        $('.progress-fill').css('width', percent + '%');
                        $('.progress-text').text(percent + '%');
                    }
                });
                return xhr;
            },
            beforeSend: function() {
                $btn.prop('disabled', true);
                $btn.html('<span class="loading-spinner"></span> در حال آپلود...');
            },
            success: function(res) {
                    
                toaster.success(res.message);                
                $('#addMusicForm')[0].reset();
                musicFile = null;
                imageFile = null;
                $('#musicPreview').addClass('hidden');
                $('#imagePreview').addClass('hidden');
                $('#musicUploadArea .upload-content').removeClass('hidden');
                $('#imageUploadArea .upload-content').removeClass('hidden');
                if(res.music_id){
                    setTimeout(() => {
                        window.location.href = window.location.pathname + '?action=edit&id=' + res.music_id;    
                    }, 2000);
                    
                }
                  
            },
            error: function(xhr) {
                let errorMsg = 'خطا در آپلود';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                toaster.error(errorMsg, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false);
                $btn.html('<span class="btn-icon">➕</span> اضافه کردن موزیک');
                setTimeout(function() {
                    $('#musicProgress, #imageProgress').addClass('hidden');
                    $('.progress-fill').css('width', '0%');
                    $('.progress-text').text('۰%');
                }, 1000);
            }
        });
    });
    $(window).on('load', function(e){
    let music_id = $('#music_id').val();
    if(music_id){
     if(music_id != 0){        
            if($('#hidden_music_url').val().length && musicFile===null){
                musicFile = $('#hidden_music_url').val();
            }
            if($('#hidden_music_cover').val().length && imageFile===null){
                imageFile = $('#hidden_music_cover').val();
            }
            // IMAGE
              $('#previewImg').attr('src', imageFile);
    
                $('#imagePreview').removeClass('hidden');
                $('#imageUploadArea .upload-content').addClass('hidden');
            // MUSIC
                $('#musicFileName').text(musicFile);
                $('#musicPreview').removeClass('hidden');
                
                $('#musicUploadArea .upload-content').addClass('hidden');
            // CATEGORY
                $(document).ajaxComplete(function() {
                    let category = $('#hidden_music_category').val();
                    if(category && category.length){
                            $('#musicCategory').val(category);
                    }
                });           
                $(document).ajaxComplete(function() {
                    let artist = $('#hidden_music_artist').val();
                    if(artist && artist.length){
                            $('#musicArtist').val(artist);
                    }
                });           
        };}
            
    });
    if($('#btn_delete_music')[0]){
        $('#btn_delete_music').click(function(e){
            e.preventDefault();
            let music_id = $('#btn_delete_music').data('id');
            $.ajax({
                type:"POST",
                url:"../ajax/music/delete.php",
                data:{music_id:music_id},
                success:function(res){                
                    toaster.success(res.message);
                    setTimeout(() => {
                         window.location.href = "/myphp/musici/panel/addmusic.php";
                    }, 2000);
                }
            })
        })
    }
    // ============= اجرا توابع =============
    setActiveMenuItem();
    checkAdminAccess();
});

function showToast(message, type) {
    return toaster.success(message);
}