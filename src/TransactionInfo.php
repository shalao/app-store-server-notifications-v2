<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerNotificationsV2;

final class TransactionInfo
{
    /**
     * Indicates that the notification applies to testing in the sandbox environment.
     */
    public const ENVIRONMENT__SANDBOX = 'Sandbox';

    /**
     * Indicates that the notification applies to the production environment.
     */
    public const ENVIRONMENT__PRODUCTION = 'Production';

    /**
     * The transaction belongs to a family member who benefits from the service.
     */
    public const IN_APP_OWNERSHIP_TYPE__FAMILY_SHARED = 'FAMILY_SHARED';

    /**
     * The transaction belongs to the purchaser.
     */
    public const IN_APP_OWNERSHIP_TYPE__PURCHASED = 'PURCHASED';

    /**
     * An introductory offer.
     */
    public const OFFER_TYPE__INTRODUCTORY = 1;

    /**
     * A promotional offer.
     */
    public const OFFER_TYPE__PROMOTIONAL = 2;

    /**
     * An offer with a subscription offer code.
     */
    public const OFFER_TYPE__SUBSCRIPTION = 3;

    /**
     * Apple Support refunded the transaction on behalf of the customer for other reasons; for example, an accidental purchase.
     */
    public const REVOCATION_REASON__OTHER = 0;

    /**
     * Apple Support refunded the transaction on behalf of the customer due to an actual or perceived issue within your app.
     */
    public const REVOCATION_REASON__ISSUE_WITHIN_APP = 1;

    /**
     * An auto-renewable subscription.
     */
    public const TYPE__AUTO_RENEWABLE_SUBSCRIPTION = 'Auto-Renewable Subscription';

    /**
     * A non-consumable in-app purchase.
     */
    public const TYPE__NON_CONSUMABLE = 'Non-Consumable';

    /**
     * A consumable in-app purchase.
     */
    public const TYPE__CONSUMABLE = 'Consumable';

    /**
     * A non-renewing subscription.
     */
    public const TYPE__NON_RENEWING_SUBSCRIPTION = 'Non-Renewing Subscription';

    /**
     * A UUID that associates the transaction with a user on your own service.
     * If the app doesn't provide an appAccountToken, this string is empty.
     */
    private string $appAccountToken;

    /**
     * The bundle identifier of the app.
     */
    private string $bundleId;

    /**
     * The server environment, either sandbox or production.
     */
    private string $environment;

    /**
     * The UNIX time, in milliseconds, the subscription expires or renews.
     */
    private int $expiresDate;

    /**
     * A string that describes whether the transaction was purchased by the user, or is available to them through Family Sharing.
     */
    private string $inAppOwnershipType;

    /**
     * A Boolean value that indicates whether the user upgraded to another subscription.
     */
    private bool $isUpgraded;

    /**
     * The identifier that contains the promo code or the promotional offer identifier.
     * NOTE: This field applies only when the offerType is either promotional offer or subscription offer code.
     */
    private string $offerIdentifier;

    /**
     * A value that represents the promotional offer type.
     */
    private string $offerType;

    /**
     * The UNIX time, in milliseconds, that represents the purchase date of the original transaction identifier.
     */
    private int $originalPurchaseDate;

    /**
     * The transaction identifier of the original purchase.
     */
    private string $originalTransactionId;

    /**
     * The product identifier of the in-app purchase.
     */
    private string $productId;

    /**
     * The UNIX time, in milliseconds, that the App Store charged the user's account for a purchase,
     * restored product, subscription, or subscription renewal after a lapse.
     */
    private int $purchaseDate;

    /**
     * The number of consumable products the user purchased.
     */
    private int $quantity;

    /**
     * The UNIX time, in milliseconds, that the App Store refunded the transaction or revoked it from Family Sharing.
     */
    private int $revocationDate;

    /**
     * The reason that the App Store refunded the transaction or revoked it from Family Sharing.
     */
    private int $revocationReason;

    /**
     * The UNIX time, in milliseconds, that the App Store signed the JSON Web Signature (JWS) data.
     */
    private int $signedDate;

    /**
     * The identifier of the subscription group the subscription belongs to.
     */
    private string $subscriptionGroupIdentifier;

    /**
     * The unique identifier of the transaction.
     */
    private string $transactionId;

    /**
     *
     */
    private string $type;

    /**
     * The unique identifier of subscription purchase events across devices, including subscription renewals.
     */
    private string $webOrderLineItemId;

    private function __construct()
    {
        // just a stub which prevents this class from direct instantiation
    }

    public static function createFromPayload(array $payload): self
    {
        $transactionInfo = new self();
        $typeCaster = Helper::arrayTypeCastGenerator($payload, [
            'int' => [
                'expiresDate', 'originalPurchaseDate', 'purchaseDate',
                'quantity', 'revocationDate', 'revocationReason', 'signedDate',
            ],
            'bool' => [
                'isUpgraded',
            ],
            'string' => [
                'appAccountToken', 'bundleId', 'environment', 'inAppOwnershipType',
                'offerIdentifier', 'offerType', 'originalTransactionId', 'productId',
                'subscriptionGroupIdentifier', 'transactionId', 'type', 'webOrderLineItemId',
            ],
        ]);

        foreach ($typeCaster as $prop => $value) {
            $transactionInfo->$prop = $value;
        }

        return $transactionInfo;
    }

    public function getAppAccountToken(): string
    {
        return $this->appAccountToken;
    }

    public function getBundleId(): string
    {
        return $this->bundleId;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getExpiresDate(): int
    {
        return $this->expiresDate;
    }

    public function getInAppOwnershipType(): string
    {
        return $this->inAppOwnershipType;
    }

    public function getIsUpgraded(): bool
    {
        return $this->isUpgraded;
    }

    public function getOfferIdentifier(): string
    {
        return $this->offerIdentifier;
    }

    public function getOfferType(): string
    {
        return $this->offerType;
    }

    public function getOriginalPurchaseDate(): int
    {
        return $this->originalPurchaseDate;
    }

    public function getOriginalTransactionId(): string
    {
        return $this->originalTransactionId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getPurchaseDate(): int
    {
        return $this->purchaseDate;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getRevocationDate(): int
    {
        return $this->revocationDate;
    }

    public function getRevocationReason(): int
    {
        return $this->revocationReason;
    }

    public function getSignedDate(): int
    {
        return $this->signedDate;
    }

    public function getSubscriptionGroupIdentifier(): string
    {
        return $this->subscriptionGroupIdentifier;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getWebOrderLineItemId(): string
    {
        return $this->webOrderLineItemId;
    }
}
