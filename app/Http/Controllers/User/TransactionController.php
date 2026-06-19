<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;

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
    public function print($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction = Transaction::findOrFail($id);

        $pdf = Pdf::loadView('transactions.partials.pdf', [
            'transaction' => $transaction,
        ])
        ->setPaper([0, 0, 164, 595], 'portrait')
        ->setOption('margin_top', 0)
        ->setOption('margin_right', 0)
        ->setOption('margin_bottom', 0)
        ->setOption('margin_left', 0)
        ->setOption('defaultFont', 'Arial');;

        return $pdf->stream(
            'transaction_' . $transaction->transaction_code ?? $transaction->id . '.pdf'
        );
    }

}
