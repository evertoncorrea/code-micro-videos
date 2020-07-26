<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;
use App\Models\Category;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class, UUid::class
        ];
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEquals($traits, $categoryTraits);
        // print_r($categoryTraits);
    }

    public function testCasts()
    {
        $casts = ['is_active' => 'boolean', 'id' => 'string'];
        $this->assertEquals($casts, $this->category->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->category->incrementing);
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'description', 'is_active'];
        $this->assertEquals($fillable, $this->category->getFillable());
    }

    public function testDatesAttribute()
    {
        $dates = ['created_at', 'deleted_at', 'updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $this->category->getDates());
        }
        $this->assertSameSize($dates, $this->category->getDates());
    }

}
