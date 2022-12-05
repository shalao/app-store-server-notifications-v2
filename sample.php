<?php

use Readdle\AppStoreServerNotificationsV2\CertificateManager;
use Readdle\AppStoreServerNotificationsV2\Exception\AppStoreServerNotificationException;
use Readdle\AppStoreServerNotificationsV2\ResponseBodyV2;

require_once 'vendor/autoload.php';
$path = __DIR__ . "/test/bb.txt";
$notification = file_get_contents($path);


try {
    CertificateManager::addCertificateSource('AppleRootCA', 'https://www.apple.com/certificateauthority/AppleRootCA-G3.cer');
    $rootCertificate = CertificateManager::convertDerToPem(CertificateManager::getCertificate('AppleRootCA'));
    $responseBodyV2 = ResponseBodyV2::createFromRawNotification($notification, $rootCertificate);
} catch (AppStoreServerNotificationException $e) {
    exit($e->getMessage());
}
var_dump($responseBodyV2->getAppMetadata());
// var_dump($responseBodyV2->getAppMetadata()->getRenewalInfo()->toArray());
 var_dump($responseBodyV2->getAppMetadata()->getTransactionInfo()->toArray());
// var_dump($responseBodyV2->getNotificationType());
echo var_export($responseBodyV2, true) . "\n";
