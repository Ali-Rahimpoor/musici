$(document).ready(function() {
    // متغیرهای پلیر
    let currentAudio = null;
    let isPlaying = false;
    let currentButton = null;
    let updateInterval = null;
    
    // ساختار پلیر - با کلاس‌های یکتا برای جلوگیری از تداخل
    const playerStructure = `
        <div class="music-player-wrapper">
            <div class="music-player-container" id="globalMusicPlayer">
                <div class="player-main-row">
                    <div class="player-info">
                        <div class="player-song-title" id="playerSongTitle">انتخاب آهنگ</div>
                        <div class="player-song-artist" id="playerSongArtist">پخش کننده</div>
                    </div>
                    <div class="player-buttons">
                        <button class="player-btn player-close" id="playerCloseBtn" title="بستن">✕</button>
                        <button class="player-btn player-play-pause" id="playerPlayPauseBtn">▶</button>
                    </div>
                </div>
                <div class="player-progress-row">
                    <span class="player-time" id="playerCurrentTime">00:00</span>
                    <div class="player-progress-bar" id="playerProgressBar">
                        <div class="player-progress-fill" id="playerProgressFill"></div>
                    </div>
                    <span class="player-time" id="playerDuration">00:00</span>
                </div>
            </div>
        </div>
    `;
    
    // اضافه کردن پلیر به صفحه
    $('body').append(playerStructure);
    
    // انتخاب المنت‌ها
    const $playerContainer = $('#globalMusicPlayer');
    const $playerTitle = $('#playerSongTitle');
    const $playerArtist = $('#playerSongArtist');
    const $playPauseBtn = $('#playerPlayPauseBtn');
    const $closeBtn = $('#playerCloseBtn');
    const $progressBar = $('#playerProgressBar');
    const $progressFill = $('#playerProgressFill');
    const $currentTimeSpan = $('#playerCurrentTime');
    const $durationSpan = $('#playerDuration');
    
    // نمایش پلیر
    function showPlayer() {
        $playerContainer.addClass('active');
    }
    
    // مخفی کردن پلیر و توقف پخش
    function hidePlayer() {
        if (currentAudio) {
            currentAudio.pause();
            if (updateInterval) {
                clearInterval(updateInterval);
                updateInterval = null;
            }
            currentAudio = null;
        }
        $playerContainer.removeClass('active');
        isPlaying = false;
        currentButton = null;
        $playPauseBtn.text('▶');
        $progressFill.css('width', '0%');
        $currentTimeSpan.text('00:00');
        $durationSpan.text('00:00');
    }
    
    // فرمت زمان
    function formatTime(seconds) {
        if (isNaN(seconds)) return '00:00';
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    
    // دریافت اطلاعات آهنگ از کارت
    function getTrackInfo(buttonElement) {
        let title = 'آهنگ';
        let artist = 'در حال پخش';
        
        const $card = $(buttonElement).closest('.music-card');
        if ($card.length) {
            const titleElem = $card.find('.music-title');
            if (titleElem.length) title = titleElem.text().trim();            
        }
        
        return { title, artist };
    }
    
    // به‌روزرسانی نوار پیشرفت
    function updateProgress() {
        if (currentAudio && currentAudio.duration && isFinite(currentAudio.duration)) {
            const percent = (currentAudio.currentTime / currentAudio.duration) * 100;
            $progressFill.css('width', percent + '%');
            $currentTimeSpan.text(formatTime(currentAudio.currentTime));
        }
    }
    
    // شروع تایمر
    function startUpdateTimer() {
        if (updateInterval) clearInterval(updateInterval);
        updateInterval = setInterval(() => {
            if (currentAudio && !currentAudio.paused) {
                updateProgress();
                if (currentAudio.currentTime >= currentAudio.duration) {
                    // پایان آهنگ
                    hidePlayer();
                }
            } else if (currentAudio && currentAudio.paused) {
                updateProgress();
            }
        }, 200);
    }
    
    // تابع اصلی پخش موزیک
    function playThisMusic(musicUrl, buttonElement) {
        // اگر همان آهنگ در حال پخش است
        if (currentAudio && currentButton === buttonElement && !currentAudio.paused) {
            currentAudio.pause();
            isPlaying = false;
            $playPauseBtn.text('▶');
            return;
        }
        
        if (currentAudio && currentButton === buttonElement && currentAudio.paused) {
            currentAudio.play();
            isPlaying = true;
            $playPauseBtn.text('⏸');
            startUpdateTimer();
            return;
        }
        
        // آهنگ جدید
        if (currentAudio) {
            currentAudio.pause();
            if (updateInterval) clearInterval(updateInterval);
        }
        
        const trackInfo = getTrackInfo(buttonElement);
        $playerTitle.text(trackInfo.title);
        $playerArtist.text(trackInfo.artist);
        
        const audio = new Audio(musicUrl);
        currentAudio = audio;
        currentButton = buttonElement;
        
        audio.addEventListener('loadedmetadata', function() {
            if (audio.duration && isFinite(audio.duration)) {
                $durationSpan.text(formatTime(audio.duration));
            }
        });
        
        audio.addEventListener('timeupdate', function() {
            if (audio && !audio.paused) {
                const percent = (audio.currentTime / audio.duration) * 100;
                $progressFill.css('width', percent + '%');
                $currentTimeSpan.text(formatTime(audio.currentTime));
            }
        });
        
        audio.addEventListener('ended', function() {
            hidePlayer();
        });
        
        audio.addEventListener('error', function() {
            console.error('خطا در پخش');
            alert('خطا در پخش فایل صوتی');
            hidePlayer();
        });
        
        audio.play().then(() => {
            isPlaying = true;
            $playPauseBtn.text('⏸');
            showPlayer();
            startUpdateTimer();
        }).catch(err => {
            console.error('خطا در پخش:', err);
            alert('امکان پخش خودکار وجود ندارد. لطفا دوباره تلاش کنید.');
            hidePlayer();
        });
    }
    
    // رویدادهای پلیر
    $playPauseBtn.on('click', function() {
        if (!currentAudio) return;
        
        if (currentAudio.paused) {
            currentAudio.play();
            $playPauseBtn.text('⏸');
            startUpdateTimer();
        } else {
            currentAudio.pause();
            $playPauseBtn.text('▶');
        }
    });
    
    $closeBtn.on('click', function() {
        hidePlayer();
    });
    
    $progressBar.on('click', function(e) {
        if (!currentAudio || !currentAudio.duration) return;
        
        const rect = e.currentTarget.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const width = rect.width;
        const percent = clickX / width;
        const newTime = percent * currentAudio.duration;
        currentAudio.currentTime = newTime;
        updateProgress();
    });
    
    // رویداد برای دکمه‌های پخش (با Event Delegation برای محتوای داینامیک)
    $(document).on('click', '.play-now-btn', function(e) {
        e.preventDefault();
        const musicUrl = $(this).data('music-url');
        if (!musicUrl) {
            alert('آدرس فایل موسیقی موجود نیست!');
            return;
        }
        playThisMusic(musicUrl, this);
    });
    
   
});