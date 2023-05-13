<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Currency;
use App\Entity\Rate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ReportService
{
    public function __construct(private EntityManagerInterface $em, private SerializerInterface $serializer) {}

    public function generateFileReport(\DateTime $from, \DateTime $to): string
    {
        $currencies = $this->em->createQueryBuilder()
            ->select('c.code')
            ->from(Currency::class, 'c')
            ->where('c.active = 1')
            ->getQuery()
            ->getResult();

        $report = $this->em->getRepository(Rate::class)->getReportByDate($from, $to, $currencies);

        $filePath = sys_get_temp_dir().'/report.json';

        $file = file_put_contents($filePath, json_encode($report));

        return $filePath;
    }

    public function activeCurrency(bool $active, string $code): void
    {
        $currency = $this->em->getRepository(Currency::class)->findOneBy(['code' => $code]);

        $currency->setActive($active);

        $this->em->flush();
    }
}