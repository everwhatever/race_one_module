<?php


namespace App\Services\Time;


use App\Entity\Race;
use App\Entity\Time;
use Doctrine\ORM\EntityManagerInterface;

class TimeCreator
{

    private TimeSaver $timeSaver;
    private EntityManagerInterface $entityManager;

    public function __construct(TimeSaver $timeSaver, EntityManagerInterface $entityManager)
    {
        $this->timeSaver = $timeSaver;
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
            $time = $this->createSimulatedTime();
            $driver->addTime($time);
            $race->addTime($time);
            $this->entityManager->persist($time);
            $this->entityManager->flush();

            array_push($times, $time);
        }

        $timesWithPositions = $this->assignPositions($times);

        return $this->timeSaver->addTimesToDatabase($timesWithPositions);
    }


    /**
     * @return Time
     */
    private function createSimulatedTime(): Time
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