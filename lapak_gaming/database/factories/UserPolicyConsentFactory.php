<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserPolicyConsent;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPolicyConsentFactory extends Factory
{
    protected $model = UserPolicyConsent::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'policy_type' => $this->faker->randomElement(['terms_of_service', 'privacy_policy', 'data_processing']),
            'version' => $this->faker->randomElement(['1.0', '1.1', '2.0']),
            'agreed_at' => $this->faker->dateTimeBetween('-1 year'),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'consent_status' => $this->faker->randomElement(['agreed', 'declined', 'pending']),
        ];
    }

    public function agreed(): self
    {
        return $this->state(fn (array $attributes) => [
            'consent_status' => 'agreed',
            'agreed_at' => now(),
        ]);
    }

    public function declined(): self
    {
        return $this->state(fn (array $attributes) => [
            'consent_status' => 'declined',
            'agreed_at' => now(),
        ]);
    }

    public function pending(): self
    {
        return $this->state(fn (array $attributes) => [
            'consent_status' => 'pending',
            'agreed_at' => null,
        ]);
    }

    public function termsOfService(): self
    {
        return $this->state(fn (array $attributes) => [
            'policy_type' => 'terms_of_service',
        ]);
    }

    public function privacyPolicy(): self
    {
        return $this->state(fn (array $attributes) => [
            'policy_type' => 'privacy_policy',
        ]);
    }

    public function dataProcessing(): self
    {
        return $this->state(fn (array $attributes) => [
            'policy_type' => 'data_processing',
        ]);
    }
}
