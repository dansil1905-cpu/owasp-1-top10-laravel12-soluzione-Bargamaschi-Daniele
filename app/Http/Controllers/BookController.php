<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookController extends Controller
{
    use AuthorizesRequests;

    /**
     */
    public function index()
    {
        $books = Auth::user()->books()->paginate(10);
        return response()->json($books);
    }

    /**
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'required|string|max:13|unique:books,isbn',
            'published_at' => 'nullable|date',
        ]);

        $book = Auth::user()->books()->create($validated);

        return response()->json($book, 201);
    }

    /**
     */
    public function show(Book $book)
    {
        if ($book->user_id !== Auth::id()) {
            return response()->json(['message' => 'Accesso negato.'], 403);
        }

        return response()->json($book);
    }

    /**
     */
    public function update(Request $request, Book $book)
    {
        if ($book->user_id !== Auth::id()) {
            return response()->json(['message' => 'Azione non autorizzata.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'sometimes|required|string|max:13|unique:books,isbn,' . $book->id,
            'published_at' => 'nullable|date',
        ]);

        $book->update($validated);

        return response()->json($book);
    }

    /**
     */
    public function destroy(Book $book)
    {
        if ($book->user_id !== Auth::id()) {
            return response()->json(['message' => 'Azione non autorizzata.'], 403);
        }

        $book->delete();

        return response()->json(['message' => 'Libro eliminato correttamente.']);
    }
}