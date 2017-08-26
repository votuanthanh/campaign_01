<?php

namespace App\Repositories\Contracts;

interface ExpenseInterface extends RepositoryInterface
{
    public function createExpenseBuy($data);

    public function deleteFromEvent($expenses);

    public function openFromEvent($event);
}
