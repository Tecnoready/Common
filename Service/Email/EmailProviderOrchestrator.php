<?php

namespace Tecnoready\Common\Service\Email;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

/**
 * Orquestador de proveedores de correo (failover, cuotas por proveedor).
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class EmailProviderOrchestrator
{
    private const EXTRA_PROVIDER = 'delivery.provider';
    private const EXTRA_ATTEMPTS_BY_PROVIDER = 'delivery.attempts_by_provider';
    private const EXTRA_LAST_ERROR = 'delivery.last_error';
    private const EXTRA_HISTORY = 'delivery.history';

    private array $providers;
    private int $failuresBeforeSwitch;
    private int $activeProviderIndex = 0;
    private array $sentPerProvider = [];

    public function __construct(array $providers = [], int $failuresBeforeSwitch = 2)
    {
        $this->providers = $this->normalizeProviders($providers);
        $this->failuresBeforeSwitch = max(1, $failuresBeforeSwitch);
    }

    public function isEnabled(): bool
    {
        return \count($this->providers) > 0;
    }

    public function sendQueueEmail($emailQueue, Email $message): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $totalProviders = \count($this->providers);
        $providersExhausted = 0;

        while ($providersExhausted < $totalProviders) {
            $provider = $this->pickProvider();
            if ($provider === null) {
                return false;
            }

            $providerName = $provider['name'];
            $failuresOnThisProvider = 0;

            while ($failuresOnThisProvider < $this->failuresBeforeSwitch) {
                $this->registerAttempt($emailQueue, $providerName);
                try {
                    $mailer = new Mailer(Transport::fromDsn($provider['dsn']));
                    $mailer->send($message);

                    $this->sentPerProvider[$providerName] = ($this->sentPerProvider[$providerName] ?? 0) + 1;
                    $this->safeSetExtraData($emailQueue, self::EXTRA_PROVIDER, $providerName);

                    return true;
                } catch (\Throwable $exception) {
                    $failuresOnThisProvider++;
                    $this->safeSetExtraData($emailQueue, self::EXTRA_LAST_ERROR, $exception->getMessage());
                    $this->appendHistory($emailQueue, $providerName, $exception->getMessage());
                }
            }

            $this->switchToNextProvider();
            $providersExhausted++;
        }

        return false;
    }

    private function normalizeProviders(array $providers): array
    {
        $result = [];
        foreach ($providers as $name => $providerConfig) {
            if (!\is_array($providerConfig)) {
                continue;
            }

            $dsn = (string) ($providerConfig['dsn'] ?? '');
            if ($dsn === '') {
                continue;
            }

            $result[] = [
                'name' => (string) $name,
                'dsn' => $dsn,
                'max_emails' => isset($providerConfig['max_emails']) ? (int) $providerConfig['max_emails'] : 0,
            ];
        }

        return array_values($result);
    }

    private function pickProvider(): ?array
    {
        if (!$this->isEnabled()) {
            return null;
        }

        $total = \count($this->providers);
        $checked = 0;
        while ($checked < $total) {
            $provider = $this->providers[$this->activeProviderIndex];
            $name = $provider['name'];
            $maxEmails = (int) $provider['max_emails'];
            $sent = (int) ($this->sentPerProvider[$name] ?? 0);

            if ($maxEmails === 0 || $sent < $maxEmails) {
                return $provider;
            }

            $this->switchToNextProvider();
            $checked++;
        }

        return null;
    }

    private function switchToNextProvider(): void
    {
        $count = \count($this->providers);
        if ($count === 0) {
            return;
        }

        $this->activeProviderIndex = ($this->activeProviderIndex + 1) % $count;
    }

    private function registerAttempt($emailQueue, string $providerName): void
    {
        $attemptsByProvider = $this->safeGetExtraData($emailQueue, self::EXTRA_ATTEMPTS_BY_PROVIDER, []);
        if (!\is_array($attemptsByProvider)) {
            $attemptsByProvider = [];
        }

        $attemptsByProvider[$providerName] = (int) ($attemptsByProvider[$providerName] ?? 0) + 1;
        $this->safeSetExtraData($emailQueue, self::EXTRA_ATTEMPTS_BY_PROVIDER, $attemptsByProvider);
    }

    private function appendHistory($emailQueue, string $providerName, string $error): void
    {
        $history = $this->safeGetExtraData($emailQueue, self::EXTRA_HISTORY, []);
        if (!\is_array($history)) {
            $history = [];
        }

        $history[] = [
            'provider' => $providerName,
            'error' => $error,
            'at' => (new \DateTime())->format(\DateTime::ATOM),
        ];

        if (\count($history) > 20) {
            $history = \array_slice($history, -20);
        }

        $this->safeSetExtraData($emailQueue, self::EXTRA_HISTORY, $history);
    }

    private function safeGetExtraData($emailQueue, string $key, $default = null)
    {
        if (\is_object($emailQueue) && method_exists($emailQueue, 'getExtraData')) {
            return $emailQueue->getExtraData($key, $default);
        }

        return $default;
    }

    private function safeSetExtraData($emailQueue, string $key, $value): void
    {
        if (\is_object($emailQueue) && method_exists($emailQueue, 'setExtraData')) {
            $emailQueue->setExtraData($key, $value);
        }
    }
}
