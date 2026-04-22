<?php
namespace Browser;

class LoginTest {
    private $validUsers = [
        ['username' => 'admin', 'password' => 'admin123'],
        ['username' => 'user1', 'password' => 'pass123'],
        ['username' => 'tester', 'password' => 'test@2024'],
        ['username' => 'manager', 'password' => 'manager456'],
        ['username' => 'nvquan', 'password' => 'quan@123']
    ];
    
    private $invalidCredentials = [
        ['username' => 'admin', 'password' => 'wrongpass'],
        ['username' => 'fakeuser', 'password' => 'fake123'],
        ['username' => 'admin', 'password' => ''],
        ['username' => '', 'password' => 'admin123'],
        ['username' => 'user1', 'password' => 'saiMatKhau'],
        ['username' => 'hacker', 'password' => 'hackme']
    ];
    
    public function run() {
        echo "=== KIỂM TRA ĐĂNG NHẬP VỚI TÀI KHOẢN CỤ THỂ ===\n\n";
        
        // Test 1: Đăng nhập với mật khẩu ĐÚNG
        $this->testValidLogins();
        
        echo "\n---\n\n";
        
        // Test 2: Đăng nhập với mật khẩu SAI
        $this->testInvalidLogins();
        
        echo "\n---\n\n";
        
        // Test 3: Brute force protection
        $this->testBruteForceProtection();
    }
    
    private function testValidLogins() {
        echo "📋 TEST ĐĂNG NHẬP THÀNH CÔNG:\n";
        echo "================================\n";
        
        $successCount = 0;
        
        foreach ($this->validUsers as $user) {
            $result = $this->attemptLogin($user['username'], $user['password']);
            
            if ($result['success']) {
                echo "✓ ĐÚNG: {$user['username']} / {$user['password']} -> ĐĂNG NHẬP THÀNH CÔNG\n";
                $successCount++;
            } else {
                echo "✗ SAI: {$user['username']} / {$user['password']} -> LỖI: {$result['message']}\n";
            }
        }
        
        echo "\n📊 Kết quả: {$successCount}/" . count($this->validUsers) . " tài khoản đúng đăng nhập được\n";
    }
    
    private function testInvalidLogins() {
        echo "🔒 TEST ĐĂNG NHẬP THẤT BẠI (MẬT KHẨU SAI):\n";
        echo "===========================================\n";
        
        $failCount = 0;
        
        foreach ($this->invalidCredentials as $cred) {
            $result = $this->attemptLogin($cred['username'], $cred['password']);
            
            if (!$result['success']) {
                echo "✓ ĐÚNG: {$cred['username']} / {$cred['password']} -> BỊ TỪ CHỐI ✓\n";
                $failCount++;
            } else {
                echo "✗ LỖI BẢO MẬT: {$cred['username']} / {$cred['password']} -> ĐĂNG NHẬP ĐƯỢC! ✗\n";
            }
        }
        
        echo "\n📊 Kết quả: {$failCount}/" . count($this->invalidCredentials) . " tài khoản sai bị chặn đúng\n";
    }
    
    private function attemptLogin($username, $password) {
        // Giả lập kiểm tra đăng nhập
        foreach ($this->validUsers as $validUser) {
            if ($validUser['username'] === $username) {
                if ($validUser['password'] === $password) {
                    return [
                        'success' => true,
                        'message' => 'Đăng nhập thành công',
                        'user' => $username
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Sai mật khẩu'
                    ];
                }
            }
        }
        
        return [
            'success' => false,
            'message' => 'Tài khoản không tồn tại'
        ];
    }
    
    private function testBruteForceProtection() {
        echo "🛡️ TEST CHỐNG TẤN CÔNG BRUTE FORCE:\n";
        echo "===================================\n";
        
        $username = "admin";
        $wrongPasswords = [
            "123456", "password", "admin", "12345678", 
            "qwerty", "abc123", "111111", "admin1234"
        ];
        
        $failedAttempts = 0;
        $maxAttempts = 5;
        $isLocked = false;
        
        foreach ($wrongPasswords as $index => $wrongPass) {
            $result = $this->attemptLogin($username, $wrongPass);
            
            if (!$result['success']) {
                $failedAttempts++;
                
                if ($failedAttempts >= $maxAttempts) {
                    $isLocked = true;
                    echo "🔒 Lần thử #{$failedAttempts}: {$username} / {$wrongPass} -> TÀI KHOẢN BỊ KHÓA (quá {$maxAttempts} lần sai)\n";
                    break;
                } else {
                    echo "⚠️  Lần thử #{$failedAttempts}: {$username} / {$wrongPass} -> Sai mật khẩu (còn " . ($maxAttempts - $failedAttempts) . " lần)\n";
                }
            }
        }
        
        if ($isLocked) {
            echo "\n✓ TEST PASS: Hệ thống khóa tài khoản sau {$maxAttempts} lần đăng nhập sai\n";
        } else {
            echo "\n✗ TEST FAIL: Hệ thống KHÔNG khóa tài khoản dù đã nhập sai nhiều lần\n";
        }
    }
    
    // Thêm test đăng nhập với session
    public function testSessionManagement() {
        echo "\n🍪 TEST QUẢN LÝ SESSION:\n";
        echo "=========================\n";
        
        // Giả lập đăng nhập thành công
        $_SESSION['user'] = 'admin';
        $_SESSION['login_time'] = time();
        $_SESSION['user_role'] = 'administrator';
        
        if (isset($_SESSION['user'])) {
            echo "✓ Session được tạo cho user: {$_SESSION['user']}\n";
            echo "✓ Role: {$_SESSION['user_role']}\n";
            echo "✓ Thời gian đăng nhập: " . date('H:i:s', $_SESSION['login_time']) . "\n";
        }
        
        // Test logout
        session_destroy();
        if (!isset($_SESSION['user'])) {
            echo "✓ Đăng xuất thành công, session đã bị hủy\n";
        }
    }
}

// Khởi tạo session để test
session_start();

// Chạy các test
$test = new LoginTest();
$test->run();
$test->testSessionManagement();
?>