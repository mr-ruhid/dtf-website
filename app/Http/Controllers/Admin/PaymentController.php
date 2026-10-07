<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Payment\Registry\PaymentGatewayRegistry;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentGatewayRegistry $registry;

    public function __construct(PaymentGatewayRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function index()
    {
        $gateways = $this->registry->all();
        $methods = PaymentMethod::pluck('enabled', 'gateway_id')->toArray();

        return view('admin.payments.index', compact('gateways', 'methods'));
    }

    public function show(string $id)
    {
        $gateway = $this->registry->get($id);

        if (!$gateway) {
            return redirect()
                ->route('admin.payments.index')
                ->withErrors(['error' => "Payment gateway [{$id}] not found."]);
        }

        $schema = $gateway->getSettingsSchema();
        $settings = $gateway->getSettings();
        $enabled = $gateway->isEnabled();
        $mode = $gateway->getMode();

        return view('admin.payments.show', compact('gateway', 'schema', 'settings', 'enabled', 'mode'));
    }

    public function update(Request $request, string $id)
    {
        $gateway = $this->registry->get($id);

        if (!$gateway) {
            return redirect()
                ->route('admin.payments.index')
                ->withErrors(['error' => "Payment gateway [{$id}] not found."]);
        }

        $schema = $gateway->getSettingsSchema();
        $rules = [];
        $attributes = [];

        foreach ($schema as $key => $field) {
            $fieldRules = [];
            $type = $field['type'] ?? 'text';

            if (($field['required'] ?? false) === true) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($type === 'number') {
                $fieldRules[] = 'numeric';
                if (isset($field['min'])) $fieldRules[] = 'min:' . $field['min'];
                if (isset($field['max'])) $fieldRules[] = 'max:' . $field['max'];
            } elseif ($type === 'email') {
                $fieldRules[] = 'email';
            } elseif ($type === 'url') {
                $fieldRules[] = 'url';
            } else {
                $fieldRules[] = 'string';
            }

            $rules['settings.' . $key] = $fieldRules;
            $attributes['settings.' . $key] = $field['label'] ?? $key;
        }

        $rules['mode'] = 'nullable|in:test,live';
        $rules['enabled'] = 'nullable|boolean';

        $validated = $request->validate($rules, [], $attributes);

        $settings = $validated['settings'] ?? [];

        foreach ($schema as $key => $field) {
            if (!array_key_exists($key, $settings) && isset($field['default'])) {
                $settings[$key] = $field['default'];
            }
        }

        $gateway->setSettings($settings);

        $method = PaymentMethod::firstOrCreate(
            ['gateway_id' => $id],
            [
                'name' => $gateway->getName(),
                'enabled' => false,
                'mode' => 'test',
                'settings' => [],
            ]
        );

        $method->update([
            'name' => $gateway->getName(),
            'mode' => $validated['mode'] ?? $method->mode,
            'enabled' => $request->boolean('enabled'),
        ]);

        return redirect()
            ->route('admin.payments.show', $id)
            ->with('status', $gateway->getName() . ' settings updated successfully.');
    }

    public function toggle(string $id)
    {
        $gateway = $this->registry->get($id);

        if (!$gateway) {
            return redirect()
                ->route('admin.payments.index')
                ->withErrors(['error' => "Payment gateway [{$id}] not found."]);
        }

        $method = PaymentMethod::firstOrCreate(
            ['gateway_id' => $id],
            [
                'name' => $gateway->getName(),
                'enabled' => false,
                'mode' => 'test',
                'settings' => [],
            ]
        );

        $method->update(['enabled' => !$method->enabled]);

        return back()->with('status', $gateway->getName() . ($method->enabled ? ' enabled.' : ' disabled.'));
    }
}
