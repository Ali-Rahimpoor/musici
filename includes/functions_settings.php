<?php
// functions/settings.php
require_once dirname(__DIR__) . '/config.php';

class SiteSettings {
    private $db;
    private $settings = [];
    
    public function __construct() {
        $this->db = $this->getConnection();
        $this->loadSettings();
    }
    
    private function getConnection() {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            return $conn;
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }
    
    private function loadSettings() {
        $sql = "SELECT setting_key, setting_value FROM site_settings";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->settings[$row['setting_key']] = $row['setting_value'];
            }
        }
    }
    
    public function get($key, $default = null) {
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }
    
    public function getAll() {
        return $this->settings;
    }
    
    public function update($key, $value) {
        $key = $this->db->real_escape_string($key);
        $value = $this->db->real_escape_string($value);
        
        $sql = "INSERT INTO site_settings (setting_key, setting_value) 
                VALUES ('$key', '$value') 
                ON DUPLICATE KEY UPDATE setting_value = '$value'";
        
        if ($this->db->query($sql)) {
            $this->settings[$key] = $value;
            
            // بازنویسی فایل config.php با تنظیمات جدید
            $this->updateConfigFile();
            return true;
        }
        return false;
    }
    
    public function updateMultiple($settings) {
        $this->db->begin_transaction();
        
        try {
            foreach ($settings as $key => $value) {
                if (!$this->update($key, $value)) {
                    throw new Exception("Failed to update: $key");
                }
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    private function updateConfigFile() {
        $configPath = dirname(__DIR__) . '/config.php';
        
        if (!is_writable($configPath)) {
            error_log("Config file is not writable: " . $configPath);
            return false;
        }
        
        // خواندن فایل config فعلی
        $configContent = file_get_contents($configPath);
        
        // به‌روزرسانی تعاریف ثابت‌ها
        $configContent = preg_replace(
            "/define\('SITE_NAME',\s*'.*?'\);/",
            "define('SITE_NAME', '" . addslashes($this->get('site_name')) . "');",
            $configContent
        );
        
        $configContent = preg_replace(
            "/define\('SITE_DESCRIPTION',\s*'.*?'\);/",
            "define('SITE_DESCRIPTION', '" . addslashes($this->get('site_description')) . "');",
            $configContent
        );
        
        $configContent = preg_replace(
            "/define\('OTP_DURATION',\s*\d+\);/",
            "define('OTP_DURATION', " . intval($this->get('otp_duration', 120)) . ");",
            $configContent
        );
        
        $configContent = preg_replace(
            "/define\('OTP_LENGTH',\s*\d+\);/",
            "define('OTP_LENGTH', " . intval($this->get('otp_length', 4)) . ");",
            $configContent
        );
        
        return file_put_contents($configPath, $configContent);
    }
}

// تابع کمکی برای استفاده آسان در سراسر سایت
function get_setting($key, $default = null) {
    static $settings = null;
    
    if ($settings === null) {
        $settings = new SiteSettings();
    }
    
    return $settings->get($key, $default);
}

// ایجاد نمونه برای استفاده در صفحه تنظیمات
$siteSettings = new SiteSettings();
?>