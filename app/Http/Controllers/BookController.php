<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')->latest()->get();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'=>'required',
            'title'=>'required',
            'author'=>'required',
            'publisher'=>'required',
            'year'=>'required',
            'stock'=>'required'
        ]);

        Book::create($request->all());

        return redirect()
                ->route('books.index')
                ->with('success','Buku berhasil ditambahkan');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::all();

        return view('books.edit', compact('book','categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'category_id'=>'required',
            'title'=>'required',
            'author'=>'required',
            'publisher'=>'required',
            'year'=>'required',
            'stock'=>'required'
        ]);

        $book->update($request->all());

        return redirect()
                ->route('books.index')
                ->with('success','Buku berhasil diupdate');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()
                ->route('books.index')
                ->with('success','Buku berhasil dihapus');
    }
}