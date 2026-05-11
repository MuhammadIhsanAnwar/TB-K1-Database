<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPolicyConsent;
use Illuminate\Database\Seeder;

class UserPolicyConsentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users
        $users = User::withoutTrashed()->get();

        $policyTypes = ['terms_of_service', 'privacy_policy', 'data_processing'];

        foreach ($users as $user) {
            foreach ($policyTypes as $policyType) {
                // Check if consent already exists
                $exists = UserPolicyConsent::where('user_id', $user->id)
                    ->where('policy_type', $policyType)
                    ->exists();

                if (!$exists) {
                    UserPolicyConsent::create([
                        'user_id' => $user->id,
                        'policy_type' => $policyType,
                        'version' => '1.0',
                        'agreed_at' => $user->created_at ?? now(),
                        'ip_address' => '127.0.0.1',
                        'user_agent' => 'Seeder/1.0',
                        'consent_status' => 'agreed',
                    ]);
                }
            }
        }

        $this->command->info('User policy consents seeded successfully!');
    }
}
