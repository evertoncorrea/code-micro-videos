<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use App\Models\Category;
use Illuminate\Http\Request;
use Tests\Stubs\Models\CategoryStub;

class CategoryControllerStub extends BasicCrudController
{
    protected function model() {
        return CategoryStub::class;
	}

	private $rules = [
		'name'=> 'required|max:255',
		'is_active' => 'boolean',
		'description' => 'nullable'
	];
	protected function rulesStore()
	{
		return $this->rules;
	}	
	protected function rulesUpdate()
	{
		return $this->rules;
	}	

}
