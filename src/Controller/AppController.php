<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/')]
    public function main()
    {
        $currencies = $this->em->getRepository(Currency::class)->findAll();

        return $this->render('index.html.twig', [
            'currencies' => $currencies
        ]);
    }
}