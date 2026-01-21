<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{

    public function index(Request $request)
    {
        $activity_logs = ActivityLog::all();
        return view('_admin.activity_log.index', compact('activity_logs'));
    }

    public function delete($id)
    {
        $activity_logs = ActivityLog::findOrFail($id);
        $activity_logs->delete($id);
        return redirect()->route('admin.activity_logs.index')->with('success', 'Category deleted successfully');
    }

}
