<?php


namespace App\Shared\Domain\Service;


use App\Driver\Infrastructure\Repository\DriverRepository;
use App\Race\Domain\Model\Race;
use App\Race\Domain\Model\Time;
use App\Race\Infrastructure\Service\TimeSaver;
use Doctrine\ORM\EntityManagerInterface;

class TimeCreator
{

    private TimeSaver $timeSaver;
    private EntityManagerInterface $entityManager;
    private DriverRepository $driverRepository;

    public function __construct(TimeSaver $timeSaver, EntityManagerInterface $entityManager,
                                DriverRepository $driverRepository)
    {
        $this->timeSaver = $timeSaver;
        $this->entityManager = $entityManager;
        $this->driverRepository = $driverRepository;
    }

    /**
     * @param Race $race
     * @return array
     */
    public function setSimulatedParams(Race $race): array
    {
        $driversIds = $race->getDriversIds();
        $drivers = $this->driverRepository->findByIds($driversIds);
        $times = [];

        foreach ($drivers as $driver) {
            $time = $this->createSimulatedTime();
            $time->setDriverId($driver->getId());
            $race->addTime($time);
            $this->entityManager->persist($time);
            $this->entityManager->flush();
            $driver->addTimeId($time->getId())
                ->addRaceId($race->getId());

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