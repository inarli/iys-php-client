<?php

namespace Iys\ConsentManagement\Model;

class ConsentModel
{

    private $recipient;

    private $type;

    private $source;

    private $status;

    private $consentDate;

    private $recipientType;

    private $retailerCode;

    private $retailerAccess = [];

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     * @return ConsentModel
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return ConsentModel
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     * @return ConsentModel
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return ConsentModel
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConsentDate()
    {
        return $this->consentDate;
    }

    /**
     * @param mixed $consentDate
     * @return ConsentModel
     */
    public function setConsentDate($consentDate)
    {
        $this->consentDate = $consentDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecipientType()
    {
        return $this->recipientType;
    }

    /**
     * @param mixed $recipientType
     * @return ConsentModel
     */
    public function setRecipientType($recipientType)
    {
        $this->recipientType = $recipientType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRetailerCode()
    {
        return $this->retailerCode;
    }

    /**
     * @param mixed $retailerCode
     * @return ConsentModel
     */
    public function setRetailerCode($retailerCode = null)
    {
        $this->retailerCode = $retailerCode;
        return $this;
    }

    /**
     * @return array
     */
    public function getRetailerAccess()
    {
        return $this->retailerAccess;
    }

    /**
     * @param array $retailerAccess
     * @return ConsentModel
     */
    public function setRetailerAccess(array $retailerAccess = [])
    {
        $this->retailerAccess = $retailerAccess;
        return $this;
    }

    public function toArray(){

        return [
            'recipient' => $this->getRecipient(),
            'type' => $this->getType(),
            'source' => $this->getSource(),
            'status' => $this->getStatus(),
            'consentDate' => $this->getConsentDate(),
            'recipientType' => $this->getRecipientType(),
            'retailerCode' => $this->getRetailerCode(),
            'retailerAccess' => $this->getRetailerAccess(),
        ];
    }

}
