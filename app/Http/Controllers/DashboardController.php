<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        $transactions = $request->user()
            ->transactions()
            ->whereDate('created_at', $today)
            ->get();

        $entradas = $transactions
            ->where('type', 'entrada')
            ->sum('amount');

        $saidas = $transactions
            ->where('type', 'saida')
            ->sum('amount');

        return response()->json([
            'entradas' => $entradas,
            'saidas' => $saidas,
            'saldo' => $entradas - $saidas,
            'transactions' => $transactions
        ]);
    }
}