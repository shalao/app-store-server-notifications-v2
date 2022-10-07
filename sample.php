<?php

use Readdle\AppStoreServerNotificationsV2\CertificateManager;
use Readdle\AppStoreServerNotificationsV2\Exception\AppStoreServerNotificationException;
use Readdle\AppStoreServerNotificationsV2\ResponseBodyV2;

require_once 'vendor/autoload.php';

$notification = '{"signedPayload":"..."}';

try {
    CertificateManager::addCertificateSource('AppleRootCA', 'https://www.apple.com/certificateauthority/AppleRootCA-G3.cer');
    $rootCertificate = CertificateManager::convertDerToPem(CertificateManager::getCertificate('AppleRootCA'));
    $responseBodyV2 = ResponseBodyV2::createFromRawNotification($notification, $rootCertificate);
} catch (AppStoreServerNotificationException $e) {
    exit($e->getMessage());
}

echo var_export($responseBodyV2, true) . "\n";
