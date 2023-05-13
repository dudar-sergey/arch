<?php

declare(strict_types=1);

namespace App\Controller;

use App\Integration\CNB\HttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test', name: 'test')]
class TestAction extends AbstractController
{
    public function __construct(private HttpClient $client) {}

    public function __invoke()
    {
        $date = \DateTime::createFromFormat('Y-m-d', '2019-07-28');
    }
}