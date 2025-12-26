<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;

class DocumentInteractionController extends Controller
{
    public function like(Document $document)
    {
        $user = auth()->user();

        $existingLike = Like::where('user_id', $user->id)
            ->where('document_id', $document->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $document->decrement('likes_count');
            $message = 'Like removido';
        } else {
            Like::create([
                'user_id' => $user->id,
                'document_id' => $document->id
            ]);
            $document->increment('likes_count');
            $message = 'Documento liked';
        }

        if (request()->wantsJson()) {
            return response()->json([
                'message' => $message,
                'likes_count' => $document->likes_count,
                'is_liked' => !$existingLike
            ]);
        }

        return back()->with('success', $message);
    }

    public function comment(Request $request, Document $document)
    {
        $request->validate([
            'comment' => 'required|string|max:500'
        ], [
            'comment.max' => 'El comentario no puede superar los 500 caracteres.'
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'document_id' => $document->id,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Comentario agregado.');
    }

    public function deleteComment(Comment $comment)
    {
        // Verificar que el usuario es el dueño del comentario o es admin
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('Administrador')) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comentario eliminado.');
    }

    public function toggleFavorite(Document $document): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();

        $favorite = $document->favorites()->where('user_id', $user->id)->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            $document->favorites()->create([
                'user_id' => $user->id,
            ]);
        }

        return back();
    }


}
