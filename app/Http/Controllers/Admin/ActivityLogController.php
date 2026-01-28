<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{

    public function index(Request $request)
    {
        $keywords = $request->get('keywords');
        $activity_logs = ActivityLog::when($keywords, function($query) use ($keywords) {
            return $query->where('description', 'like', "%{$keywords}%")
                         ->orWhere('causer_type', 'like', "%{$keywords}%");
        })
        ->paginate(10);
        return view('_admin.activity_log.index', compact('activity_logs'));
    }

    public function delete($id)
    {
        $activity_logs = ActivityLog::findOrFail($id);
        $activity_logs->delete($id);
        return redirect()->route('admin.activity_logs.index')->with('success', 'Category deleted successfully');
    }

}
