<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Expense;
use App\Repositories\Contracts\ExpenseInterface;
use App\Exceptions\Api\UnknowException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class ExpenseRepository extends BaseRepository implements ExpenseInterface
{
    public function model()
    {
        return Expense::class;
    }

    public function multiCreate($data)
    {
        parent::multiCreate($data[0]);

        foreach ($data[1] as $value) {
            $expense = parent::create($value['expense']);
            $qualityId = app(QualityRepository::class)->firstOrCreate(['name' => $value['quality']])->id;
            $productId = app(ProductRepository::class)->firstOrCreate(['name' => $value['name']])->id;
            $expense->products()->attach($productId, [
                'quality_id' => $qualityId,
                'quantity' => $value['quantity'],
            ]);
        }

        return true;
    }

    public function update($id, $data)
    {
        $newExpense = parent::update($id, $data['expense']);

        if ($data['expense']['type']) {
            $qualityId = app(QualityRepository::class)->firstOrCreate(['name' => $data['quality']])->id;
            $productId = app(ProductRepository::class)->firstOrCreate(['name' => $data['name']])->id;
            \DB::table('expense_product')
                ->where('expense_id', $id)
                ->update([
                    'quality_id' => $qualityId,
                    'product_id' => $productId,
                    'quantity' => $data['quantity'],
                ]);
        }

        return $newExpense;
    }

    public function delete($expense)
    {
        \DB::table('expense_product')
            ->where('expense_id', $expense->id)
            ->update(['deleted_at' => Carbon::Now()]);

        return $expense->delete();
    }
}
