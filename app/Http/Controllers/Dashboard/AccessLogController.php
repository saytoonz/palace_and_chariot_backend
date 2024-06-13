<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Traits\ApiResponseTrait;

class AccessLogController extends Controller
{
    use ApiResponseTrait;

    function getAccessLogs() {
            $accessLog = AccessLog::orderBy('id', 'DESC')->paginate();
     return $this->ApiResponse(true, 'access_log', null,  $accessLog);
    }
}
