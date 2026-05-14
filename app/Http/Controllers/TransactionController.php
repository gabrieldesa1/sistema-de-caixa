<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
{
    return $request->user()
        ->transactions()
        ->with('category')
        ->latest()
        ->get();
}

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required',
            'category' => 'required',
        ]);

        $transaction = $request->user()
            ->transactions()
            ->create($validated);

        return response()->json($transaction, 201);
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'title' => 'sometimes|required',
            'amount' => 'sometimes|required|numeric',
            'type' => 'sometimes|required',
            'category' => 'sometimes|required',
        ]);

        $transaction = $request->user()
            ->transactions()
            ->findOrFail($id);

        $transaction->update($validated);

        return response()->json($transaction);
    }

    public function destroy (Request $request, $id) {
        $transaction = $request->user()
            ->transactions()
            ->findOrFail($id);

        $transaction->delete();
    
        return response()->json([
            'message' => 'Transação deletada'
        ]);
    }
}
