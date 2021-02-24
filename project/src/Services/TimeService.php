<?php


namespace App\Services;


use App\Entity\Race;
use App\Entity\Time;
use Doctrine\ORM\EntityManagerInterface;

class TimeService
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Race $race
     * @return array
     */
    public function setSimulatedParams(Race $race): array
    {
        $drivers = $race->getDrivers();
        $times = [];

        foreach ($drivers as $driver) {
            $time = $this->simulateTime();
            $driver->addTime($time);
            $race->addTime($time);
            $this->entityManager->persist($time);
            $this->entityManager->flush();

            array_push($times, $time);
        }

        $timesWithPositions = $this->assignPositions($times);

        return $this->addTimesToDatabase($timesWithPositions);
    }

    /**
     * @param array $times
     * @return array
     */
    private function addTimesToDatabase(array $times): array
    {
        foreach ($times as $time) {
            $this->entityManager->persist($time);
            $this->entityManager->flush();
        }

        return $times;
    }

    /**
     * @return Time
     */
    private function simulateTime(): Time
    {
        $time = new Time();
        $time->setTime(gmdate("H:i:s", rand(512, 600)));

        return $time;
    }

    /**
     * @param array $times
     * @return array
     */
    private function assignPositions(array $times): array
    {
        $position = 1;
        usort($times, function ($a, $b) {
            return $a->getTime() <=> $b->getTime();
        });
        /** @var Time $time */
        foreach ($times as $time) {
            $time->setPosition($position);
            $position++;
        }

        return $times;
    }
}