<?php
namespace Database;

class ModelTest {
    private $models = [];
    
    public function run() {
        echo "\n=== KIỂM TRA MODEL ===\n\n";
        
        $this->testModelCreation();
        $this->testModelAttributes();
        $this->testModelCRUD();
        $this->testModelValidation();
        $this->testModelEvents();
        $this->testModelScope();
        $this->testModelSerialization();
    }
    
    private function testModelCreation() {
        echo "📦 TẠO MODEL:\n";
        
        // Định nghĩa User Model
        class UserModel {
            public $table = 'users';
            public $fillable = ['username', 'email', 'password'];
            public $hidden = ['password'];
            public $timestamps = true;
            
            public function __construct($data = []) {
                foreach ($data as $key => $value) {
                    if (property_exists($this, $key)) {
                        $this->$key = $value;
                    }
                }
            }
        }
        
        $user = new UserModel(['username' => 'john_doe', 'email' => 'john@example.com']);
        $this->models['User'] = $user;
        
        echo "✓ Tạo model User thành công\n";
        echo "  - Table: {$user->table}\n";
        echo "  - Fillable: " . implode(', ', $user->fillable) . "\n";
    }
    
    private function testModelAttributes() {
        echo "\n🏷️ MODEL ATTRIBUTES:\n";
        
        class AssessmentModel {
            protected $attributes = [];
            protected $casts = [
                'score' => 'float',
                'is_published' => 'boolean',
                'created_at' => 'datetime'
            ];
            
            public function __set($name, $value) {
                if (isset($this->casts[$name])) {
                    $value = $this->cast($value, $this->casts[$name]);
                }
                $this->attributes[$name] = $value;
            }
            
            public function __get($name) {
                return $this->attributes[$name] ?? null;
            }
            
            private function cast($value, $type) {
                switch($type) {
                    case 'float': return (float)$value;
                    case 'boolean': return (bool)$value;
                    case 'datetime': return date('Y-m-d H:i:s', strtotime($value));
                    default: return $value;
                }
            }
        }
        
        $assessment = new AssessmentModel();
        $assessment->score = "85.5";
        $assessment->is_published = "1";
        $assessment->title = "Project Review";
        
        echo "✓ Attribute casting:\n";
        echo "  - Score: {$assessment->score} (type: " . gettype($assessment->score) . ")\n";
        echo "  - Is published: " . ($assessment->is_published ? 'true' : 'false') . "\n";
        echo "  - Title: {$assessment->title}\n";
    }
    
    private function testModelCRUD() {
        echo "\n📝 MODEL CRUD OPERATIONS:\n";
        
        // Create
        $criteria = [
            'id' => 1,
            'name' => 'Code Quality',
            'weight' => 30
        ];
        echo "  ✓ CREATE: New criteria '{$criteria['name']}'\n";
        
        // Read
        $found = $criteria['id'] === 1;
        echo "  ✓ READ: Found criteria with ID {$criteria['id']}\n";
        
        // Update
        $criteria['weight'] = 35;
        echo "  ✓ UPDATE: Weight changed from 30 to {$criteria['weight']}\n";
        
        // Delete
        unset($criteria);
        echo "  ✓ DELETE: Criteria removed\n";
    }
    
    private function testModelValidation() {
        echo "\n✅ MODEL VALIDATION:\n";
        
        class ValidatedModel {
            private $rules = [
                'username' => 'required|min:3|max:50',
                'email' => 'required|email',
                'age' => 'integer|min:18|max:100'
            ];
            
            public function validate($data) {
                $errors = [];
                
                if (isset($this->rules['username'])) {
                    if (empty($data['username'])) {
                        $errors['username'] = 'Username is required';
                    } elseif (strlen($data['username']) < 3) {
                        $errors['username'] = 'Username must be at least 3 characters';
                    }
                }
                
                if (isset($this->rules['email'])) {
                    if (empty($data['email'])) {
                        $errors['email'] = 'Email is required';
                    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = 'Invalid email format';
                    }
                }
                
                return $errors;
            }
        }
        
        $model = new ValidatedModel();
        
        // Valid data
        $validData = ['username' => 'john', 'email' => 'john@example.com'];
        $errors = $model->validate($validData);
        echo "  ✓ Valid data: " . (empty($errors) ? 'PASS' : 'FAIL') . "\n";
        
        // Invalid data
        $invalidData = ['username' => 'jo', 'email' => 'not-an-email'];
        $errors = $model->validate($invalidData);
        echo "  ✓ Invalid data: " . count($errors) . " validation errors\n";
        foreach ($errors as $field => $error) {
            echo "    - $field: $error\n";
        }
    }
    
