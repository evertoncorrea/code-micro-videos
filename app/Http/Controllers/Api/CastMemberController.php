<?php

namespace App\Http\Controllers\Api;

use App\Models\CastMember;

class CastMemberController extends BasicCrudController
{
	private $rules;

	public function __construct() {
		$this->rules = [
			'name'=> 'required|max:255',
			'is_active' => 'boolean',
			'type' => ('required|in:' . implode(",", CastMember::ALL_TYPES))
		];
	}

	protected function model() {
        return CastMember::class;
	}

	protected function rulesStore()
	{
		return $this->rules;
	}

	protected function rulesUpdate()
	{
		return $this->rules;
	}	
}