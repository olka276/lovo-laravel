<?php


namespace App\Services;


/**
 * Class GusService
 * @package App\Services
 */
class GusService
{
    /**
     * Refactors response string from API gus to key=>value array
     *
     * @param $message
     * @return array
     */
    public function refactorResponseMessage($message): array
    {
        $messageArray = [];
        $messagePart = explode("\n", $message);

        foreach ($messagePart as $item) {
            $separatedByColon = explode(':', $item);

            if(count($separatedByColon) == 2) {
                $messageArray[$separatedByColon[0]] = $separatedByColon[1];
            }
        }

        return $messageArray;
    }

    public function getNipDataArray($data) {
        return [
            'nip'           => $data->getNip(),
            'regon'         => $data->getRegon(),
            'regon14'       => $data->getRegon14(),
            'nazwa'         => $data->getName(),
            'wojewodztwo'   => $data->getProvince(),
            'powiat'        => $data->getDistrict(),
            'gmina'         => $data->getCommunity(),
            'miasto'        => $data->getCity(),
            'ulica'         => $data->getStreet(),
            'numer_domu'    => $data->getPropertyNumber(),
            'numer_lokalu'  => $data->getApartmentNumber(),
            'kod_pocztowy'  => $data->getZipCode(),
            'poczta'        => $data->getPostCity()
        ];
    }
}
