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
        $before = (float) $wallet->balance;
        $wallet->forceFill([
            'balance' => $before + (float) $data['amount'],
            'available_balance' => $wallet->available_balance + (float) $data['amount'],
        ])->save();

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $request->user()->id,
            'type' => 'deposit',
            'direction' => 'credit',
            'amount' => $data['amount'],
            'balance_before' => $before,
            'balance_after' => $wallet->balance,
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

        if ($wallet->available_balance < (float) $data['amount']) {
            return back()->withErrors(['amount' => 'Saldo tidak cukup.']);
        }

        $before = (float) $wallet->balance;
        $wallet->forceFill([
            'balance' => $before - (float) $data['amount'],
            'available_balance' => $wallet->available_balance - (float) $data['amount'],
        ])->save();

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $request->user()->id,
            'type' => 'withdraw',
            'direction' => 'debit',
            'amount' => $data['amount'],
            'balance_before' => $before,
            'balance_after' => $wallet->balance,
            'description' => 'Withdraw wallet',
        ]);

        return back()->with('success', 'Penarikan berhasil diajukan.');
    }
}