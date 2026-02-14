<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class CreditController extends Controller
{
    public function pricing()
    {
        $packages = [
            ['name' => 'Silver', 'credit' => 20, 'price' => 5.00, 'price_id' => 'price_1T0UTl1DMC7Ht8eGhUG1RWWE'],
            ['name' => 'Gold', 'credit' => 50, 'price' => 10.00, 'price_id' => 'price_1T0pYl1DMC7Ht8eGTcP5D4EA'],
        ];

        return Inertia::render('Pricing', ['packages' => $packages]);
    }

    public function checkout(Request $request)
    {
        // if (! auth()->check()) {
        //     return redirect()->route('home')->with('message', 'You must be logged in to purchase credits.');
        // }
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $price_id = $request->input('price_id');
        $credit = $request->input('credit');

        try {
            $checkoutSession = Session::create([
                'line_items' => [[
                    'price' => $price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
                'metadata' => [
                    'credit' => $credit,
                    'user_id' => auth()->id(),
                ],
            ]);

            return Inertia::location($checkoutSession->url);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $sessionId = $request->query('session_id');
        $session = Session::retrieve($sessionId);
        $metadata = $session->metadata;
        $credit = (int) $metadata->credit;

        if ($session->payment_status === 'paid') {

            return Inertia::render('Success', []);
            // return "🎉 {$metadata->first_name} {$metadata->last_name} successfully donated \${$metadata->amount}.";
        }

        return redirect()->route('cancel');
    }

    public function cancel()
    {
        return '❌ Payment canceled.';
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
        Log::info('Webhook received', ['has_secret' => ! empty($endpointSecret)]);
        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Webhook invalid payload', ['error' => $e->getMessage()]);

            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Webhook invalid signature', ['error' => $e->getMessage()]);

            return response('Invalid signature', 400);
        }

        // ✅ PAYMENT SUCCESS CONFIRMATION
        try {
            // Handle the event
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;

                $metadata = $session->metadata;

                // 2. Extract your stored IDs
                $userId = $metadata->user_id ?? null;
                $creditAmount = $metadata->credit ?? 0;

                if ($userId) {
                    // 3. Find the user and update their credit
                    $user = User::find($userId);

                    if ($user) {
                        $user->increment('credit', $creditAmount);
                        Log::info("Credits added to User ID: {$userId}", ['amount' => $creditAmount]);
                    }
                }
            }

            return response('Webhook handled', 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response('Webhook processing failed', 500);
        }

    }
}
