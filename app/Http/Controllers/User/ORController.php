<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OriginalReceipt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ORController extends Controller
{
    public function index() {
        $original_receipt = OriginalReceipt::first();

        return view('OR.index', [
            'original_receipt' => $original_receipt,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'original_receipt_id' => 'required|integer|exists:original_receipts,id',
            ]);

            DB::transaction(function () use ($validated) {
                OriginalReceipt::create([
                    'original_receipt_id' => $validated['original_receipt_id'],
                ]);
            });

            return redirect()
                ->route('OriginalReceipt')
                ->with('success', 'Original Receipt created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to create Original Receipt. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'original_receipt_id' => 'required|integer|exists:original_receipts,id',
            ]);

            DB::transaction(function () use ($validated, $id) {
                $originalReceipt = OriginalReceipt::findOrFail($id);
                $originalReceipt->update([
                    'original_receipt_id' => $validated['original_receipt_id'],
                ]);
            });

            return redirect()
                ->route('OriginalReceipt')
                ->with('success', 'Original Receipt updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update Original Receipt. ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $originalReceipt = OriginalReceipt::findOrFail($id);
                $originalReceipt->delete();
            });

            return redirect()
                ->route('OriginalReceipt')
                ->with('success', 'Original Receipt deleted successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete Original Receipt. ' . $e->getMessage());
        }
    }
}
