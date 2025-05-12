<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Books;

class BookFilters extends Component
{
    public $search = '';
    public $author = '';
    public $detail = '';
    public $minPrice;
    public $maxPrice;

    // Método para renderizar e filtrar os livros
    public function render()
    {
        $books = Books::query()
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->author, fn($query) => $query->where('author', $this->author))
            ->when($this->detail, fn($query) => $query->where('detail', $this->detail))
            ->when($this->minPrice, fn($query) => $query->where('price', '>=', $this->minPrice))
            ->when($this->maxPrice, fn($query) => $query->where('price', '<=', $this->maxPrice))
            ->paginate(12); // Adicionando paginação

        return view('livewire.book-filters', [
            'books' => $books,
        ]);
    }

    // Método para adicionar ao carrinho
    public function addToCart($bookId)
    {
        $book = Books::findOrFail($bookId);

        $cart = session()->get('cart', []);

        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity']++;
        } else {
            $cart[$bookId] = [
                'id' => $book->id,
                'name' => $book->name,
                'quantity' => 1,
                'price' => $book->price,
                'image' => json_decode($book->image, true)[0] ?? null,
            ];
        }

        session()->put('cart', $cart);

        // Disparando evento JavaScript para notificação
        $this->dispatchBrowserEvent('book-added', ['message' => 'Book added to cart!']);
    }
}
