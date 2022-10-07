<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerNotificationsV2;

use Readdle\AppStoreServerNotificationsV2\Exception\AppStoreServerNotificationException;

final class CertificateManager
{
    private static array $certificateSources = [];

    /**
     * @throws AppStoreServerNotificationException
     */
    public static function addCertificateSource(string $name, string $source, bool $cacheable = false, int $ttl = 24 * 60 * 60): void
    {
        if (array_key_exists($name, self::$certificateSources)) {
            throw new AppStoreServerNotificationException("Certificate source with name \"$name\" already exists");
        }

        self::$certificateSources[$name] = [
            'source' => $source,
            'cacheable' => $cacheable,
            'ttl' => $ttl,
        ];
    }

    /**
     * @throws AppStoreServerNotificationException
     */
    public static function getCertificate(string $name): string
    {
        if (!array_key_exists($name, self::$certificateSources)) {
            throw new AppStoreServerNotificationException("Certificate source with name \"$name\" does not exist");
        }

        // TODO: make it nicer
        return file_get_contents(self::$certificateSources[$name]['source']);
    }

    public static function convertDerToPem(string $der): string
    {
        return chunk_split(base64_encode($der), 64);
    }

    public static function formatPemCertificate(string $certificate): string
    {
        $prefix = "-----BEGIN CERTIFICATE-----\n";
        $postfix = "\n-----END CERTIFICATE-----";

        if (str_starts_with($certificate, $prefix)) {
            return $certificate;
        }

        return $prefix . $certificate . $postfix;
    }
}
