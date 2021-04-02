<?php declare(strict_types=1);


namespace App\Services;


use GusApi\SearchReport;

/**
 * Class GusService
 * @package App\Services
 */
class GusService
{
    /**
     * Transforms error response string into key - value array. Response
     * has attributes separated by new line, and each of them is separated from
     * value by colon. e.g. "attr1:abc\nattr2:xyz"
     *
     * @param $message
     * @return array
     */
    public function getErrorMessage(string $message): array
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

    /**
     * Transforms SearchReport object into necessary data array
     *
     * @param SearchReport $data
     * @return array
     */
    public function getNipDataArray(SearchReport $data): array
    {
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
