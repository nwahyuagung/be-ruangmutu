<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;

use App\Service\Dashboard\DashboardService;

use Illuminate\Http\Request;

class DashboardController extends ApiController
{
    protected $dashboardService;

    public function __construct(
        DashboardService $dashboardService,
        Request $request)
    {
        $this->dashboardService    =   $dashboardService;
        parent::__construct($request);
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function indicator(Request $request): \Illuminate\Http\JsonResponse
    {
        $input = $request;
        $result = $this->dashboardService->dashboard($input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function cardlist(Request $request) {
        $result = $this->dashboardService->indicatorDataList($request);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function recapIndicator(Request $request) {
        $year = $this->request->query('year', null);
        $result = $this->dashboardService->recapIndicator($year);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function recapComplaint(Request $request) {
        $year = $this->request->query('year', null);
        $result = $this->dashboardService->recapComplaint($year);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function recapSatisfaction(Request $request) {
        $year = $this->request->query('year', date('Y'));
        $result = $this->dashboardService->recapSatisfaction($year);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }
    
    public function recapPerformance(Request $request) {
        $year = $this->request->query('year', null);
        $result = $this->dashboardService->recapPerformance($year);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }
    
    public function eventInfo(Request $request) {
        $year = $this->request->query('year', date('Y'));
        $result = $this->dashboardService->eventInfo($year);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function documentInfo(Request $request) {
        $year = $this->request->query('year', date('Y'));
        $result = $this->dashboardService->documentInfo($year);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }
}
