<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Student;
use App\Services\PayableAssignmentService;
use App\Models\Penalty;

class PayablesController extends Controller
{
    private function autoAssignPayableToStudents(Payable $payable): void
    {
        $service = new PayableAssignmentService();

        // Get all active students for this school year
        $students = Student::where('school_year', $payable->school_year)->get();

        # dd($students);

        if ($students->isNotEmpty()) {
            $service->assignPayableToStudents($payable);
        }
    }

    public function index(Request $request)
    {
        $query = Payable::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $payables = $query->latest('updated_at')->paginate(50)->withQueryString();

        $penalties = Penalty::latest('updated_at')->get();

        return view('payables.index', [
            'payables'  => $payables,
            'penalties' => $penalties,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:50',
            'type'         => 'required|in:tuition,enrollment,electricity,assessment,uniforms,graduation,others',
            'school_year'  => 'required|string|size:9',
            'is_repeatable' => 'nullable',
            'details'      => 'required|json',
        ]);

        # dd($validated);

        try {
            DB::beginTransaction();

            $details = json_decode($validated['details'], true);

            # dd($details);

            $payable = Payable::create([
                'name'        => $validated['name'],
                'type'        => $validated['type'],
                'school_year' => $validated['school_year'],
                'is_repeatable' => $validated['is_repeatable'] ?? 0,
                'details'     => $details,
            ]);

            # dd($payable);

            $this->autoAssignPayableToStudents($payable);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Payable created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            \Log::error('Payable Creation Failed: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create payable. Please try again.');
        }
    }

    public function destroy(Payable $payable)
    {
        DB::beginTransaction();

        try {

            $payable->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Payable deleted successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Failed to delete payable.', [
                'payable_id' => $payable->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to delete payable.');
        }
    }

    public function update(Request $request, Payable $payable)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:50',
            'type'         => 'required|in:tuition,enrollment,electricity,assessment,uniforms,graduation,others',
            'school_year'  => 'required|string|size:9',
            'is_repeatable' => 'nullable',
            'details'      => 'required|json',
        ]);

        try {
            DB::beginTransaction();

            $details = json_decode($validated['details'], true);

            // Update the payable
            $payable->update([
                'name'        => $validated['name'],
                'type'        => $validated['type'],
                'school_year' => $validated['school_year'],
                'is_repeatable' => $validated['is_repeatable'] ?? 0,
                'details'     => $details,
            ]);

            $service = new PayableAssignmentService();
            $service->refreshUnpaidStudentPayables($payable);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Payable updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payable Update Failed: ' . $e->getMessage(), [
                'payable_id' => $payable->id,
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update payable. Please try again.');
        }
    }

    public function storePenalty(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:50',
            'type'   => 'required|in:tuition,enrollment,electricity,assessment,uniforms,graduation,others',
            'amount' => 'required|numeric|min:0',
        ]);

        Penalty::create($validated);

        return redirect()->back()->with('success', 'Penalty created successfully!');
    }

    public function updatePenalty(Request $request, Penalty $penalty)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:50',
            'type'   => 'required|in:tuition,enrollment,electricity,assessment,uniforms,graduation,others',
            'amount' => 'required|numeric|min:0',
        ]);

        $penalty->update($validated);

        return redirect()->back()->with('success', 'Penalty updated successfully!');
    }

    public function destroyPenalty(Penalty $penalty)
    {
        $penalty->delete();

        return redirect()->back()->with('success', 'Penalty deleted successfully.');
    }
}
