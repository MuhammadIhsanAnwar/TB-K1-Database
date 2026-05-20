<?php

namespace App\Http\Controllers;

use App\Models\UserPolicyConsent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PolicyConsentController extends Controller
{
    public function accept(Request $request): JsonResponse
    {
        $request->validate([
            'policy_type' => 'required|in:terms_of_service,privacy_policy,data_processing',
            'version' => 'required|string',
        ]);

        $user = Auth::user();

        $consent = UserPolicyConsent::create([
            'user_id' => $user->id,
            'policy_type' => $request->input('policy_type'),
            'version' => $request->input('version'),
            'agreed_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'consent_status' => 'agreed',
        ]);

        return response()->json([
            'message' => 'Policy consent recorded successfully',
            'data' => $consent,
        ]);
    }

    public function decline(Request $request): JsonResponse
    {
        $request->validate([
            'policy_type' => 'required|in:terms_of_service,privacy_policy,data_processing',
            'version' => 'required|string',
        ]);

        $user = Auth::user();

        $consent = UserPolicyConsent::create([
            'user_id' => $user->id,
            'policy_type' => $request->input('policy_type'),
            'version' => $request->input('version'),
            'agreed_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'consent_status' => 'declined',
        ]);

        return response()->json([
            'message' => 'Policy decline recorded',
            'data' => $consent,
        ]);
    }

    public function getUserConsents(): JsonResponse
    {
        $user = Auth::user();

        $consents = UserPolicyConsent::where('user_id', $user->id)
            ->latest('agreed_at')
            ->get()
            ->groupBy('policy_type');

        return response()->json([
            'data' => $consents,
        ]);
    }

    public function getLatestConsent(string $policyType): JsonResponse
    {
        $user = Auth::user();

        $consent = UserPolicyConsent::where('user_id', $user->id)
            ->where('policy_type', $policyType)
            ->latest('agreed_at')
            ->first();

        if (!$consent) {
            return response()->json([
                'message' => 'No consent found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'data' => $consent,
        ]);
    }

    public function getConsentStats(): JsonResponse
    {
        $stats = [
            'terms_of_service' => [
                'agreed' => UserPolicyConsent::termsOfService()->agreed()->count(),
                'declined' => UserPolicyConsent::termsOfService()->declined()->count(),
                'pending' => UserPolicyConsent::termsOfService()->where('consent_status', 'pending')->count(),
            ],
            'privacy_policy' => [
                'agreed' => UserPolicyConsent::privacyPolicy()->agreed()->count(),
                'declined' => UserPolicyConsent::privacyPolicy()->declined()->count(),
                'pending' => UserPolicyConsent::privacyPolicy()->where('consent_status', 'pending')->count(),
            ],
            'data_processing' => [
                'agreed' => UserPolicyConsent::where('policy_type', 'data_processing')->agreed()->count(),
                'declined' => UserPolicyConsent::where('policy_type', 'data_processing')->declined()->count(),
                'pending' => UserPolicyConsent::where('policy_type', 'data_processing')->where('consent_status', 'pending')->count(),
            ],
        ];

        return response()->json([
            'data' => $stats,
        ]);
    }
}
