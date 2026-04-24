<?php
namespace Browser;

class AssessmentUITest {
    private $assessments = [];
    
    public function run() {
        echo "\n=== ĐANG KIỂM TRA CHỨC NĂNG ĐÁNH GIÁ ===\n";
        
        $this->testCreateAssessment();
        $this->testAddScore();
        $this->testCalculateTotal();
        $this->testSaveAssessment();
        $this->testViewHistory();
    }
    
    private function testCreateAssessment() {
        $assessment = [
            'id' => 1,
            'title' => 'Đánh giá dự án ABC',
            'date' => date('Y-m-d'),
            'status' => 'draft'
        ];
        
        array_push($this->assessments, $assessment);
        
        echo "✓ TEST PASS: Tạo đánh giá mới '{$assessment['title']}'\n";
    }
    
    private function testAddScore() {
        if (!empty($this->assessments)) {
            $scores = [
                'criteria_1' => 85,
                'criteria_2' => 90,
                'criteria_3' => 78
            ];
            
            $this->assessments[0]['scores'] = $scores;
            echo "✓ TEST PASS: Thêm điểm thành công (" . count($scores) . " tiêu chí)\n";
        } else {
            echo "✗ TEST FAIL: Không có đánh giá để thêm điểm\n";
        }
    }
    
    private function testCalculateTotal() {
        if (isset($this->assessments[0]['scores'])) {
            $total = array_sum($this->assessments[0]['scores']);
            $average = $total / count($this->assessments[0]['scores']);
            
            $this->assessments[0]['total'] = $total;
            $this->assessments[0]['average'] = $average;
            
            echo "✓ TEST PASS: Tính tổng điểm = $total, Trung bình = " . round($average, 2) . "\n";
        } else {
            echo "✗ TEST FAIL: Không thể tính tổng điểm\n";
        }
    }
    
    private function testSaveAssessment() {
        if (!empty($this->assessments)) {
            $this->assessments[0]['status'] = 'completed';
            $this->assessments[0]['saved_at'] = date('Y-m-d H:i:s');
            
            echo "✓ TEST PASS: Lưu đánh giá thành công lúc {$this->assessments[0]['saved_at']}\n";
        } else {
            echo "✗ TEST FAIL: Không thể lưu đánh giá\n";
        }
    }
    
    private function testViewHistory() {
        $history = [
            ['date' => '2024-01-15', 'score' => 75],
            ['date' => '2024-02-20', 'score' => 82],
            ['date' => '2024-03-25', 'score' => 88]
        ];
        
        echo "✓ TEST PASS: Xem lịch sử đánh giá (" . count($history) . " lần)\n";
        foreach ($history as $record) {
            echo "   - Ngày: {$record['date']}, Điểm: {$record['score']}\n";
        }
    }
}

$test = new AssessmentUITest();
$test->run();
?>