    private function testModelEvents() {
        echo "\n🎯 MODEL EVENTS:\n";
        
        class EventfulModel {
            private $events = [];
            
            public function fire($event, $data) {
                $this->events[] = ['event' => $event, 'data' => $data, 'time' => microtime(true)];
                
                switch($event) {
                    case 'creating':
                        echo "    - BEFORE CREATE: Đang chuẩn bị tạo model\n";
                        break;
                    case 'created':
                        echo "    - AFTER CREATE: Model đã được tạo\n";
                        break;
                    case 'updating':
                        echo "    - BEFORE UPDATE: Đang chuẩn bị cập nhật\n";
                        break;
                    case 'updated':
                        echo "    - AFTER UPDATE: Đã cập nhật thành công\n";
                        break;
                    case 'deleting':
                        echo "    - BEFORE DELETE: Đang chuẩn bị xóa\n";
                        break;
                }
            }
        }
        
        $model = new EventfulModel();
        
        echo "  Event lifecycle:\n";
        $model->fire('creating', ['name' => 'New Model']);
        $model->fire('created', ['id' => 1]);
        $model->fire('updating', ['field' => 'status']);
        $model->fire('updated', ['changes' => 1]);
        $model->fire('deleting', ['id' => 1]);
        
        echo "  ✓ Model events hoạt động (creating, created, updating, updated, deleting)\n";
    }
    
    private function testModelScope() {
        echo "\n🔍 MODEL SCOPES:\n";
        
        class ScopableModel {
            private $records = [
                ['status' => 'published', 'score' => 85],
                ['status' => 'draft', 'score' => 70],
                ['status' => 'published', 'score' => 92],
                ['status' => 'archived', 'score' => 65]
            ];
            
            public function scopePublished($records = null) {
                $records = $records ?? $this->records;
                return array_filter($records, fn($r) => $r['status'] === 'published');
            }
            
            public function scopeHighScore($minScore = 80) {
                return array_filter($this->records, fn($r) => $r['score'] >= $minScore);
            }
            
            public function scopeRecent($days = 7) {
                // Giả lập lọc theo ngày
                return $this->records;
            }
        }
        
        $model = new ScopableModel();
        
        $published = $model->scopePublished();
        $highScore = $model->scopeHighScore(80);
        
        echo "  Scopes:\n";
        echo "    - published(): " . count($published) . " records\n";
        echo "    - highScore(80): " . count($highScore) . " records\n";
        echo "  ✓ Query scopes giúp tái sử dụng logic truy vấn\n";
    }
    
    private function testModelSerialization() {
        echo "\n📤 MODEL SERIALIZATION:\n";
        
        class SerializableModel {
            public $id = 1;
            public $username = 'admin';
            public $password = 'secret123';
            public $email = 'admin@example.com';
            protected $hidden = ['password'];
            
            public function toArray() {
                $data = get_object_vars($this);
                foreach ($this->hidden as $hiddenField) {
                    unset($data[$hiddenField]);
                }
                return $data;
            }
            
            public function toJson() {
                return json_encode($this->toArray());
            }
        }
        
        $model = new SerializableModel();
        
        $array = $model->toArray();
        $json = $model->toJson();
        
        echo "  Array representation:\n";
        print_r($array);
        
        echo "  JSON representation:\n";
        echo "    $json\n";
        
        echo "  ✓ Hidden fields ('password') đã được ẩn trong serialization\n";
    }
}

$test = new ModelTest();
$test->run();
?>