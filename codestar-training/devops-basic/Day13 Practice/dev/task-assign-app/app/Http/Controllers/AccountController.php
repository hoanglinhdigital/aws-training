<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Exception;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    const DEFAULT_PAGINATE = 15;

    public function getPendingAccounts()
    {
        $pendingAccounts = Account::pending()->with('staff')->paginate($this::DEFAULT_PAGINATE);
        $reformatedPendingAccounts = $this->reformatAccounts($pendingAccounts);
        
        return view('pending-accounts', ['pendings' => $reformatedPendingAccounts]);
    }

    public function activateAccounts(Request $request) {
        $accountIds = $request->input('account_ids');

        try {
            foreach ($accountIds as $id) {

                $account = Account::find($id);

                if (!$account) {
                    return redirect()->back()->with('error', 'Tài khoản không tồn tại');
                }

                $account->activate();
                $account->save();
            }


            return redirect()->route('accounts.pending')->with('success', 'Kích hoạt tài khoản thành công');
        } 
        catch (Exception $e) {
            return redirect()->back()->with('error', 'Lỗi cơ sở dữ liệu: ' . $e->getCode());

        }

    }


    private function reformatAccounts($accounts) {
        foreach($accounts as $acc) {
            $acc->name = $acc->staff->name;
        }

        return $accounts;
    }
}
