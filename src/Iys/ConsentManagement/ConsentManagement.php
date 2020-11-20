<?php

namespace Iys\ConsentManagement;

use Iys\AbstractEndpoint;
use Iys\ConsentManagement\Model\ConsentModel;
use Iys\HttpResponse;

class ConsentManagement extends AbstractEndpoint
{
    private $endpoint = '/consents';
    private $singleStatusEndpoint = '/consents/status';
    private $multipleCreateEndpoint = '/consents/request';
    private $multipleStatusEndpoint = '/consents/request/';
    private $consentChangesEndpoint = '/consents/changes';

    /**
     * @param $recipient
     * @param $type
     * @param $source
     * @param $status
     * @param $consentDate
     * @param null $recipientType
     * @param null $retailerCode
     * @param array $retailerAccess
     * @return ConsentModel
     */
    public function generateConsent($recipient, $type, $source, $status, $consentDate, $recipientType = null, $retailerCode = null, array $retailerAccess = []){
        $consent = new ConsentModel();
        $consent->setRecipient($recipient)
            ->setType($type)
            ->setSource($source)
            ->setStatus($status)
            ->setConsentDate($consentDate)
            ->setRecipientType($recipientType)
            ->setRetailerCode($retailerCode)
            ->setRetailerAccess($retailerAccess);
        return $consent;
    }

    /**
     * @param ConsentModel $consent
     * @return HttpResponse
     * @throws \Exception
     */
    public function createSingleConsent(ConsentModel $consent){
        return $this->httpClient->post($this->endpoint, $consent->toArray());
    }

    /**
     * @param $recipient
     * @param $recipientType
     * @param $type
     * @return HttpResponse
     * @throws \Exception
     */
    public function consentStatus($recipient, $recipientType, $type){
        $payload = get_defined_vars();
        return $this->httpClient->post($this->singleStatusEndpoint, $payload);
    }

    /**
     * @param array $consents
     * @return HttpResponse
     * @throws \Exception
     */
    public function createAsyncMultipleConsent(array $consents){
        $consents = array_map(function ($item){
            if ($item instanceof ConsentModel){
                return $item->toArray();
            }
        }, $consents);
        return $this->httpClient->post($this->multipleCreateEndpoint, $consents);
    }


    /**
     * @param $requestId
     * @return HttpResponse
     * @throws \Exception
     */
    public function multipleConsentStatus($requestId){
        return $this->httpClient->get($this->multipleStatusEndpoint.$requestId);
    }

    /**
     * @param null $source
     * @param null $limit
     * @return HttpResponse
     * @throws \Exception
     */
    public function getConsentChanges($source = null, $limit = null){
        $query = http_build_query(get_defined_vars());
        return $this->httpClient->get($this->consentChangesEndpoint.'?'.$query);
    }

    /**
     * Recommend : use on the cli
     * @param string $path
     * @param int $batchCount
     * @throws \Exception
     */
    public function createFromCsv($path = '/Users/ilkaynarli/Desktop/projects/iys-bulk/proje/csv/data.csv', $batchCount = 100){

        $handle = fopen($path, 'r');
        if ($handle === false){
            throw new \Exception('File does not open');
        }
        $totalLine = count(file($path));
        $lastCluster = $totalLine % $batchCount;
        $pageCount = (int)ceil($totalLine / $batchCount);
        $page = 0;
        $processedCount = 0;
        $consents = [];
        while(($data = fgetcsv($handle)) !== false){
            if (count($data) > 0){
                $consents[] = $this->generateConsent($data[2], $data[0], $data[1], $data[3], $data[4],$data[5]);
            }
            $consentCount = count($consents);
            if ($consentCount === $batchCount || ($pageCount - 1 === $page && $lastCluster === $consentCount)) {
                $this->sendMultipleCreate($consents);
                $processedCount += $consentCount;
                $this->console->debug('Total processed record count : '.$processedCount);
                $consents = [];
                $page++;
            }
        }
    }

    /**
     * @param array $consents
     * @return bool
     * @throws \Exception
     */
    private function sendMultipleCreate(array $consents): bool
    {
        $this->console->debug(count($consents) . ' records sending...');
        $response = $this->createAsyncMultipleConsent($consents);
        $this->console->success(count($consents) . ' records sent!');
        if (!$response->isSuccessful()){
            if ($response->getErrors()){
                dump(count($response->getErrors()));
                foreach ($response->getErrors() as $error){
                    $index = $error['index'];
                    $errorConsent = $consents[$index] ?? null;
                    if ($errorConsent instanceof ConsentModel){
                        $this->console->error(json_encode($errorConsent->toArray()). ' Hata : '.$error['message']);
                    }
                }
            }
        }
        return $response->isSuccessful();
    }
}
