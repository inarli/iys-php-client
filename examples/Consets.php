<?php
use Iys\ConsentManagement\Enum\ConsentSource;
use Iys\ConsentManagement\Enum\ConsentStatus;
use Iys\ConsentManagement\Enum\ConsentType;
use Iys\ConsentManagement\Enum\RecipientType;

include __DIR__."/../vendor/autoload.php";
include 'Authentication.php';

// Create a single consent
$phoneNumbers = ['+905050000000','+905050000001','+905050000002','+905050000003','+905050000004','+905050000005'];
$emails = ['tarreau@me.com','avalon@hotmail.com','weidai@verizon.net','aukjan@me.com','matty@aol.com','suresh@me.com','grossman@comcast.net','mglee@outlook.com','adamk@att.net','boomzilla@outlook.com','nogin@yahoo.ca','crimsane@mac.com'];
$date = date('Y-m-d H:i:s');
$consentModel = $iys->consentManagement->generateConsent($emails[array_rand($emails)], ConsentType::EMAIL, ConsentSource::EMAIL, ConsentStatus::APPROVE,$date, RecipientType::INDIVIDUAL);
$result = $iys->consentManagement->createSingleConsent($consentModel);
dump("== Single ==");
dump($result);

// Create multiple consents
$multipleRecords[] = $iys->consentManagement->generateConsent($emails[array_rand($emails)], ConsentType::EMAIL, ConsentSource::EMAIL, ConsentStatus::APPROVE, $date, RecipientType::INDIVIDUAL);
$multipleRecords[] = $iys->consentManagement->generateConsent($phoneNumbers[array_rand($phoneNumbers)], ConsentType::CALL, ConsentSource::EMAIL, ConsentStatus::APPROVE, $date, RecipientType::TRADER);
$multipleRecords[] = $iys->consentManagement->generateConsent($phoneNumbers[array_rand($phoneNumbers)], ConsentType::MESSAGE, ConsentSource::EMAIL, ConsentStatus::APPROVE,$date, RecipientType::INDIVIDUAL);
$result = $iys->consentManagement->createAsyncMultipleConsent($multipleRecords);
dump("== Multi ==");
dump($result);

// Get Status of multiple consent status
$requestId = isset($result->getData()['requestId']) ? $result->getData()['requestId'] : null;
if ($requestId){
   $result = $iys->consentManagement->multipleConsentStatus($requestId);
   dump("== Multiple Consent Status ==");
   dump($result);
}

// Get Status a consent
$result = $iys->consentManagement->consentStatus('ilkaynarli@gmail.com', RecipientType::INDIVIDUAL, ConsentType::EMAIL);
dump("== Get Status a consent ==");
dump($result);

// Get changes
$result = $iys->consentManagement->getConsentChanges('HS',100);
dump("== Changes ==");
dump($result);


// Create bulk consents with csv file.
$iys->consentManagement->createFromCsv('example.csv');



