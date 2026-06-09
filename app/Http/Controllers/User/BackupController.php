<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BackupPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\BackupSchedule;

class BackupController extends Controller
{
    public function index()
    {
        $paths = BackupPath::where('is_active', true)
                          ->orderBy('name')
                          ->get();

        $schedule = BackupSchedule::getSettings();

        return view('backup.index', compact('paths', 'schedule'));
    }

    /**
     * Store New Backup Path
     */
    public function storePath(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:backup_paths,name',
            'path' => 'required|string'
        ]);

        $path = trim($request->path);

        if (!File::isDirectory($path)) {
            return back()->with('error', 'The provided path is not a valid directory.');
        }

        // Security check
        $realPath = realpath($path);
        if ($realPath && in_array($realPath, ['/', '/etc', '/var', '/root', 'C:\\', 'C:/', 'C:\\Windows'])) {
            return back()->with('error', 'Access to system root directories is not allowed.');
        }

        BackupPath::create([
            'name' => $request->name,
            'path' => $path,
        ]);

        return back()->with('success', 'New backup path added successfully.');
    }

    /**
     * Verify Path (Updated for ID)
     */
    public function verifyPath(Request $request)
    {
        $pathId = $request->input('backup_path_id');

        if (empty($pathId)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a backup path.'
            ]);
        }

        $backupPath = BackupPath::find($pathId);

        if (!$backupPath) {
            return response()->json([
                'success' => false,
                'message' => 'Selected backup path not found.'
            ]);
        }

        if (!File::isDirectory($backupPath->path)) {
            return response()->json([
                'success' => false,
                'message' => 'Directory does not exist or is not accessible.'
            ]);
        }

        // Security check
        $realPath = realpath($backupPath->path);
        if ($realPath && in_array($realPath, ['/', '/etc', '/var', '/root', 'C:\\', 'C:/'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access to system root directories is not allowed.'
            ]);
        }

        $backups = $this->getSqlBackups($backupPath->path);

        // Save in session for download/restore/delete
        session(['current_backup_path' => $backupPath->path]);

        return response()->json([
            'success' => true,
            'backups' => $backups,
            'path'    => $backupPath->path
        ]);
    }

    private function getSqlBackups($path)
    {
        $files = File::files($path);
        $backups = [];

        foreach ($files as $file) {
            if (strtolower($file->getExtension()) === 'sql') {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => round($file->getSize() / 1024 / 1024, 2) . ' MB',
                    'date' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
        }

        usort($backups, fn($a, $b) => strtotime($b['date']) <=> strtotime($a['date']));
        return $backups;
    }

    /**
     * Create Manual Backup
     */
    public function backup(Request $request)
    {
        $backupPathModel = BackupPath::findOrFail($request->backup_path_id);

        $database = config('database.connections.mysql.database');
        $filename = $database . '_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $fullPath = rtrim($backupPathModel->path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > "%s"',
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_HOST')),
            escapeshellarg($database),
            $fullPath
        );

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            return back()->with('error', 'Backup failed. Check database credentials and folder permissions.');
        }

        session(['current_backup_path' => $backupPathModel->path]);

        return back()->with('success', 'Backup created successfully!');
    }

    public function download($filename)
    {
        $backupPath = session('current_backup_path');

        if (!$backupPath || !File::exists($backupPath . DIRECTORY_SEPARATOR . $filename)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($backupPath . DIRECTORY_SEPARATOR . $filename);
    }

    public function restoreFromFile($filename)
    {
        $backupPath = session('current_backup_path');

        if (!$backupPath) {
            return back()->with('error', 'No backup path selected.');
        }

        $filePath = rtrim($backupPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (!File::exists($filePath)) {
            return back()->with('error', 'Backup file not found.');
        }

        $database = config('database.connections.mysql.database');

        $command = sprintf(
            'mysql --user=%s --password=%s --host=%s %s < "%s"',
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_HOST')),
            escapeshellarg($database),
            $filePath
        );

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            return back()->with('error', 'Restore failed. Check error logs for details.');
        }

        return back()->with('success', 'Database restored successfully!');
    }

    public function delete($filename)
    {
        $backupPath = session('current_backup_path');

        if (!$backupPath) {
            return back()->with('error', 'No backup path selected.');
        }

        $filePath = rtrim($backupPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (File::exists($filePath)) {
            File::delete($filePath);
            return back()->with('success', 'Backup file deleted successfully.');
        }

        return back()->with('error', 'File not found.');
    }

    public function destroyPath($id)
    {
        $backupPath = BackupPath::findOrFail($id);

        // Prevent deleting if it's the only path or default (optional protection)
        if ($backupPath->is_default) {
            return back()->with('error', 'Cannot delete the default backup path.');
        }

        // Optional: Check if path has files before deleting
        if (File::exists($backupPath->path) && count(File::files($backupPath->path)) > 0) {
            return back()->with('error', 'Cannot delete path that contains backup files. Please delete files first.');
        }

        $backupPath->delete();

        return back()->with('success', 'Backup path deleted successfully.');
    }

    public function setDefaultPath($id)
    {
        $newDefault = BackupPath::findOrFail($id);

        // Remove default from all other paths
        BackupPath::where('is_default', true)->update(['is_default' => false]);

        // Set new default
        $newDefault->update(['is_default' => true]);

        return response()->json(['success' => true]);
    }


    public function scheduleIndex()
    {
        $schedule = BackupSchedule::getSettings();
        $paths = BackupPath::where('is_active', true)->orderBy('name')->get();

        return view('backup.index', compact('schedule', 'paths'));
    }

public function updateSchedule(Request $request)
{
    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'is_enabled'      => 'nullable',
        'frequency'       => 'required|in:daily,weekly,biweekly,monthly,quarterly,yearly',
        'backup_time'     => 'required|date_format:H:i',   // Changed back + we'll handle it
        'backup_path_id'  => 'required|exists:backup_paths,id',
        'day_of_week'     => 'nullable|in:mon,tue,wed,thu,fri,sat,sun',
        'day_of_month'    => 'nullable|integer|between:1,28',
    ]);

    if ($validator->fails()) {
        dd([
            'errors'        => $validator->errors()->all(),
            'all_input'     => $request->all(),
            'backup_time'   => $request->backup_time,
        ]);
    }

    // Validation passed
    $schedule = BackupSchedule::getSettings();

    $updateData = [
        'is_enabled'     => $request->boolean('is_enabled'),
        'frequency'      => $request->frequency,
        'backup_time'    => $request->backup_time . ':00',
        'backup_path_id' => $request->backup_path_id,
        'day_of_week'    => null,
        'day_of_month'   => null,
    ];

    if (in_array($request->frequency, ['weekly', 'biweekly'])) {
        $updateData['day_of_week'] = $request->day_of_week;
    } elseif (in_array($request->frequency, ['monthly', 'quarterly', 'yearly'])) {
        $updateData['day_of_month'] = $request->day_of_month;
    }

    $schedule->update($updateData);
    $schedule->updateNextRun();

    return back()->with('success', 'Backup schedule saved successfully!');
}
}
