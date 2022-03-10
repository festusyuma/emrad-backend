<?php

namespace Emrad\Http\Controllers\Distributor;

use Emrad\Http\Controllers\Controller;
use Emrad\Services\Distributor\IndexService;
use Illuminate\Http\Request;

class IndexController  extends Controller
{
    public IndexService $indexService;

    public function __construct(IndexService $indexService)
    {
        $this->indexService = $indexService;
    }

    public function getStats(Request $request) {
        $result = $this->indexService->fetchStats();

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }
}
