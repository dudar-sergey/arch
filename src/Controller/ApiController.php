<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    public function __construct(private ReportService $service) {}

    #[Route('/report')]
    public function getReport(Request $request): BinaryFileResponse
    {
        return $this->file(
            $this->service->generateFileReport(
                \DateTime::createFromFormat('Y-m-d', $request->query->get('from')),
                \DateTime::createFromFormat('Y-m-d', $request->query->get('to'))
            )
        );
    }

    #[Route('/currency/active')]
    public function currencyActive(Request $request)
    {
        $code = $request->query->get('code');
        $active = $request->query->get('active');

        $this->service->activeCurrency((bool)$active, $code);

        return new Response();
    }
}