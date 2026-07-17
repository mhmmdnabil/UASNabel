<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user', 'book')
                        ->latest()
                        ->get();

        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        $books = Book::all();

        return view('reviews.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()
                ->route('reviews.index')
                ->with('success', 'Review berhasil ditambahkan.');
    }

    public function show(Review $review)
    {
        //
    }

    public function edit(Review $review)
    {
        $books = Book::all();

        return view('reviews.edit',
            compact('review', 'books'));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'book_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required',
        ]);

        $review->update([
            'book_id' => $request->book_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()
                ->route('reviews.index')
                ->with('success', 'Review berhasil diupdate.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()
                ->route('reviews.index')
                ->with('success', 'Review berhasil dihapus.');
    }
}