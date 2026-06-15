<?php

namespace App\Http\Controllers;

use App\Models\AnimationUserLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnimationUserLibraryController extends Controller
{
    /**
     * Display a listing of the user's animation library.
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $library = AnimationUserLibrary::with(['animationFeedback.coin'])
            ->where('user_id', $request->user_id)
            ->get();

        $data = $library->map(function ($item) {
            $animation = $item->animationFeedback;
            if ($animation) {
                $animation->setAttribute('library_id', $item->id);
                $animation->setAttribute('user_id', $item->user_id);
            }
            return $animation;
        })->filter()->values();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Store a new animation in the user's library.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'animation_feedbacks_id' => 'required|exists:animation_feedbacks,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Fetch the animation details
        $animation = \App\Models\AnimationFeedback::findOrFail($request->animation_feedbacks_id);

        // Check if already exists to avoid duplicates
        $exists = AnimationUserLibrary::where('user_id', $request->user_id)
            ->where('animation_feedback_id', $request->animation_feedbacks_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Animation already exists in user library'
            ], 409);
        }

        // --- Purchase Logic ---
        if ($animation->is_free == 0 && $animation->coin_amount > 0) {
            $coin_id = $animation->coin_id;
            $required_amount = $animation->coin_amount;

            // Calculate user's current balance for this coin
            $user_balance = \App\Models\UserCoin::where('user_id', $request->user_id)
                ->where('game_coin_id', $coin_id)
                ->sum('coins_number');

            if ($user_balance < $required_amount) {
                // Load coin details to return the type of currency required
                $coin = $animation->load('coin')->coin;

                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient coins balance',
                    'required' => $required_amount,
                    'current_balance' => (int) $user_balance,
                    'coin' => $coin
                ], 200);
            }

            // Deduct coins by creating a new record in user_coins
            \App\Models\UserCoin::create([
                'user_id' => $request->user_id,
                'game_coin_id' => $coin_id,
                'coins_number' => -$required_amount,
                'type' => 'buy_animation'
            ]);
        }
        // --- End Purchase Logic ---

        $entry = AnimationUserLibrary::create([
            'user_id' => $request->user_id,
            'animation_feedback_id' => $request->animation_feedbacks_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Animation added to library successfully',
            'data' => $entry->load(['animationFeedback.coin'])
        ], 201);
    }

    /**
     * Remove an animation from the user's library.
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'animation_feedbacks_id' => 'required|exists:animation_feedbacks,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $deleted = AnimationUserLibrary::where('user_id', $request->user_id)
            ->where('animation_feedback_id', $request->animation_feedbacks_id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Entry not found in library'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Animation removed from library successfully'
        ]);
    }
}
