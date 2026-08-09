<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Exports\ChallengeExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChallengeController extends Controller
{
    /**
     * Display a list of all challenges with filters and statistics.
     */
    public function allChallenges(Request $request)
    {
        $query = Challenge::with(['sender', 'receiver', 'winner']);

        // Filter by invitation status
        if ($request->filled('invitation_statue') && $request->invitation_statue !== 'all') {
            $query->where('invitation_statue', $request->invitation_statue);
        }

        // Search by game code or user names/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('game_code', 'like', "%{$search}%")
                  ->orWhereHas('sender', function ($sq) use ($search) {
                      $sq->where('fname', 'like', "%{$search}%")
                        ->orWhere('lname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('receiver', function ($rq) use ($search) {
                      $rq->where('fname', 'like', "%{$search}%")
                        ->orWhere('lname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        if ($request->sort_by === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $challenges = $query->get();

        // Statistics
        $totalChallengesCount = Challenge::count();
        $pendingCount = Challenge::where('invitation_statue', 'pending')->count();
        $acceptedCount = Challenge::where('invitation_statue', 'accepted')->count();
        $completedCount = Challenge::whereIn('invitation_statue', ['completed', 'finished'])->count();

        return view('admin.challenges.all_challenges', compact(
            'challenges',
            'totalChallengesCount',
            'pendingCount',
            'acceptedCount',
            'completedCount'
        ));
    }

    /**
     * Fetch challenge details for AJAX modal viewing.
     */
    public function detailsChallenge($id)
    {
        $challenge = Challenge::with(['sender', 'receiver', 'winner'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $challenge->id,
                'game_code' => $challenge->game_code,
                'date' => $challenge->date ? $challenge->date->format('Y-m-d H:i:s') : '-',
                'invitation_statue' => $challenge->invitation_statue,
                'score_get' => $challenge->score_get !== null ? $challenge->score_get : 'غير محدد',
                'join_start_at' => $challenge->join_start_at ? $challenge->join_start_at->format('Y-m-d H:i:s') : '-',
                'join_end_at' => $challenge->join_end_at ? $challenge->join_end_at->format('Y-m-d H:i:s') : '-',
                'created_at' => $challenge->created_at ? $challenge->created_at->format('Y-m-d H:i:s') : '-',
                'updated_at' => $challenge->updated_at ? $challenge->updated_at->format('Y-m-d H:i:s') : '-',
                'sender' => $challenge->sender ? [
                    'id' => $challenge->sender->id,
                    'name' => trim($challenge->sender->fname . ' ' . $challenge->sender->lname),
                    'email' => $challenge->sender->email,
                    'phone' => $challenge->sender->phone ?? 'غير متوفر',
                    'photo' => $challenge->sender->photo ? asset($challenge->sender->photo) : asset('upload/no_image.jpg'),
                ] : null,
                'receiver' => $challenge->receiver ? [
                    'id' => $challenge->receiver->id,
                    'name' => trim($challenge->receiver->fname . ' ' . $challenge->receiver->lname),
                    'email' => $challenge->receiver->email,
                    'phone' => $challenge->receiver->phone ?? 'غير متوفر',
                    'photo' => $challenge->receiver->photo ? asset($challenge->receiver->photo) : asset('upload/no_image.jpg'),
                ] : null,
                'winner' => $challenge->winner ? [
                    'id' => $challenge->winner->id,
                    'name' => trim($challenge->winner->fname . ' ' . $challenge->winner->lname),
                    'email' => $challenge->winner->email,
                    'photo' => $challenge->winner->photo ? asset($challenge->winner->photo) : asset('upload/no_image.jpg'),
                ] : null,
            ]
        ]);
    }

    /**
     * Delete a challenge record.
     */
    public function deleteChallenge($id)
    {
        $challenge = Challenge::findOrFail($id);
        $challenge->delete();

        $notification = array(
            'message' => 'تم حذف التحدي بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Export challenges data to Excel.
     */
    public function exportChallenges(Request $request)
    {
        return Excel::download(new ChallengeExport($request), 'challenges_' . date('Y_m_d_His') . '.xlsx');
    }

    /**
     * API: Send / Create a challenge invitation.
     */
    public function sendChallengeInvitationApi(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id_send_invitataion' => 'required|exists:users,id',
            'user_id_resive_invitaion' => 'required|exists:users,id|different:user_id_send_invitataion',
            'game_code'                => 'required|string|max:255',
            'date'                     => 'nullable|date',
            'invitation_statue'        => 'nullable|string|max:255',
            'user_id_winner'           => 'nullable|exists:users,id',
            'score_get'                => 'nullable|integer',
            'join_start_at'            => 'nullable',
            'join_end_at'              => 'nullable',
            'joinStartAt'              => 'nullable',
            'joinEndAt'                => 'nullable',
        ], [
            'user_id_send_invitataion.required'  => 'حقل معرف مرسل الدعوة مطلوب.',
            'user_id_send_invitataion.exists'    => 'المستخدم المرسل غير موجود.',
            'user_id_resive_invitaion.required'  => 'حقل معرف مستقبل الدعوة مطلوب.',
            'user_id_resive_invitaion.exists'    => 'المستخدم المستقبل غير موجود.',
            'user_id_resive_invitaion.different' => 'لا يمكنك إرسال دعوة تحدي لنفسك.',
            'game_code.required'                 => 'حقل كود اللعبة مطلوب.',
            'game_code.string'                   => 'كود اللعبة يجب أن يكون نصاً.',
            'user_id_winner.exists'              => 'المستخدم الفائز المحدد غير موجود.',
            'score_get.integer'                  => 'النقاط يجب أن تكون رقماً صحيحاً.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors'  => $validator->errors()
            ], 422);
        }

        $joinStartAtRaw = $request->input('join_start_at') ?? $request->input('joinStartAt') ?? $request->input('date');
        $joinEndAtRaw   = $request->input('join_end_at') ?? $request->input('joinEndAt');

        $joinStartAt = $joinStartAtRaw ? \Carbon\Carbon::parse($joinStartAtRaw) : now();
        $joinEndAt   = $joinEndAtRaw ? \Carbon\Carbon::parse($joinEndAtRaw) : null;

        $challenge = Challenge::create([
            'user_id_send_invitataion' => $request->user_id_send_invitataion,
            'user_id_resive_invitaion' => $request->user_id_resive_invitaion,
            'date'                     => $request->filled('date') ? \Carbon\Carbon::parse($request->date) : $joinStartAt,
            'invitation_statue'        => $request->invitation_statue ?? 'pending',
            'game_code'                => $request->game_code,
            'user_id_winner'           => $request->user_id_winner ?? null,
            'score_get'                => $request->score_get ?? null,
            'join_start_at'            => $joinStartAt,
            'join_end_at'              => $joinEndAt,
        ]);

        $challenge->load(['sender', 'receiver', 'winner']);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال دعوة التحدي وتسجيلها بنجاح',
            'data'    => [
                'id'                       => $challenge->id,
                'user_id_send_invitataion' => $challenge->user_id_send_invitataion,
                'user_id_resive_invitaion' => $challenge->user_id_resive_invitaion,
                'date'                     => $challenge->date ? $challenge->date->format('Y-m-d H:i:s') : null,
                'invitation_statue'        => $challenge->invitation_statue,
                'game_code'                => $challenge->game_code,
                'user_id_winner'           => $challenge->user_id_winner,
                'score_get'                => $challenge->score_get,
                'join_start_at'            => $challenge->join_start_at ? $challenge->join_start_at->format('Y-m-d H:i:s') : null,
                'join_end_at'              => $challenge->join_end_at ? $challenge->join_end_at->format('Y-m-d H:i:s') : null,
                'created_at'               => $challenge->created_at ? $challenge->created_at->format('Y-m-d H:i:s') : null,
                'updated_at'               => $challenge->updated_at ? $challenge->updated_at->format('Y-m-d H:i:s') : null,
                'sender'                   => $challenge->sender ? [
                    'id'    => $challenge->sender->id,
                    'name'  => trim(($challenge->sender->fname ?? '') . ' ' . ($challenge->sender->lname ?? '')),
                    'email' => $challenge->sender->email,
                ] : null,
                'receiver'                 => $challenge->receiver ? [
                    'id'    => $challenge->receiver->id,
                    'name'  => trim(($challenge->receiver->fname ?? '') . ' ' . ($challenge->receiver->lname ?? '')),
                    'email' => $challenge->receiver->email,
                ] : null,
                'winner'                   => $challenge->winner ? [
                    'id'    => $challenge->winner->id,
                    'name'  => trim(($challenge->winner->fname ?? '') . ' ' . ($challenge->winner->lname ?? '')),
                    'email' => $challenge->winner->email,
                ] : null,
            ]
        ], 201);
    }

    /**
     * API: Update challenge status / winner / score.
     */
    public function updateChallengeStatusApi(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'challenge_id'      => 'required|exists:challenges,id',
            'invitation_statue' => 'nullable|string|max:255',
            'user_id_winner'    => 'nullable|exists:users,id',
            'score_get'         => 'nullable|integer',
        ], [
            'challenge_id.required' => 'حقل معرف التحدي مطلوب.',
            'challenge_id.exists'   => 'التحدي المحدد غير موجود.',
            'user_id_winner.exists' => 'المستخدم الفائز المحدد غير موجود.',
            'score_get.integer'     => 'النقاط يجب أن تكون رقماً صحيحاً.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors'  => $validator->errors()
            ], 422);
        }

        $challenge = Challenge::findOrFail($request->challenge_id);

        $updateData = [];
        if ($request->has('invitation_statue')) {
            $updateData['invitation_statue'] = $request->invitation_statue;
        }
        if ($request->has('user_id_winner')) {
            $updateData['user_id_winner'] = $request->user_id_winner;
        }
        if ($request->has('score_get')) {
            $updateData['score_get'] = $request->score_get;
        }

        $challenge->update($updateData);
        $challenge->load(['sender', 'receiver', 'winner']);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة التحدي بنجاح',
            'data'    => [
                'id'                       => $challenge->id,
                'user_id_send_invitataion' => $challenge->user_id_send_invitataion,
                'user_id_resive_invitaion' => $challenge->user_id_resive_invitaion,
                'date'                     => $challenge->date ? $challenge->date->format('Y-m-d H:i:s') : null,
                'invitation_statue'        => $challenge->invitation_statue,
                'game_code'                => $challenge->game_code,
                'user_id_winner'           => $challenge->user_id_winner,
                'score_get'                => $challenge->score_get,
                'updated_at'               => $challenge->updated_at ? $challenge->updated_at->format('Y-m-d H:i:s') : null,
            ]
        ], 200);
    }

    /**
     * API: Get all challenges/invitations for a specific user.
     */
    public function getUserChallengesApi(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id'           => 'required|exists:users,id',
            'type'              => 'nullable|in:all,sent,received',
            'invitation_statue' => 'nullable|string|max:255',
        ], [
            'user_id.required'  => 'حقل معرف المستخدم مطلوب.',
            'user_id.exists'    => 'المستخدم المحدد غير موجود.',
            'type.in'           => 'حقل النوع يجب أن يكون all أو sent أو received.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors'  => $validator->errors()
            ], 422);
        }

        $userId = $request->user_id;
        $type = $request->type ?? 'all';

        $query = Challenge::with(['sender', 'receiver', 'winner']);

        if ($type === 'sent') {
            $query->where('user_id_send_invitataion', $userId);
        } elseif ($type === 'received') {
            $query->where('user_id_resive_invitaion', $userId);
        } else {
            $query->where(function ($q) use ($userId) {
                $q->where('user_id_send_invitataion', $userId)
                  ->orWhere('user_id_resive_invitaion', $userId);
            });
        }

        if ($request->filled('invitation_statue') && $request->invitation_statue !== 'all') {
            $query->where('invitation_statue', $request->invitation_statue);
        }

        $challenges = $query->latest()->get();

        $data = $challenges->map(function ($challenge) use ($userId) {
            $senderPhoto = null;
            if ($challenge->sender && !empty($challenge->sender->photo) && $challenge->sender->photo !== 'non') {
                $sp = $challenge->sender->photo;
                if (str_starts_with($sp, 'http://') || str_starts_with($sp, 'https://')) {
                    $senderPhoto = $sp;
                } else {
                    $senderPhoto = asset($sp);
                }
            }

            $receiverPhoto = null;
            if ($challenge->receiver && !empty($challenge->receiver->photo) && $challenge->receiver->photo !== 'non') {
                $rp = $challenge->receiver->photo;
                if (str_starts_with($rp, 'http://') || str_starts_with($rp, 'https://')) {
                    $receiverPhoto = $rp;
                } else {
                    $receiverPhoto = asset($rp);
                }
            }

            $winnerPhoto = null;
            if ($challenge->winner && !empty($challenge->winner->photo) && $challenge->winner->photo !== 'non') {
                $wp = $challenge->winner->photo;
                if (str_starts_with($wp, 'http://') || str_starts_with($wp, 'https://')) {
                    $winnerPhoto = $wp;
                } else {
                    $winnerPhoto = asset($wp);
                }
            }

            return [
                'id'                       => $challenge->id,
                'user_id_send_invitataion' => $challenge->user_id_send_invitataion,
                'user_id_resive_invitaion' => $challenge->user_id_resive_invitaion,
                'date'                     => $challenge->date ? $challenge->date->format('Y-m-d H:i:s') : null,
                'invitation_statue'        => $challenge->invitation_statue,
                'game_code'                => $challenge->game_code,
                'user_id_winner'           => $challenge->user_id_winner,
                'score_get'                => $challenge->score_get,
                'join_start_at'            => $challenge->join_start_at ? $challenge->join_start_at->format('Y-m-d H:i:s') : null,
                'join_end_at'              => $challenge->join_end_at ? $challenge->join_end_at->format('Y-m-d H:i:s') : null,
                'is_sender'                => (int)$challenge->user_id_send_invitataion === (int)$userId,
                'created_at'               => $challenge->created_at ? $challenge->created_at->format('Y-m-d H:i:s') : null,
                'updated_at'               => $challenge->updated_at ? $challenge->updated_at->format('Y-m-d H:i:s') : null,
                'sender'                   => $challenge->sender ? [
                    'id'    => $challenge->sender->id,
                    'name'  => trim(($challenge->sender->fname ?? '') . ' ' . ($challenge->sender->lname ?? '')),
                    'email' => $challenge->sender->email,
                    'photo' => $senderPhoto,
                ] : null,
                'receiver'                 => $challenge->receiver ? [
                    'id'    => $challenge->receiver->id,
                    'name'  => trim(($challenge->receiver->fname ?? '') . ' ' . ($challenge->receiver->lname ?? '')),
                    'email' => $challenge->receiver->email,
                    'photo' => $receiverPhoto,
                ] : null,
                'winner'                   => $challenge->winner ? [
                    'id'    => $challenge->winner->id,
                    'name'  => trim(($challenge->winner->fname ?? '') . ' ' . ($challenge->winner->lname ?? '')),
                    'email' => $challenge->winner->email,
                    'photo' => $winnerPhoto,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'تم جلب دعوات التحدي للمستخدم بنجاح',
            'count'   => $data->count(),
            'data'    => $data
        ], 200);
    }
}
