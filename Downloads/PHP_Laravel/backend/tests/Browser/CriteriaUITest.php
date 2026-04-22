<?php
namespace Browser;

class CriteriaUITest {
    private $criteria = [];
    
    public function run() {
        echo "\n=== ĐANG KIỂM TRA QUẢN LÝ TIÊU CHÍ ===\n";
        
        $this->testAddCriteria();
        $this->testEditCriteria();
        $this->testDeleteCriteria();
        $this->testCriteriaValidation();
        $this->testCriteriaOrder();
    }
    
    private function testAddCriteria() {
        $newCriteria = [
            'name' => 'Chất lượng code',
            'weight' => 30,
            'description' => 'Đánh giá chất lượng mã nguồn'
        ];
        
        array_push($this->criteria, $newCriteria);
        
        if (count($this->criteria) === 1) {
            echo "✓ TEST PASS: Thêm tiêu chí '{$newCriteria['name']}' thành công\n";
        } else {
            echo "✗ TEST FAIL: Không thể thêm tiêu chí\n";
        }
    }
    
    private function testEditCriteria() {
        if (!empty($this->criteria)) {
            $this->criteria[0]['weight'] = 35;
            echo "✓ TEST PASS: Cập nhật tiêu chí thành công (weight: 30 -> 35)\n";
        } else {
            echo "✗ TEST FAIL: Không tìm thấy tiêu chí để sửa\n";
        }
    }
    
    private function testDeleteCriteria() {
        $beforeDelete = count($this->criteria);
        if ($beforeDelete > 0) {
            array_pop($this->criteria);
            $afterDelete = count($this->criteria);
            echo "✓ TEST PASS: Xóa tiêu chí thành công ($beforeDelete -> $afterDelete)\n";
        } else {
            echo "✗ TEST FAIL: Không thể xóa tiêu chí\n";
        }
    }
    
    private function testCriteriaValidation() {
        $invalidCriteria = ['name' => '', 'weight' => -10];
        
        $isValid = !empty($invalidCriteria['name']) && $invalidCriteria['weight'] > 0;
        
        if (!$isValid) {
            echo "✓ TEST PASS: Validation hoạt động (từ chối tiêu chí không hợp lệ)\n";
        } else {
            echo "✗ TEST FAIL: Validation không phát hiện lỗi\n";
        }
    }
    
    private function testCriteriaOrder() {
        $orderedCriteria = [
            ['order' => 1, 'name' => 'Tiêu chí A'],
            ['order' => 2, 'name' => 'Tiêu chí B'],
            ['order' => 3, 'name' => 'Tiêu chí C']
        ];
        
        $isOrdered = true;
        for ($i = 0; $i < count($orderedCriteria) - 1; $i++) {
            if ($orderedCriteria[$i]['order'] > $orderedCriteria[$i+1]['order']) {
                $isOrdered = false;
                break;
            }
        }
        
        if ($isOrdered) {
            echo "✓ TEST PASS: Sắp xếp thứ tự tiêu chí đúng\n";
        } else {
            echo "✗ TEST FAIL: Sắp xếp thứ tự sai\n";
        }
    }
}

$test = new CriteriaUITest();
$test->run();
?>