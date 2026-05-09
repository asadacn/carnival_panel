<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientCommentController extends Controller
{
    /**
     * Return all comments for a client (JSON, newest first).
     */
    public function index($clientId)
    {
        $client = Client::findOrFail($clientId);

        $comments = ClientComment::with('author')
            ->where('client_id', $clientId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($c) {
                return [
                    'id'              => $c->id,
                    'body'            => $c->body,
                    'type'            => $c->type,
                    'author_name'     => $c->author_name,
                    'author_initials' => $c->author_initials,
                    'time_ago'        => $c->time_ago,
                    'created_at'      => $c->created_at?->format('d M Y, h:i A'),
                    'is_mine'         => $c->user_id === Auth::id(),
                ];
            });

        return response()->json([
            'success'  => true,
            'comments' => $comments,
            'client'   => [
                'id'   => $client->id,
                'name' => $client->name,
            ],
        ]);
    }

    /**
     * Store a new comment.
     */
    public function store(Request $request, $clientId)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
            'type' => 'nullable|in:note,alert,info,success',
        ]);

        Client::findOrFail($clientId);

        $comment = ClientComment::create([
            'client_id' => $clientId,
            'user_id'   => Auth::id(),
            'body'      => trim($request->body),
            'type'      => $request->type ?? 'note',
        ]);

        $comment->load('author');

        return response()->json([
            'success' => true,
            'comment' => [
                'id'              => $comment->id,
                'body'            => $comment->body,
                'type'            => $comment->type,
                'author_name'     => $comment->author_name,
                'author_initials' => $comment->author_initials,
                'time_ago'        => $comment->time_ago,
                'created_at'      => $comment->created_at?->format('d M Y, h:i A'),
                'is_mine'         => true,
            ],
        ]);
    }

    /**
     * Delete a comment (only the owner or admin can delete).
     */
    public function destroy($commentId)
    {
        $comment = ClientComment::findOrFail($commentId);

        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }
}
