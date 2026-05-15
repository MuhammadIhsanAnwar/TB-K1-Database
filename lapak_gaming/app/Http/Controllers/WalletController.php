<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(Request $request): View
    {
        $wallet = Wallet::firstOrCreate(['user_id' => $request->user()->id]);

        return view('wallet.index', [
            'wallet' => $wallet->load('transactions'),
        ]);
    }

    public function deposit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
        ]);

        $wallet = Wallet::firstOrCreate(['user_id' => $request->user()->id]);
        $balanceState = $wallet->balanceState()->firstOrCreate([], [
            'balance' => 0,
            'available_balance' => 0,
            'locked_balance' => 0,
        ]);

        $before = (float) $balanceState->balance;
        $balanceState->forceFill([
            'balance' => $before + (float) $data['amount'],
            'available_balance' => (float) $balanceState->available_balance + (float) $data['amount'],
        ])->save();

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $request->user()->id,
            'type' => 'deposit',
            'direction' => 'credit',
            'amount' => $data['amount'],
            'balance_before' => $before,
            'balance_after' => $balanceState->balance,
            'description' => 'Deposit wallet',
        ]);

        return back()->with('success', 'Deposit berhasil ditambahkan.');
    }

    public function withdraw(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
        ]);

        $wallet = Wallet::firstOrCreate(['user_id' => $request->user()->id]);
        $balanceState = $wallet->balanceState()->firstOrCreate([], [
            'balance' => 0,
            'available_balance' => 0,
            'locked_balance' => 0,
        ]);

        if ($balanceState->available_balance < (float) $data['amount']) {
            return back()->withErrors(['amount' => 'Saldo tidak cukup.']);
        }

        $before = (float) $balanceState->balance;
        $balanceState->forceFill([
            'balance' => $before - (float) $data['amount'],
            'available_balance' => (float) $balanceState->available_balance - (float) $data['amount'],
        ])->save();

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $request->user()->id,
            'type' => 'withdraw',
            'direction' => 'debit',
            'amount' => $data['amount'],
            'balance_before' => $before,
            'balance_after' => $balanceState->balance,
            'description' => 'Withdraw wallet',
        ]);

        return back()->with('success', 'Penarikan berhasil diajukan.');
    }
}