<?php
namespace Database;

class RelationshipTest {
    private $data = [];
    
    public function run() {
        echo "\n=== KIỂM TRA RELATIONSHIP ===\n\n";
        
        $this->testOneToOne();
        $this->testOneToMany();
        $this->testManyToMany();
        $this->testHasManyThrough();
        $this->testPolymorphic();
        $this->testEagerLoading();
        $this->testLazyLoading();
    }
    
    private function testOneToOne() {
        echo "🔗 ONE-TO-ONE RELATIONSHIP:\n";
        
        // User -> Profile
        $user = ['id' => 1, 'username' => 'john_doe'];
        $profile = ['user_id' => 1, 'fullname' => 'John Doe', 'phone' => '0123456789'];
        
        $this->data['users'][1] = $user;
        $this->data['profiles'][1] = $profile;
        
        echo "  User 1 có profile:\n";
        echo "    - User: {$user['username']}\n";
        echo "    - Profile: {$profile['fullname']} - {$profile['phone']}\n";
        echo "  ✓ Mỗi user có DUY NHẤT một profile\n";
    }
    
    private function testOneToMany() {
        echo "\n🔗 ONE-TO-MANY RELATIONSHIP:\n";
        
        // User -> Assessments
        $user = ['id' => 1, 'username' => 'admin'];
        $assessments = [
            ['id' => 1, 'user_id' => 1, 'title' => 'Assessment 1'],
            ['id' => 2, 'user_id' => 1, 'title' => 'Assessment 2'],
            ['id' => 3, 'user_id' => 1, 'title' => 'Assessment 3']
        ];
        
        $this->data['assessments'] = $assessments;
        
        echo "  User '{$user['username']}' có " . count($assessments) . " assessments:\n";
        foreach ($assessments as $ass) {
            echo "    - {$ass['title']}\n";
        }
        echo "  ✓ Một user có NHIỀU assessments\n";
    }
    
    private function testManyToMany() {
        echo "\n🔗 MANY-TO-MANY RELATIONSHIP:\n";
        
        // Assessments <-> Tags (through pivot table)
        $assessments = [
            ['id' => 1, 'title' => 'Project Review'],
            ['id' => 2, 'title' => 'Code Review']
        ];
        
        $tags = [
            ['id' => 1, 'name' => 'important'],
            ['id' => 2, 'name' => 'urgent'],
            ['id' => 3, 'name' => 'completed']
        ];
        
        $pivot = [
            ['assessment_id' => 1, 'tag_id' => 1],
            ['assessment_id' => 1, 'tag_id' => 2],
            ['assessment_id' => 2, 'tag_id' => 1],
            ['assessment_id' => 2, 'tag_id' => 3]
        ];
        
        echo "  Assessment 'Project Review' có tags: ";
        $tagsForAss1 = array_filter($pivot, fn($p) => $p['assessment_id'] === 1);
        foreach ($tagsForAss1 as $p) {
            $tag = array_filter($tags, fn($t) => $t['id'] === $p['tag_id']);
            echo current($tag)['name'] . " ";
        }
        
        echo "\n  Assessment 'Code Review' có tags: ";
        $tagsForAss2 = array_filter($pivot, fn($p) => $p['assessment_id'] === 2);
        foreach ($tagsForAss2 as $p) {
            $tag = array_filter($tags, fn($t) => $t['id'] === $p['tag_id']);
            echo current($tag)['name'] . " ";
        }
        
        echo "\n  ✓ Nhiều-nhiều thông qua bảng trung gian (pivot)\n";
    }
    
    private function testHasManyThrough() {
        echo "\n🔗 HAS-MANY-THROUGH RELATIONSHIP:\n";
        
        // User -> Assessments -> Scores (through)
        $user = ['id' => 1, 'username' => 'admin'];
        $assessments = [
            ['id' => 1, 'user_id' => 1, 'title' => 'Assessment A'],
            ['id' => 2, 'user_id' => 1, 'title' => 'Assessment B']
        ];
        
        $scores = [
            ['id' => 1, 'assessment_id' => 1, 'score' => 85],
            ['id' => 2, 'assessment_id' => 1, 'score' => 90],
            ['id' => 3, 'assessment_id' => 2, 'score' => 78]
        ];
        
        $userScores = array_filter($scores, function($score) use ($assessments) {
            $ass = array_filter($assessments, fn($a) => $a['id'] === $score['assessment_id']);
            return !empty($ass) && current($ass)['user_id'] === 1;
        });
        
        echo "  User 'admin' có " . count($userScores) . " scores (through assessments):\n";
        foreach ($userScores as $score) {
            echo "    - Score: {$score['score']} (Assessment ID: {$score['assessment_id']})\n";
        }
        echo "  ✓ Lấy scores thông qua bảng assessments\n";
    }
    
    private function testPolymorphic() {
        echo "\n🔗 POLYMORPHIC RELATIONSHIP:\n";
        
        // Comments có thể thuộc về Assessments hoặc Criteria
        $comments = [
            ['id' => 1, 'commentable_type' => 'Assessment', 'commentable_id' => 1, 'content' => 'Great job!'],
            ['id' => 2, 'commentable_type' => 'Assessment', 'commentable_id' => 1, 'content' => 'Well done'],
            ['id' => 3, 'commentable_type' => 'Criteria', 'commentable_id' => 5, 'content' => 'Too strict'],
        ];
        
        $assessmentComments = array_filter($comments, fn($c) => $c['commentable_type'] === 'Assessment');
        $criteriaComments = array_filter($comments, fn($c) => $c['commentable_type'] === 'Criteria');
        
        echo "  Comments cho Assessments: " . count($assessmentComments) . "\n";
        echo "  Comments cho Criteria: " . count($criteriaComments) . "\n";
        echo "  ✓ Một bảng comments có thể liên kết với nhiều bảng khác\n";
    }
    
    private function testEagerLoading() {
        echo "\n⚡ EAGER LOADING (Load trước dữ liệu liên quan):\n";
        
        // Load assessments kèm theo user và criteria trong 1 query
        $assessments = [
            ['id' => 1, 'title' => 'Assessment 1', 'user' => ['username' => 'admin'], 'criteria' => [['name' => 'Quality'], ['name' => 'Speed']]],
            ['id' => 2, 'title' => 'Assessment 2', 'user' => ['username' => 'john'], 'criteria' => [['name' => 'Security']]]
        ];
        
        echo "  Số queries: 1 (thay vì 1 + N queries)\n";
        echo "  Dữ liệu relationships đã được load sẵn:\n";
        foreach ($assessments as $ass) {
            echo "    - {$ass['title']}: User={$ass['user']['username']}, Criteria=" . count($ass['criteria']) . "\n";
        }
        echo "  ✓ Tối ưu hiệu suất, tránh N+1 problem\n";
    }
    
    private function testLazyLoading() {
        echo "\n🐌 LAZY LOADING (Load khi cần):\n";
        
        // Chỉ load relationships khi truy cập
        $assessment = ['id' => 1, 'title' => 'Assessment 1'];
        
        echo "  - Khởi tạo: chỉ có dữ liệu assessment\n";
        
        // Khi cần user, mới load
        $assessment['user'] = ['username' => 'admin'];
        echo "  - Truy cập user: load thêm relationship user\n";
        
        // Khi cần criteria, mới load
        $assessment['criteria'] = [['name' => 'Quality'], ['name' => 'Speed']];
        echo "  - Truy cập criteria: load thêm relationship criteria\n";
        
        echo "  ✓ Load dữ liệu khi thực sự cần, tiết kiệm tài nguyên\n";
    }
}

$test = new RelationshipTest();
$test->run();
?>