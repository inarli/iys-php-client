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
}
