<?php
     
namespace App\Http\Controllers;
     
use App\Http\Requests\CreateAccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function create(CreateAccountRequest $request): JsonResponse
    {
        $accountDetails = array_merge(
            $request->only(['name', 'balance']),
            [
                'user_id' => $request->user()->id,
            ]
        );

        $account = Account::create($accountDetails);

        return response()->json($account->toArray());
    }

    public function show(Request $request, int $accountId): JsonResponse
    {
        $userId = $request->user()->id;

        $account = Account::forUser(
            accountId: $accountId,
            userId: $userId,
        )->first();

        // Manual error handling here to avoid picky error handling elsewhere
        if (is_null($account)) {
            // Not found here to stop leaking to malicious actors that an account with that ID does exist
            return $this->notFoundResponse('Account', $accountId);
        }

        return response()->json($account->toArray());
    }

    public function list(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(
            // TODO: resource class?
            Account::where([
                'user_id' => $user->id,
            ])->get()
        );
    }
}