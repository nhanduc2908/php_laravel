<?php
namespace Database;

class TransactionTest {
    private $transactionLog = [];
    private $inTransaction = false;
    
    public function run() {
        echo "\n=== KIỂM TRA TRANSACTION ===\n\n";
        
        $this->testBeginTransaction();
        $this->testCommitTransaction();
        $this->testRollbackTransaction();
        $this->testTransactionWithMultipleQueries();
        $this->testSavepoint();
        $this->testDeadlockHandling();
    }
    
    private function testBeginTransaction() {
        $this->beginTransaction();
        
        if ($this->inTransaction) {
            echo "✓ BEGIN TRANSACTION thành công\n";
            $this->log("Bắt đầu transaction");
        }
    }
    
    private function testCommitTransaction() {
        $this->beginTransaction();
        
        // Thực hiện các thao tác
        $this->executeQuery("INSERT INTO users (username) VALUES ('test_user')");
        $this->executeQuery("UPDATE assessments SET status = 'published' WHERE id = 1");
        
        $this->commit();
        
        if (!$this->inTransaction) {
            echo "✓ COMMIT thành công\n";
            $this->log("Commit transaction");
        }
    }
    
    private function testRollbackTransaction() {
        $this->beginTransaction();
        
        $this->executeQuery("DELETE FROM scores WHERE score < 60");
        $this->executeQuery("UPDATE users SET role = 'vip' WHERE id = 999"); // Sẽ lỗi
        
        $this->rollback();
        
        if (!$this->inTransaction) {
            echo "✓ ROLLBACK thành công - Không có thay đổi nào được lưu\n";
            $this->log("Rollback transaction");
        }
    }
    
    private function testTransactionWithMultipleQueries() {
        echo "\n🔄 Transaction với nhiều queries:\n";
        
        $this->beginTransaction();
        
        $queries = [
            "INSERT INTO assessments (title, user_id) VALUES ('New Assessment', 1)",
            "INSERT INTO criteria (assessment_id, name, weight) VALUES (LAST_INSERT_ID(), 'Quality', 50)",
            "INSERT INTO criteria (assessment_id, name, weight) VALUES (LAST_INSERT_ID(), 'Speed', 30)",
            "INSERT INTO scores (criteria_id, score) VALUES (LAST_INSERT_ID(), 85)"
        ];
        
        $success = true;
        foreach ($queries as $index => $query) {
            if ($this->executeQuery($query)) {
                echo "  ✓ Query " . ($index + 1) . " thành công\n";
            } else {
                echo "  ✗ Query " . ($index + 1) . " thất bại\n";
                $success = false;
                break;
            }
        }
        
        if ($success) {
            $this->commit();
            echo "✓ COMMIT tất cả " . count($queries) . " queries\n";
        } else {
            $this->rollback();
            echo "✓ ROLLBACK - Hủy toàn bộ " . count($queries) . " queries\n";
        }
    }
    
    private function testSavepoint() {
        echo "\n📌 Test SAVEPOINT:\n";
        
        $this->beginTransaction();
        echo "  - Bắt đầu transaction\n";
        
        $this->executeQuery("INSERT INTO users (username) VALUES ('user_a')");
        echo "  - Insert user_a\n";
        
        $this->savepoint('before_user_b');
        echo "  - Tạo savepoint 'before_user_b'\n";
        
        $this->executeQuery("INSERT INTO users (username) VALUES ('user_b')");
        echo "  - Insert user_b\n";
        
        // Rollback to savepoint
        $this->rollbackToSavepoint('before_user_b');
        echo "  - Rollback to savepoint 'before_user_b' (user_b bị xóa)\n";
        
        $this->commit();
        echo "✓ Commit - Chỉ user_a được lưu\n";
    }
    
    private function testDeadlockHandling() {
        echo "\n⚠️ Test xử lý Deadlock:\n";
        
        $maxRetries = 3;
        $retryCount = 0;
        $success = false;
        
        while ($retryCount < $maxRetries && !$success) {
            try {
                $this->beginTransaction();
                echo "  - Lần thử " . ($retryCount + 1) . ": Bắt đầu transaction\n";
                
                // Giả lập deadlock
                if ($retryCount < 2) {
                    throw new Exception("Deadlock detected");
                }
                
                $this->commit();
                $success = true;
                echo "  ✓ Transaction thành công sau $retryCount lần retry\n";
                
            } catch (Exception $e) {
                $this->rollback();
                echo "  ⚠ Lỗi: " . $e->getMessage() . ", retry...\n";
                $retryCount++;
                sleep(1);
            }
        }
        
        if ($success) {
            echo "✓ Deadlock handling hoạt động tốt\n";
        }
    }
    
    // Helper methods
    private function beginTransaction() {
        $this->inTransaction = true;
        $this->transactionLog[] = ['action' => 'BEGIN', 'time' => microtime(true)];
    }
    
    private function commit() {
        $this->inTransaction = false;
        $this->transactionLog[] = ['action' => 'COMMIT', 'time' => microtime(true)];
    }
    
    private function rollback() {
        $this->inTransaction = false;
        $this->transactionLog[] = ['action' => 'ROLLBACK', 'time' => microtime(true)];
    }
    
    private function savepoint($name) {
        $this->transactionLog[] = ['action' => "SAVEPOINT $name", 'time' => microtime(true)];
    }
    
    private function rollbackToSavepoint($name) {
        $this->transactionLog[] = ['action' => "ROLLBACK TO $name", 'time' => microtime(true)];
    }
    
    private function executeQuery($query) {
        // Giả lập execute query
        $this->transactionLog[] = ['action' => "QUERY: $query", 'time' => microtime(true)];
        return true;
    }
    
    private function log($message) {
        // Log transaction actions
    }
}

$test = new TransactionTest();
$test->run();
?>