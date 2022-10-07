<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerNotificationsV2;

use Exception;
use Jose\Component\Checker\HeaderCheckerManager;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\JWS;
use Jose\Component\Signature\JWSTokenSupport;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Readdle\AppStoreServerNotificationsV2\Exception\AppStoreServerNotificationException;

final class JWT
{
    const HEADER_ALG = 'alg';
    const HEADER_X5C = 'x5c';

    const REQUIRED_JWS_HEADERS = [
        self::HEADER_ALG,
        self::HEADER_X5C,
    ];

    private string $jwt;
    private ?string $rootCertificate = null;
    private JWS $jws;
    private array $x509Chain;

    public function __construct(string $jwt, ?string $rootCertificate = null)
    {
        $this->jwt = $jwt;

        if (!is_null($rootCertificate)) {
            $this->rootCertificate = CertificateManager::formatPemCertificate(trim($rootCertificate));
        }
    }

    /**
     * @throws AppStoreServerNotificationException
     */
    public function parseAndVerify(): array
    {
        $this->parseJWT();
        $this->checkHeaders();
        $this->extractAndVerifyX509Chain();
        $this->verifySignature();

        return json_decode($this->jws->getPayload(), true);
    }

    /**
     * @throws AppStoreServerNotificationException
     */
    private function parseJWT(): void
    {
        $serializerManager = new JWSSerializerManager([new CompactSerializer()]);

        try {
            $this->jws = $serializerManager->unserialize($this->jwt);
        } catch (Exception $e) {
            throw new AppStoreServerNotificationException('JWT unserialization error: ' . $e->getMessage());
        }
    }

    /**
     * @throws AppStoreServerNotificationException
     */
    private function checkHeaders(): void
    {
        // we don't know which algorithm was used to sign this JWS
        $checkers = [];

        // we do know that this is JWS according to Apple's documentation
        $tokenTypes = [new JWSTokenSupport()];

        try {
            (new HeaderCheckerManager($checkers, $tokenTypes))->check($this->jws, 0, self::REQUIRED_JWS_HEADERS);
        } catch (Exception $e) {
            throw new AppStoreServerNotificationException('JWT headers error: ' . $e->getMessage());
        }
    }

    /**
     * @throws AppStoreServerNotificationException
     */
    private function extractAndVerifyX509Chain(): void
    {
        $rawChain = $this->jws->getSignature(0)->getProtectedHeaderParameter('x5c');
        $chain = [
            'certificate' => CertificateManager::formatPemCertificate(trim($rawChain[0])),
            'intermediate' => CertificateManager::formatPemCertificate(trim($rawChain[1])),
            'root' => CertificateManager::formatPemCertificate(trim($rawChain[2])),
        ];

        if (openssl_x509_verify($chain['certificate'], $chain['intermediate']) !== 1) {
            throw new AppStoreServerNotificationException('JWT X509 chain error: intermediate certificate is not a signer of the main certificate');
        }

        if (openssl_x509_verify($chain['intermediate'], $chain['root']) !== 1) {
            throw new AppStoreServerNotificationException('JWT X509 chain error: root certificate is not a signer of the intermediate certificate');
        }

        if (!is_null($this->rootCertificate) && openssl_x509_verify($chain['root'], $this->rootCertificate) !== 1) {
            throw new AppStoreServerNotificationException('JWT X509 chain error: JWT root certificate differs from the one is set as a sample');
        }

        $this->x509Chain = $chain;
    }

    /**
     * @throws AppStoreServerNotificationException
     */
    private function verifySignature(): void
    {
        $algClass = self::getAlgClass($this->jws);
        $jwk = JWKFactory::createFromCertificate($this->x509Chain['certificate']);

        try {
            $verified = (new JWSVerifier(new AlgorithmManager([new $algClass])))->verifyWithKey($this->jws, $jwk, 0);
        } catch (Exception $e) {
            throw new AppStoreServerNotificationException('JWT signature error: ' . $e->getMessage());
        }

        if (!$verified) {
            throw new AppStoreServerNotificationException('JWT signature error: sign verification failed');
        }
    }

    /**
     * @throws AppStoreServerNotificationException
     */
    private static function getAlgClass(JWS $jws): string
    {
        $alg = $jws->getSignature(0)->getProtectedHeaderParameter('alg');
        $algClass = 'Jose\Component\Signature\Algorithm\\' . $alg;

        if (!class_exists($algClass)) {
            throw new AppStoreServerNotificationException("JWT parsing error: unknown algorithm \"$alg\"");
        }

        return $algClass;
    }
}
