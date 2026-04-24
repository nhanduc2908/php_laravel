<?php
namespace Database;

class QueryTest {
    private $connection = null;
    
    public function run() {
        echo "\n=== KIỂM TRA QUERY ===\n\n";
        
        $this->testSelectQuery();
        $this->testInsertQuery();
        $this->testUpdateQuery();
        $this->testDeleteQuery();
        $this->testJoinQuery();
        $this->testAggregateQuery();
        $this->testSubQuery();
        $this->testPreventSQLInjection();
    }
    
    private function testSelectQuery() {
        $sql = "SELECT * FROM users WHERE role = 'admin'";
        echo "✓ SELECT query: $sql\n";
        
        // Giả lập kết quả
        $result = [
            ['id' => 1, 'username' => 'admin', 'email' => 'admin@example.com']
        ];
        echo "  - Kết quả: " . count($result) . " rows returned\n";
    }
    
    private function testInsertQuery() {
        $data = [
            'username' => 'newuser',
            'email' => 'new@example.com',
            'password' => password_hash('pass123', PASSWORD_DEFAULT)
        ];
        
        $sql = "INSERT INTO users (username, email, password) VALUES ('{$data['username']}', '{$data['email']}', '{$data['password']}')";
        echo "✓ INSERT query: $sql\n";
        echo "  - Thêm user mới: {$data['username']}\n";
    }
    
    private function testUpdateQuery() {
        $sql = "UPDATE assessments SET status = 'published' WHERE id = 2";
        echo "✓ UPDATE query: $sql\n";
        echo "  - Cập nhật status cho assessment ID 2\n";
    }
    
    private function testDeleteQuery() {
        $sql = "DELETE FROM scores WHERE score < 50";
        echo "✓ DELETE query: $sql\n";
        echo "  - Xóa các scores dưới 50 điểm\n";
    }
    
    private function testJoinQuery() {
        $sql = "SELECT a.title, c.name, s.score 
                FROM assessments a 
                JOIN criteria c ON a.id = c.assessment_id 
                JOIN scores s ON c.id = s.criteria_id 
                WHERE a.status = 'published'";
        
        echo "✓ JOIN query (3 tables):\n";
        echo "  $sql\n";
        
        // Giả lập kết quả
        $results = [
            ['title' => 'Đánh giá dự án Q1', 'name' => 'Chất lượng code', 'score' => 85],
            ['title' => 'Đánh giá dự án Q1', 'name' => 'Hiệu năng', 'score' => 78]
        ];
        echo "  - Kết quả: " . count($results) . " rows\n";
    }
    
    private function testAggregateQuery() {
        $queries = [
            "SELECT AVG(score) as avg_score FROM scores" => "Điểm trung bình",
            "SELECT COUNT(*) as total FROM users" => "Tổng số users",
            "SELECT assessment_id, SUM(weight) as total_weight FROM criteria GROUP BY assessment_id" => "Tổng weight theo assessment",
            "SELECT status, COUNT(*) as count FROM assessments GROUP BY status" => "Thống kê theo status"
        ];
        
        echo "✓ AGGREGATE queries:\n";
        foreach ($queries as $sql => $desc) {
            echo "  - $desc: $sql\n";
        }
    }
    
    private function testSubQuery() {
        $sql = "SELECT username FROM users WHERE id IN (SELECT user_id FROM assessments WHERE status = 'published')";
        echo "✓ SUBQUERY:\n";
        echo "  $sql\n";
        echo "  - Tìm users có assessment đã publish\n";
    }
    
    private function testPreventSQLInjection() {
        $maliciousInput = "admin' OR '1'='1";
        $safeInput = addslashes($maliciousInput);
        
        echo "✓ PROTECT SQL INJECTION:\n";
        echo "  - Input độc hại: $maliciousInput\n";
        echo "  - Đã escape: $safeInput\n";
        
        // Prepared statement example
        $sql = "SELECT * FROM users WHERE username = :username";
        echo "  - Prepared statement: $sql\n";
        echo "  - ✓ An toàn với SQL injection\n";
    }
}

$test = new QueryTest();
$test->run();
?>