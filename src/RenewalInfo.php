<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerNotificationsV2;

final class RenewalInfo
{
    /**
     * Automatic renewal is off.
     * The customer has turned off automatic renewal for the subscription, and it won’t renew at the end of the current subscription period.
     */
    const AUTO_RENEW_STATUS__OFF = 0;

    /**
     * Automatic renewal is on.
     * The subscription renews at the end of the current subscription period.
     */
    const AUTO_RENEW_STATUS__ON = 1;

    /**
     * Indicates that the notification applies to testing in the sandbox environment.
     */
    public const ENVIRONMENT__SANDBOX = 'Sandbox';

    /**
     * Indicates that the notification applies to the production environment.
     */
    public const ENVIRONMENT__PRODUCTION = 'Production';

    /**
     * The customer canceled their subscription.
     */
    public const EXPIRATION_INTENT__CANCEL = 1;

    /**
     * Billing error; for example, the customer’s payment information is no longer valid.
     */
    public const EXPIRATION_INTENT__BILLING_ERROR = 2;

    /**
     * The customer didn't consent to an auto-renewable subscription price increase
     * that requires customer consent, allowing the subscription to expire.
     */
    public const EXPIRATION_INTENT__PRICE_INCREASE = 3;

    /**
     * The product wasn’t available for purchase at the time of renewal.
     */
    public const EXPIRATION_INTENT__UNAVAILABLE_PRODUCT = 4;

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
    public const OFFER_TYPE__SUBSCRIPTION_OFFER_CODE = 3;

    /**
     * The customer hasn't yet responded to an auto-renewable subscription price increase that requires customer consent.
     */
    public const PRICE_INCREASE_STATUS__NOT_RESPONDED = 0;

    /**
     * The customer consented to an auto-renewable subscription price increase that requires customer consent,
     * or the App Store has notified the customer of an auto-renewable subscription price increase that doesn't require consent.
     */
    public const PRICE_INCREASE_STATUS__CONSENTED = 1;

    /**
     * The product identifier of the product that renews at the next billing period.
     */
    private string $autoRenewProductId;

    /**
     * The renewal status for an auto-renewable subscription.
     */
    private int $autoRenewStatus;

    /**
     * The server environment, either sandbox or production.
     */
    private string $environment;

    /**
     * The reason a subscription expired.
     */
    private string $expirationIntent;

    /**
     * The time when the billing grace period for subscription renewals expires.
     */
    private int $gracePeriodExpiresDate;

    /**
     * The Boolean value that indicates whether the App Store is attempting to automatically renew an expired subscription.
     */
    private bool $isInBillingRetryPeriod;

    /**
     * The offer code or the promotional offer identifier.
     */
    private string $offerIdentifier;

    /**
     * The type of subscription offer.
     */
    private int $offerType;

    /**
     * The original transaction identifier of a purchase.
     */
    private string $originalTransactionId;

    /**
     * The status that indicates whether the auto-renewable subscription is subject to a price increase.
     */
    private int $priceIncreaseStatus;

    /**
     * The product identifier of the in-app purchase.
     */
    private string $productId;

    /**
     * The earliest start date of an auto-renewable subscription in a series of subscription purchases
     * that ignores all lapses of paid service that are 60 days or less.
     */
    private int $recentSubscriptionStartDate;

    /**
     * The UNIX time, in milliseconds, that the App Store signed the JSON Web Signature data.
     */
    private int $signedDate;

    private function __construct()
    {
        // just a stub which prevents this class from direct instantiation
    }

    public static function createFromPayload(array $payload): self
    {
        $renewalInfo = new self();
        $typeCaster = Helper::arrayTypeCastGenerator($payload, [
            'int' => [
                'autoRenewStatus', 'gracePeriodExpiresDate', 'offerType',
                'priceIncreaseStatus', 'recentSubscriptionStartDate', 'signedDate',
            ],
            'bool' => [
                'isInBillingRetryPeriod',
            ],
            'string' => [
                'autoRenewProductId', 'environment', 'expirationIntent',
                'offerIdentifier', 'originalTransactionId', 'productId',
            ],
        ]);

        foreach ($typeCaster as $prop => $value) {
            $renewalInfo->$prop = $value;
        }

        return $renewalInfo;
    }

    public function getAutoRenewProductId(): string
    {
        return $this->autoRenewProductId;
    }

    public function getAutoRenewStatus(): int
    {
        return $this->autoRenewStatus;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getExpirationIntent(): string
    {
        return $this->expirationIntent;
    }

    public function getGracePeriodExpiresDate(): int
    {
        return $this->gracePeriodExpiresDate;
    }

    public function getIsInBillingRetryPeriod(): bool
    {
        return $this->isInBillingRetryPeriod;
    }

    public function getOfferIdentifier(): string
    {
        return $this->offerIdentifier;
    }

    public function getOfferType(): int
    {
        return $this->offerType;
    }

    public function getOriginalTransactionId(): string
    {
        return $this->originalTransactionId;
    }

    public function getPriceIncreaseStatus(): int
    {
        return $this->priceIncreaseStatus;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getRecentSubscriptionStartDate(): int
    {
        return $this->recentSubscriptionStartDate;
    }

    public function getSignedDate(): int
    {
        return $this->signedDate;
    }
}
