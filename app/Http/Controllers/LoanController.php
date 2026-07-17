<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with('user', 'book')->latest()->get();

        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $books = Book::all();

        return view('loans.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required'
        ]);

        $book = Book::findOrFail($request->book_id);

        if ($book->stock <= 0) {
            return back()->with('error', 'Stok buku habis.');
        }

        Loan::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'loan_date' => now(),
            'status' => 'dipinjam',
        ]);

        $book->decrement('stock');

        return redirect()
            ->route('loans.index')
            ->with('success', 'Buku berhasil dipinjam.');
    }

    public function edit(Loan $loan)
    {
        return view('loans.edit', compact('loan'));
    }

    public function update(Request $request, Loan $loan)
    {
        if ($loan->status === 'dipinjam') {

            $loan->update([
                'status' => 'dikembalikan',
                'return_date' => now(),
            ]);

            $loan->book->increment('stock');
        }

        return redirect()
            ->route('loans.index')
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();

        return back();
    }
}