<?php


namespace App\Shared\Infrastructure\Service;


use Doctrine\Common\Collections\ArrayCollection;

class FormDataGetter
{
    public function getRacesNames(array $races): array
    {
        $racesNames = [];

        foreach ($races as $race) {
            $racesNames[] = $race['name'];
        }

        return $racesNames;
    }

    public function getDriversIds(ArrayCollection $drivers): array
    {
        $driversIds = [];
        foreach ($drivers as $driver) {
            $driversIds[] = $driver->getId();
        }

        return $driversIds;
    }
}