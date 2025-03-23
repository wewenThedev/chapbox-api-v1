<?php

namespace Database\Factories;

use App\Models\{Order, PaymentMethod, Promo, Invoice};
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'method_id' => PaymentMethod::factory(),
            'paid_at' => null,
            'status' => 'processing',
            'code_promo_id' => fake()->optional(0.3)->passthrough(fn () => Promo::factory()),
            'total_paid' => fake()->randomFloat(2, 1000, 50000), // Montant en XOF
            'details' => function (array $attributes) {
                $method = PaymentMethod::find($attributes['method_id']);
                
                // Génère des détails spécifiques selon la méthode
                return match($method->name) {
                    'MTN Mobile Money' => [
                        'transaction_id' => 'TX' . fake()->unique()->numerify('#######'),
                        'phone_number' => fake()->regexify('(229)(66|96|67|97)[0-9]{6}')
                    ],
                    'Visa Card' => [
                        'last_four' => fake()->numerify('####'),
                        'authorization_code' => 'AUTH' . fake()->unique()->lexify('????')
                    ],
                    'Bank Transfer (BOA)' => [
                        'reference' => 'REF' . fake()->unique()->bothify('??##??##')
                    ],
                    default => []
                };
            },
            'invoice_id' => Invoice::factory()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($payment) {
            $status = fake()->randomElement(['processing', 'failed', 'successful', 'canceled']);
            
            $payment->update([
                'status' => $status,
                'paid_at' => $status === 'successful' ? now() : null
            ]);
        });
    }
}