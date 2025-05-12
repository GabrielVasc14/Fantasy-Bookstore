<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\User;
use App\Models\Review;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return response()
     */
    public function index(): View
    {
        $books = Books::latest()->paginate(5);

        return view('books.index', compact('books'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'detail' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'stock' => 'nullable|integer',
            'condition' => 'nullable|in:New,Used,Limited Edition,Signed',
            'is_special_edition' => 'nullable|boolean',
            'price' => 'required|numeric',
            'price_ebook' => 'nullable|numeric',
            'price_audio' => 'nullable|numeric',
            'author' => 'required',
            'synopsis' => 'required',
            'audio_path' => 'nullable|file|mimes:mp3,wav,ogg|max:2048',
            'ebook_path' => 'nullable|mimes:pdf|max:2048',
        ], [
            'name.required' => 'Book Name is mandatory',
            'detail.required' => 'Book Detail is mandatory',
            'image.required' => 'Book Image is mandatory',
            'price.required' => 'Book Price is mandatory',
            'author.required' => 'Author of the book is mandatory',
            'synopsis.required' => 'Book Synopsis is mandatory',
            'audio_path.mimes' => 'Audio must be a file of type: mp3, wav, ogg.',
            'ebook_path.mimes' => 'Ebook must be a file of type: pdf.',
        ], [
            'name' => 'Book Name',
            'detail' => 'Book Detail',
            'image' => 'Book Image',
            'stock' => 'Book Stock',
            'condition' => 'Book Condition',
            'is_special_edition' => 'Book Special Edition',
            'price' => 'Book Price',
            'price_ebook' => 'Book Price eBook',
            'price_audio' => 'Book Price Audio',
            'author' => 'Author of the book',
            'synopsis' => 'Book Synopsis',
            'audio_path' => 'Audio Book',
            'ebook_path' => 'Ebook',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        //Imagem
        $imagePaths = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                // Verifique se o arquivo é válido
                if ($image->isValid()) {
                    // Gerar um nome único para cada imagem
                    $profileImage = date('YmdHis') . "_" . uniqid() . "." . $image->getClientOriginalExtension();

                    // Mover a imagem para o diretório 'images'
                    $image->move(public_path('images'), $profileImage);

                    // Armazenar o nome da imagem
                    $imagePaths[] = $profileImage;
                } else {
                    // Se algum arquivo não for válido
                    dd("This is not valid.", $image);
                }
            }
        }

        //Audio
        $audioPaths = [];

        if ($request->hasFile('audio_path')) {
            $audio = $request->file('audio_path');

            // Verifique se o arquivo de áudio é válido
            if ($audio->isValid()) {
                // Gerar um nome único para o arquivo de áudio
                $audioName = date('YmdHis') . "_" . uniqid() . "." . $audio->getClientOriginalExtension();

                // Mover o arquivo de áudio para o diretório 'audios'
                $audio->move(public_path('audios'), $audioName);

                // Armazenar o nome do audio
                $audioPaths[] = $audioName;
            } else {
                // Se algum arquivo não for válido
                dd("This is not valid.", $audio);
            }
        }

        //PDF
        $ebookPaths = [];

        if ($request->hasFile('ebook_path')) {
            $ebook = $request->file('ebook_path');

            // Verifique se o arquivo de pdf é válido
            if ($ebook->isValid()) {
                // Gerar um nome único para o arquivo de pdf
                $ebookName = date('YmdHis') . "_" . uniqid() . '.pdf';

                // Mover o arquivo de pdf para o diretório 'ebooks'
                $ebook->move(public_path('ebooks'), $ebookName);

                // Armazenar o nome do audio
                $ebookPaths[] = $ebookName;
            } else {
                // Se algum arquivo não for válido
                dd("This is not valid.", $ebook);
            }
        }

        $books = new Books;
        $books->name = $request->name;
        $books->detail = $request->detail;
        $books->price = $request->price;
        $books->price_ebook = $request->price_ebook;
        $books->price_audio = $request->price_audio;
        $books->author = $request->author;
        $books->synopsis = $request->synopsis;
        $books->image = json_encode($imagePaths);
        $books->audio_path = !empty($audioPaths) ? json_encode($audioPaths) : null;
        $books->ebook_path = !empty($ebookPaths) ? json_encode($ebookPaths) : null;
        $books->stock = $request->stock;
        $books->condition = $request->condition;
        $books->is_special_edition = $request->is_special_edition;
        $books->save();

        if (!$books) {
            return response()->json(['error' => 'Erro ao inserir os dados.']);
        } else {
            return response()->json(['success' => true, 'message' => 'Book added successfully']);
        }

        // return redirect()->route('books.index')
        //     ->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $books = Books::findOrFail($id);

        return view('books.show', compact('books'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $books = Books::findOrFail($id);

        return view('books.edit', compact('books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'detail' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'stock' => 'nullable|integer',
            'condition' => 'nullable|in:New,Used,Limited Edition,Signed',
            'is_special_edition' => 'nullable|boolean',
            'price' => 'required|numeric',
            'price_ebook' => 'nullable|numeric',
            'price_audio' => 'nullable|numeric',
            'author' => 'required',
            'synopsis' => 'required',
            'audio_path' => 'nullable|mimes:mp3,wav,ogg|max:2048',
            'ebook_path' => 'nullable|mimes:pdf|max:2048',
        ], [
            'name.required' => 'Book Name is mandatory',
            'detail.required' => 'Book Detail is mandatory',
            'image.required' => 'Book Image is mandatory',
            'price.required' => 'Book Price is mandatory',
            'author.required' => 'Author of the book is mandatory',
            'synopsis.required' => 'Book Synopsis is mandatory',
            'audio_path.mimes' => 'Audio must be a file of type: mp3, wav, ogg.',
        ], [
            'name' => 'Book Name',
            'detail' => 'Book Detail',
            'image' => 'Book Image',
            'stock' => 'Book Stock',
            'condition' => 'Book Condition',
            'is_special_edition' => 'Book Special Edition',
            'price' => 'Book Price',
            'price_ebook' => 'Book Price eBook',
            'price_audio' => 'Book Price Audio',
            'author' => 'Author of the book',
            'synopsis' => 'Book Synopsis',
            'audio_path' => 'Audio',
            'ebook_path' => 'PDF',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        //Imagem
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            if ($image->isValid()) {
                $profileImage = date('YmdHis') . "_" . uniqid() . "." . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $profileImage);
                $imagePath = $profileImage;
            }
        }

        //Audio
        $audioPath = null;
        if ($request->hasFile('audio_path')) {
            $audio = $request->file('audio_path');

            if ($audio->isValid()) {
                $audioName = date('YmdHis') . "_" . uniqid() . "." . $audio->getClientOriginalExtension();
                $audio->move(public_path('audios'), $audioName);
                $audioPath = $audioName;
            }
        }

        //PDF
        $ebookPath = null;
        if ($request->hasFile('ebook_path')) {
            $ebook = $request->file('ebook_path');

            if ($ebook->isValid()) {
                $ebookName = date('YmdHis') . "_" . uniqid() . '.pdf';
                $ebook->move(public_path('ebooks'), $ebookName);
                $ebookPath = $ebookName;
            }
        }

        $books = Books::findOrFail($id);
        $books->name = $request->name;
        $books->detail = $request->detail;
        $books->price = $request->price;
        $books->price_ebook = $request->price_ebook;
        $books->price_audio = $request->price_audio;
        $books->author = $request->author;
        $books->synopsis = $request->synopsis;
        $books->stock = $request->stock;
        $books->condition = $request->condition;
        $books->is_special_edition = $request->is_special_edition;
        if ($imagePath) {
            $books->image = json_encode([$imagePath]); // ainda guarda como array (se seu projeto espera assim)
        }
        if ($audioPath) {
            $books->audio_path = json_encode([$audioPath]); // ainda guarda como array (se seu projeto espera assim)
        }
        if ($ebookPath) {
            $books->ebook_path = json_encode([$ebookPath]); // ainda guarda como array (se seu projeto espera assim)
        }
        $books->save();

        if (!$books) {
            return response()->json(['error' => 'Erro ao inserir os dados.']);
        } else {
            return response()->json(['success' => true, 'message' => 'Book added successfully']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $books = Books::findOrFail($id);
        $books->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Book Deleted successfully.']);
        }

        return redirect()->route('books.index')
            ->with('success', 'Book Deleted successfully.');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function cart_index(Request $request)
    {
        // Iniciar a consulta para buscar livros
        $query = Books::query();

        // Filtro por título (se fornecido)
        if ($request->has('title') && !empty($request->title)) {
            $query->where('name', 'like', '%' . $request->title . '%');
        }

        // Filtro por autor (se fornecido)
        if ($request->has('author') && !empty($request->author)) {
            $query->where('author', 'like', '%' . $request->author . '%');
        }

        // Filtro por categoria/detalhes (se fornecido)
        if ($request->has('detail') && !empty($request->detail)) {
            $query->where('detail', 'like', '%' . $request->detail . '%');
        }

        // Filtro por preço mínimo (se fornecido)
        if ($request->has('min_price') && !empty($request->min_price)) {
            if (is_numeric($request->min_price)) {
                $query->where('price', '>=', $request->min_price);
            } else {
                // Caso o valor de min_price não seja válido, ignoramos esse filtro
                Log::warning("Valor inválido para min_price: " . $request->min_price);
            }
        }

        // Filtro por preço máximo (se fornecido)
        if ($request->has('max_price') && !empty($request->max_price)) {
            if (is_numeric($request->max_price)) {
                $query->where('price', '<=', $request->max_price);
            } else {
                // Caso o valor de max_price não seja válido, ignoramos esse filtro
                Log::warning("Valor inválido para max_price: " . $request->max_price);
            }
        }

        $books = $query->latest()->paginate(12);

        return view('cart.books', compact('books'))
            ->with('i', (request()->input('page', 1) - 1) * 12);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function cart()
    {
        return view('cart.cart');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    // Exemplo do método no controlador
    public function addToCart(Request $request, $bookId, $format = 'physical')
    {
        // Recuperar o livro
        $book = Books::findOrFail($bookId);
        $format = in_array($format, ['ebook', 'audio']) ? $format : 'physical';

        //Formato
        switch ($format) {
            case 'ebook':
                $price = $book->price_ebook;
                $label = $book->name . ' (eBook) ';
                break;

            case 'audio':
                $price = $book->price_audio;
                $label = $book->name . ' (Audiobook) ';
                break;

            default:
                $price = $book->price;
                $label = $book->name;
        }

        $key = $bookId . '_' . $format;
        // Lógica para adicionar ao carrinho
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity']++;
        } else {
            $cart[$key] = [
                'id' => $book->id,
                'name' => $label,
                'format' => $format,
                'quantity' => 1,
                'price' => $price,
                'image' => json_decode($book->image)[0] ?? ''
            ];
        }

        // Salvar no carrinho
        session()->put('cart', $cart);

        // Retornar o carrinho atualizado como resposta
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'total' => session('cart_total') // Incluindo o total do carrinho na resposta
        ]);
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function cart_update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');

            // Verifique se o item existe no carrinho antes de atualizar
            if (isset($cart[$request->id])) {
                $cart[$request->id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
                session()->flash('success', 'Cart updated successfully');
            } else {
                session()->flash('error', 'Item not found in the cart');
            }
        }
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');

            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);

                // Recalcular o total do carrinho após a remoção
                $cart_total = 0;
                foreach ($cart as $item) {
                    $cart_total += $item['price'] * $item['quantity'];
                }
                session()->put('cart_total', $cart_total);

                session()->flash('success', 'Book removed from cart successfully.');
            } else {
                session()->flash('error', 'Item not found in the cart.');
            }
        }
    }

    /**
     * Remove all resource from storage.
     */
    public function destroyAll()
    {
        session()->forget('cart');
        session()->forget('cart_total');

        session()->flash('success', 'Cart emptied successfully.');
    }

    public function toggle($book)
    {
        $user = User::find(Auth::id());

        if ($user->likedBooks()->where('book_id', $book)->exists()) {
            $user->likedBooks()->detach($book);
            return response()->json(['liked' => false]);
        }

        $user->likedBooks()->attach($book);
        return response()->json(['liked' => true]);
    }

    public function wishlist()
    {
        $user = User::find(Auth::id());

        $likedBooks = $user->likedBooks()->get();
        return view('cart.wishlist', compact('likedBooks'));
    }

    public function dislike(Books $book)
    {
        $user = User::find(Auth::id());

        // Verificar se o livro já está na lista de curtidos do usuário
        if ($user->likedBooks()->where('book_id', $book->id)->exists()) {
            // Remover o livro da lista de curtidos
            $user->likedBooks()->detach($book->id);

            // Retornar uma mensagem de sucesso
            return response()->json([
                'success' => true,
                'message' => 'Book removed from wishlist.'
            ], 200);  // Aqui está o código 200 indicando sucesso
        }

        // Caso o livro não esteja na lista de curtidos, retornar uma mensagem
        return response()->json([
            'success' => false,
            'message' => 'Book is not in the wishlist.'
        ], 404);
    }

    public function searchGoogleBooks(Request $request)
    {
        $title = $request->input('title');
        $author = $request->input('author');

        // Chamada à API do Google Books
        $query = http_build_query([
            'q' => trim("$title $author"),
            'maxResults' => 10,
        ]);

        $response = Http::get("https://www.googleapis.com/books/v1/volumes?$query");
        $items = $response->json()['items'] ?? [];

        $books = array_map(function ($item) {
            $info = $item['volumeInfo'];
            $isbn = null;

            foreach ($info['industryIdentifiers'] ?? [] as $id) {
                if (in_array($id['type'], ['ISBN_13', 'ISBN_10'])) {
                    $isbn = $id['identifier'];
                    break;
                }
            }

            $googleThumbnail = $info['imageLinks']['thumbnail'] ?? null;

            $coverUrl = $googleThumbnail;

            if (!$coverUrl && $isbn) {
                $openLibraryUrl = "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg";
                $head = Http::head($openLibraryUrl);

                if ($head->status() == 200 && !str_contains($head->header('Content-Type'), 'image/gif')) {
                    $coverUrl = $openLibraryUrl;
                }
            }

            $fallback = $coverUrl ?? '/images/no-cover.png';

            return [
                'id'        => $item['id'],
                'title'     => $info['title'] ?? '',
                'authors'   => $info['authors'] ?? [],
                'details'   => $info['categories'] ?? [],
                'synopsis'  => $info['description'] ?? '',
                'price'     => $item['saleInfo']['listPrice']['amount'] ?? null,
                'cover'     => $fallback,
                'fallback'  => '/images/no-cover.png', // <-- adicione isso
            ];
        }, $items);

        return view('books.google-results', compact('books'));

        // if ($response->successful()) {
        //     $books = $response->json()['items'] ?? [];
        //     return view('books.google-results', compact('books'));
        // } else {
        //     return view('books.google-results', ['books' => [], 'message' => 'Error fetching from Google Books']);
        // }
    }

    public function importFromGoogle(Request $request)
    {
        // Validação
        $validator = Validator::make($request->all(), [
            'google_book_id' => 'required|string',
            'name' => 'required|string',
            'author' => 'nullable|string',
            'detail' => 'nullable|string',
            'price' => 'nullable|numeric',
            'synopsis' => 'nullable|string',
            'images' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->route('books.index')->withErrors($validator)->withInput();
        }

        $imagePaths = [];

        // Se uma URL de imagem foi enviada
        if ($request->filled('images')) {
            $imageUrl = $request->input('images');

            try {
                $response = Http::get($imageUrl);
                if ($response->successful()) {
                    $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                    $filename = date('YmdHis') . "_" . Str::random(10) . "." . $extension;
                    $imagePath = public_path('images/' . $filename);

                    file_put_contents($imagePath, $response->body());

                    $imagePaths[] = $filename;
                }
            } catch (\Exception $e) {
                return redirect()->route('books.index')->with('error', 'Erro ao baixar a imagem.');
            }
        }

        // Criação do livro
        $book = new Books();
        $book->name = $request->name;
        $book->author = $request->author;
        $book->detail = $request->detail;
        $book->price = $request->price ?? 0;
        $book->synopsis = $request->synopsis;
        $book->image = json_encode($imagePaths);
        $book->save();

        return redirect()->route('books.index')->with('success', 'Livro importado com sucesso!');
    }

    public function details($id)
    {
        $book = Books::with('reviews.user')->findOrFail($id);
        return view('books.details', compact('book'));
    }

    public function recommendations()
    {
        $user = User::find(Auth::id());

        //Baseado na wishlist
        $likedGenres = $user->likedBooks()->pluck('detail')->unique();
        $byGenre = Books::whereIn('detail', $likedGenres)->take(8)->get();

        //Baseado no autor
        $likedAuthors = $user->likedBooks()->pluck('author')->unique();
        $byAuthor = Books::whereIn('author', $likedAuthors)->take(8)->get();

        //Populares
        $popular = Books::withCount('orders')
            ->orderByDesc('orders_items_count')
            ->take(8)
            ->get();

        return view('books.recommendations', compact('byGenre', 'byAuthor', 'popular'));
    }

    public function bestRated()
    {
        $bestRated = Books::withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(8)
            ->get();

        return view('books.best-rated', compact('bestRated'));
    }
}
