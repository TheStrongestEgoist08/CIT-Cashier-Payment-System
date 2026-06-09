<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog as ActivityLogs;
use Illuminate\Database\Eloquent\Model;

class ActivityLog
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user()) {
            $this->logActivity($request);
        }

        return $response;
    }

    protected function logActivity(Request $request)
    {
        ActivityLogs::create([
            'user_id'     => $request->user()->id,
            'action'      => $this->getActionName($request),
            'model_type'  => $this->getModelType($request),
            'model_id'    => $this->getModelId($request),
            'description' => $this->getDescription($request),
            'properties'  => $this->getProperties($request),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);
    }

    protected function getDescription(Request $request): string
    {
        $userName   = $request->user()->name;
        $routeName  = $request->route()->getName();
        $modelId    = $this->getModelId($request);
        $targetName = $this->getTargetName($request);

        return match (true) {

            // ==================== ACCOUNTS ====================
            $routeName === 'accounts.activate'   => "Activated user account"   . ($targetName ? " - {$targetName}" : "") . ($modelId ? " (#{$modelId})" : ""),
            $routeName === 'accounts.deactivate' => "Deactivated user account" . ($targetName ? " - {$targetName}" : "") . ($modelId ? " (#{$modelId})" : ""),
            $routeName === 'accounts.store'      => "Created new user account" . ($targetName ? " - {$targetName}" : ""),
            $routeName === 'accounts.destroy'    => "Deleted user account"     . ($targetName ? " - {$targetName}" : "") . ($modelId ? " (#{$modelId})" : ""),

            // ==================== STUDENTS ====================
            $routeName === 'students.store'       => "Payment transaction for " . ($targetName ? " - {$targetName}" : ""),
            $routeName === 'students.import'      => "Imported multiple students",
            $routeName === 'students.getPayables' => "Viewed payables for student" . ($targetName ? " - {$targetName}" : ""),

            // ==================== PAYABLES ====================
            $routeName === 'payables.store'   => "Added new payable"   . ($targetName ? " - {$targetName}" : "") . ($modelId ? " (#{$modelId})" : ""),
            $routeName === 'payables.update'  => "Updated payable"     . ($targetName ? " - {$targetName}" : "") . ($modelId ? " (#{$modelId})" : ""),
            $routeName === 'payables.destroy' => "Deleted payable"     . ($targetName ? " - {$targetName}" : "") . ($modelId ? " (#{$modelId})" : ""),

            // ==================== TRANSACTIONS ====================
            $routeName === 'transactions.show' => "Viewed transaction details" . ($modelId ? " (#{$modelId})" : ""),

            // ==================== VIEWS ====================
            $routeName === 'dashboard'     => "Viewed Dashboard",
            $routeName === 'students'      => "Viewed students list",
            $routeName === 'payables'      => "Viewed payables list",
            $routeName === 'transactions'  => "Viewed transactions list",
            $routeName === 'summary'       => "Viewed Summary of Accounts",
            $routeName === 'reports'       => "Viewed Reports",
            $routeName === 'accounts'      => "Viewed User Accounts list",
            $routeName === 'activities'    => "Viewed Activity Logs",

            // ==================== EXPORT & PRINT ====================
            str_contains($routeName, 'export') => "Exported data",
            str_contains($routeName, 'print')  => "Printed document",

            // Default fallback
            default => ucfirst($this->getActionName($request)) . " record" . ($modelId ? " (#{$modelId})" : "")
        } . " by {$userName}";
    }

    protected function getActionName(Request $request): string
    {
        return match ($request->method()) {
            'POST'         => 'created',
            'PUT', 'PATCH' => 'updated',
            'DELETE'       => 'deleted',
            default        => 'viewed',
        };
    }

    protected function getModelType(Request $request): ?string
    {
        $routeName = $request->route()->getName();

        return match (true) {
            str_contains($routeName, 'students')    => 'Student',
            str_contains($routeName, 'payables')    => 'Payable',
            str_contains($routeName, 'transactions') => 'Transaction',
            str_contains($routeName, 'accounts')    => 'User',
            default => null,
        };
    }

    protected function getModelId(Request $request): ?int
    {
        $param = $request->route('payable')
              ?? $request->route('student')
              ?? $request->route('transaction')
              ?? $request->route('user')
              ?? $request->route('id');

        if ($param instanceof Model) {
            return $param->getKey();
        }

        return is_numeric($param) ? (int) $param : null;
    }

    protected function getTargetName(Request $request): ?string
    {
        // From form input
        if ($name = $request->input('name') ?? $request->input('complete_name')) {
            return $name;
        }

        // From Route Model Binding
        foreach (['user', 'student', 'payable'] as $key) {
            $model = $request->route($key);
            if ($model instanceof Model) {
                return match (true) {
                    $model instanceof \App\Models\User    => $model->name ?? null,
                    $model instanceof \App\Models\Student => $model->complete_name ?? null,
                    $model instanceof \App\Models\Payable => $model->name ?? null,
                    default => null,
                };
            }
        }

        return null;
    }

    protected function getProperties(Request $request): array
    {
        return $request->except([
            'password',
            'password_confirmation',
            '_token',
            '_method'
        ]);
    }
}
