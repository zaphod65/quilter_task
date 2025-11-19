<?php
     
namespace App\Http\Controllers;
     
use App\Http\Requests\{
    CreateAccountRequest,
    CreateTransactionRequest,
};
use App\Models\{
    Account,
    Transaction,
};
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function list(Request $request, int $accountId): JsonResponse
    {
        $userId = $request->user()->id;
        $account = Account::forUser(
            accountId: $accountId,
            userId: $userId,
        )->first();

        if (is_null($account)) {
            return $this->notFoundResponse('Account', $accountId);
        }

        $perPage = $request->input('per_page', 10);
        return response()->json($account->transactions()->paginate($perPage));
    }

    public function create(CreateTransactionRequest $request, int $accountId): JsonResponse
    {
        $userId = $request->user()->id;
        $account = Account::forUser(
            accountId: $accountId,
            userId: $userId,
        )->first();

        if (is_null($account)) {
            return $this->notFoundResponse('Account', $accountId);
        }

        $newBalance = $account->balance + $request->input('amount');
        if (!($newBalance >= 0)) {
            return response()->json(['message' => 'Transaction would take balance below zero'], 422);
        }

        $validated = $request->only(['description', 'amount']);
        $transaction = DB::transaction(function () use ($account, $validated, $newBalance) {
            $account->balance = $newBalance;
            $account->save();

            $transactionDetails = array_merge($validated, [
                'account_id' => $account->id,
            ]);
            return Transaction::create($transactionDetails);
        });
        $transaction->load(['account']);

        return response()->json($transaction);
    }
}
