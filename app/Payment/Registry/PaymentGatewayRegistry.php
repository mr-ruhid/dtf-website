<?php

namespace App\Payment\Registry;

use App\Payment\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class PaymentGatewayRegistry
{
    protected array $gateways = [];

    protected bool $discovered = false;

    protected string $gatewaysPath;

    public function __construct()
    {
        $this->gatewaysPath = app_path('Payment/Gateways');
    }

    public function discover(): void
    {
        if ($this->discovered) {
            return;
        }

        $manifests = $this->loadManifests();

        foreach ($manifests as $id => $manifest) {
            $gateway = $this->makeGateway($id, $manifest);

            if ($gateway) {
                $this->gateways[$id] = $gateway;
            }
        }

        $this->discovered = true;
    }

    public function all(): array
    {
        $this->discover();
        return $this->gateways;
    }

    public function get(string $id): ?PaymentGatewayInterface
    {
        $this->discover();
        return $this->gateways[$id] ?? null;
    }

    public function has(string $id): bool
    {
        $this->discover();
        return isset($this->gateways[$id]);
    }

    public function enabled(): array
    {
        $this->discover();

        return array_filter($this->gateways, function (PaymentGatewayInterface $gateway) {
            return $gateway->isEnabled();
        });
    }

    public function ids(): array
    {
        $this->discover();
        return array_keys($this->gateways);
    }

    public function flush(): void
    {
        $this->gateways = [];
        $this->discovered = false;
        Cache::forget($this->getCacheKey());
    }

    protected function loadManifests(): array
    {
        $key = $this->getCacheKey();

        return Cache::rememberForever($key, function () {
            return $this->scanManifests();
        });
    }

    protected function scanManifests(): array
    {
        if (!File::isDirectory($this->gatewaysPath)) {
            return [];
        }

        $manifests = [];

        foreach (File::directories($this->gatewaysPath) as $directory) {
            $manifestFile = $directory . DIRECTORY_SEPARATOR . 'manifest.php';

            if (!File::exists($manifestFile)) {
                continue;
            }

            try {
                $data = require $manifestFile;

                if (!is_array($data) || empty($data['id'])) {
                    continue;
                }

                $manifests[$data['id']] = $data;
            } catch (\Throwable $e) {
                if (config('app.debug')) {
                    logger()->error("Payment manifest error [{$manifestFile}]: " . $e->getMessage());
                }
            }
        }

        return $manifests;
    }

    protected function makeGateway(string $id, array $manifest): ?PaymentGatewayInterface
    {
        $class = $manifest['class'] ?? $this->guessClass($id);

        if (!class_exists($class)) {
            if (config('app.debug')) {
                logger()->warning("Payment gateway class not found: {$class}");
            }
            return null;
        }

        try {
            $instance = new $class($manifest);

            if (!$instance instanceof PaymentGatewayInterface) {
                if (config('app.debug')) {
                    logger()->warning("Payment gateway must implement PaymentGatewayInterface: {$class}");
                }
                return null;
            }

            return $instance;
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                logger()->error("Payment gateway instantiation failed [{$class}]: " . $e->getMessage());
            }
            return null;
        }
    }

    protected function guessClass(string $id): string
    {
        $studly = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $id)));

        return "App\\Payment\\Gateways\\{$studly}\\Gateway";
    }

    protected function getCacheKey(): string
    {
        $latest = 0;

        if (File::isDirectory($this->gatewaysPath)) {
            foreach (File::directories($this->gatewaysPath) as $directory) {
                $manifestFile = $directory . DIRECTORY_SEPARATOR . 'manifest.php';

                if (File::exists($manifestFile)) {
                    $latest = max($latest, File::lastModified($manifestFile));
                }
            }
        }

        return 'payment_gateways_' . $latest;
    }
}
