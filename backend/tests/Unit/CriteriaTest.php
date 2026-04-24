<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Criteria;
use App\Models\Category;

class CriteriaTest extends TestCase
{
    public function test_criteria_can_be_created()
    {
        $category = Category::factory()->create();
        
        $criteria = Criteria::create([
            'category_id' => $category->id,
            'code' => 'TEST-01',
            'name' => 'Test Criteria',
            'weight' => 5,
            'status' => 'active'
        ]);
        
        $this->assertDatabaseHas('criteria', ['code' => 'TEST-01']);
        $this->assertEquals(5, $criteria->weight);
    }

    public function test_criteria_belongs_to_category()
    {
        $category = Category::factory()->create();
        $criteria = Criteria::factory()->create(['category_id' => $category->id]);
        
        $this->assertInstanceOf(Category::class, $criteria->category);
        $this->assertEquals($category->id, $criteria->category->id);
    }

    public function test_criteria_code_must_be_unique()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        $category = Category::factory()->create();
        
        Criteria::create([
            'category_id' => $category->id,
            'code' => 'DUPLICATE',
            'name' => 'First',
            'weight' => 1
        ]);
        
        Criteria::create([
            'category_id' => $category->id,
            'code' => 'DUPLICATE',
            'name' => 'Second',
            'weight' => 1
        ]);
    }

    public function test_criteria_can_calculate_score()
    {
        $criteria = Criteria::factory()->create(['weight' => 10, 'answer_type' => 'yes_no']);
        
        $score = $criteria->calculateScore('yes');
        $this->assertEquals(10, $score);
        
        $score = $criteria->calculateScore('no');
        $this->assertEquals(0, $score);
    }
}