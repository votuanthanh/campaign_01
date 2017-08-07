<?php

namespace App\Repositories\Contracts;

interface ExpenseInterface extends RepositoryInterface
{
	public function createExpenseBuy($data);
}
