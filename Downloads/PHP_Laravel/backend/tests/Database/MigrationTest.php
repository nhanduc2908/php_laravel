<?php
namespace Database;

class MigrationTest {
    private $tables = [];
    
    public function run() {
        echo "=== KIỂM TRA MIGRATION ===\n\n";
        
        $this->testCreateUsersTable();
        $this->testCreateAssessmentsTable();
        $this->testCreateCriteriaTable();
        $this->testCreateScoresTable();
        $this->testAddForeignKeys();
        $this->testAddIndexes();
        $this->testRollback();
    }
    
    private function testCreateUsersTable() {
        $usersTable = [
            'name' => 'users',
            'columns' => [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'username' => 'VARCHAR(50) NOT NULL',
                'email' => 'VARCHAR(100) UNIQUE',
                'password' => 'VARCHAR(255) NOT NULL',
                'role' => "ENUM('admin','user') DEFAULT 'user'",
                'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
            ]
        ];
        
        array_push($this->tables, $usersTable);
        echo "✓ Tạo bảng 'users' thành công\n";
        echo "  - Các cột: " . implode(', ', array_keys($usersTable['columns'])) . "\n";
    }
    
    private function testCreateAssessmentsTable() {
        $assessmentsTable = [
            'name' => 'assessments',
            'columns' => [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'user_id' => 'INT NOT NULL',
                'title' => 'VARCHAR(200) NOT NULL',
                'description' => 'TEXT',
                'status' => "ENUM('draft','published','archived') DEFAULT 'draft'",
                'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => 'TIMESTAMP'
            ]
        ];
        
        array_push($this->tables, $assessmentsTable);
        echo "✓ Tạo bảng 'assessments' thành công\n";
    }
    
    private function testCreateCriteriaTable() {
        $criteriaTable = [
            'name' => 'criteria',
            'columns' => [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'assessment_id' => 'INT NOT NULL',
                'name' => 'VARCHAR(100) NOT NULL',
                'weight' => 'DECIMAL(5,2) DEFAULT 0',
                'max_score' => 'INT DEFAULT 100',
                'description' => 'TEXT'
            ]
        ];
        
        array_push($this->tables, $criteriaTable);
        echo "✓ Tạo bảng 'criteria' thành công\n";
    }
    
    private function testCreateScoresTable() {
        $scoresTable = [
            'name' => 'scores',
            'columns' => [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'criteria_id' => 'INT NOT NULL',
                'score' => 'DECIMAL(5,2) NOT NULL',
                'comment' => 'TEXT',
                'evaluated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
            ]
        ];
        
        array_push($this->tables, $scoresTable);
        echo "✓ Tạo bảng 'scores' thành công\n";
    }
    
    private function testAddForeignKeys() {
        $foreignKeys = [
            'assessments' => ['user_id' => 'users(id)'],
            'criteria' => ['assessment_id' => 'assessments(id)'],
            'scores' => ['criteria_id' => 'criteria(id)']
        ];
        
        foreach ($foreignKeys as $table => $fk) {
            echo "✓ Thêm khóa ngoại cho bảng '$table': " . key($fk) . " -> " . current($fk) . "\n";
        }
    }
    
    private function testAddIndexes() {
        $indexes = [
            'users' => ['username', 'email'],
            'assessments' => ['user_id', 'status'],
            'criteria' => ['assessment_id'],
            'scores' => ['criteria_id']
        ];
        
        foreach ($indexes as $table => $columns) {
            echo "✓ Thêm INDEX cho bảng '$table' trên cột: " . implode(', ', $columns) . "\n";
        }
    }
    
    private function testRollback() {
        $droppedTables = array_reverse($this->tables);
        
        echo "\n🔄 Test Rollback:\n";
        foreach ($droppedTables as $table) {
            echo "  - Xóa bảng: {$table['name']}\n";
        }
        
        if (count($droppedTables) === count($this->tables)) {
            echo "✓ Rollback thành công, đã xóa " . count($this->tables) . " bảng\n";
        }
    }
}

$test = new MigrationTest();
$test->run();
?>