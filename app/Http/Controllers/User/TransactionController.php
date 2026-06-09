<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('student');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                ->orWhere('complete_name', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()
            ->paginate(30)
            ->withQueryString();

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('student');

        return response()->json([
            'transaction' => $transaction
        ]);
    }
}
