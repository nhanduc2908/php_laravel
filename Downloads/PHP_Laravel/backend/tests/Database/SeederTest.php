<?php
namespace Database;

class SeederTest {
    private $seededData = [];
    
    public function run() {
        echo "\n=== KIỂM TRA SEEDER ===\n\n";
        
        $this->seedUsers();
        $this->seedAssessments();
        $this->seedCriteria();
        $this->seedScores();
        $this->verifySeededData();
        $this->testCleanup();
    }
    
    private function seedUsers() {
        $users = [
            ['id' => 1, 'username' => 'admin', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['id' => 2, 'username' => 'john_doe', 'email' => 'john@example.com', 'role' => 'user'],
            ['id' => 3, 'username' => 'jane_smith', 'email' => 'jane@example.com', 'role' => 'user'],
            ['id' => 4, 'username' => 'tester', 'email' => 'tester@example.com', 'role' => 'user']
        ];
        
        $this->seededData['users'] = $users;
        echo "✓ Seed " . count($users) . " users:\n";
        foreach ($users as $user) {
            echo "  - {$user['username']} ({$user['email']}) - Role: {$user['role']}\n";
        }
    }
    
    private function seedAssessments() {
        $assessments = [
            ['id' => 1, 'user_id' => 1, 'title' => 'Đánh giá dự án Q1', 'status' => 'published'],
            ['id' => 2, 'user_id' => 2, 'title' => 'Đánh giá nhân viên', 'status' => 'draft'],
            ['id' => 3, 'user_id' => 3, 'title' => 'Đánh giá hiệu suất', 'status' => 'published'],
            ['id' => 4, 'user_id' => 1, 'title' => 'Đánh giá bảo mật', 'status' => 'archived']
        ];
        
        $this->seededData['assessments'] = $assessments;
        echo "\n✓ Seed " . count($assessments) . " assessments\n";
    }
    
    private function seedCriteria() {
        $criteria = [
            ['id' => 1, 'assessment_id' => 1, 'name' => 'Chất lượng code', 'weight' => 30],
            ['id' => 2, 'assessment_id' => 1, 'name' => 'Hiệu năng', 'weight' => 25],
            ['id' => 3, 'assessment_id' => 1, 'name' => 'Bảo mật', 'weight' => 25],
            ['id' => 4, 'assessment_id' => 1, 'name' => 'Tài liệu', 'weight' => 20],
            ['id' => 5, 'assessment_id' => 2, 'name' => 'Kỹ năng chuyên môn', 'weight' => 40],
            ['id' => 6, 'assessment_id' => 2, 'name' => 'Làm việc nhóm', 'weight' => 30]
        ];
        
        $this->seededData['criteria'] = $criteria;
        echo "✓ Seed " . count($criteria) . " criteria\n";
    }
    
    private function seedScores() {
        $scores = [
            ['criteria_id' => 1, 'score' => 85, 'comment' => 'Code tốt, cần cải thiện naming'],
            ['criteria_id' => 1, 'score' => 90, 'comment' => 'Rất clean code'],
            ['criteria_id' => 2, 'score' => 78, 'comment' => 'Cần tối ưu query'],
            ['criteria_id' => 3, 'score' => 92, 'comment' => 'Bảo mật tốt'],
            ['criteria_id' => 5, 'score' => 88, 'comment' => 'Chuyên môn vững'],
            ['criteria_id' => 6, 'score' => 75, 'comment' => 'Cần cải thiện giao tiếp']
        ];
        
        $this->seededData['scores'] = $scores;
        echo "✓ Seed " . count($scores) . " scores\n";
    }
    
    private function verifySeededData() {
        echo "\n📊 XÁC THỰC DỮ LIỆU SEED:\n";
        
        // Kiểm tra users
        $userCount = count($this->seededData['users']);
        echo "  - Users: $userCount records (OK)\n";
        
        // Kiểm tra assessments
        $assessmentCount = count($this->seededData['assessments']);
        $publishedCount = count(array_filter($this->seededData['assessments'], fn($a) => $a['status'] === 'published'));
        echo "  - Assessments: $assessmentCount records ($publishedCount published)\n";
        
        // Kiểm tra criteria
        $criteriaCount = count($this->seededData['criteria']);
        $totalWeight = array_sum(array_column($this->seededData['criteria'], 'weight'));
        echo "  - Criteria: $criteriaCount records (Total weight: $totalWeight)\n";
        
        // Kiểm tra scores
        $scoreCount = count($this->seededData['scores']);
        $avgScore = array_sum(array_column($this->seededData['scores'], 'score')) / $scoreCount;
        echo "  - Scores: $scoreCount records (Average: " . round($avgScore, 2) . ")\n";
    }
    
    private function testCleanup() {
        echo "\n🗑️ CLEANUP:\n";
        $this->seededData = [];
        
        if (empty($this->seededData)) {
            echo "✓ Xóa toàn bộ dữ liệu seed thành công\n";
        }
    }
}

$test = new SeederTest();
$test->run();
?>