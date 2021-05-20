<?php


namespace App\Shared\Infrastructure\Service;



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
}