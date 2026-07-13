<?php

namespace App\Http\Controllers\User\Portal;

use App\Http\Controllers\Controller;
use App\Services\Application\ApplicationStatusService;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationStatusController extends Controller
{
    public function __construct(
        private readonly ApplicationStatusService $statusService
    ) {
    }

    public function __invoke(): Response
    {
        return Inertia::render('Portal/ApplicationStatus', [
            'application' => $this->statusService->getSummary(
                request()->user()
            ),
        ]);
    }
}
