<?php
namespace Browser;

class ResponsiveTest {
    private $viewports = [
        'desktop' => ['width' => 1920, 'height' => 1080],
        'laptop' => ['width' => 1366, 'height' => 768],
        'tablet' => ['width' => 768, 'height' => 1024],
        'mobile' => ['width' => 375, 'height' => 667]
    ];
    
    public function run() {
        echo "\n=== ĐANG KIỂM TRA RESPONSIVE ===\n";
        
        foreach ($this->viewports as $device => $dimensions) {
            $this->testViewport($device, $dimensions);
        }
        
        $this->testTouchGestures();
        $this->testOrientationChange();
    }
    
    private function testViewport($device, $dimensions) {
        $isResponsive = $this->checkResponsive($dimensions['width']);
        
        if ($isResponsive) {
            echo "✓ TEST PASS: {$device} ({$dimensions['width']}x{$dimensions['height']}) - Hiển thị tốt\n";
        } else {
            echo "✗ TEST FAIL: {$device} - Lỗi hiển thị\n";
        }
    }
    
    private function checkResponsive($width) {
        // Giả lập kiểm tra responsive
        $cssRules = [
            'desktop' => ['min-width' => 1201],
            'tablet' => ['min-width' => 769, 'max-width' => 1200],
            'mobile' => ['max-width' => 768]
        ];
        
        if ($width <= 768) {
            return true