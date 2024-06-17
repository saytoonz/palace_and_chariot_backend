<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    use ApiResponseTrait;

    function getActivityLogs()
    {
        $accessLog = ActivityLog::orderBy('id', 'DESC')->paginate();
        return $this->ApiResponse(true, 'activity_log', null,  $accessLog);
    }
}
