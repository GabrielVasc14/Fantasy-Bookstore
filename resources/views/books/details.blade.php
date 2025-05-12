@extends('books.layout')

@section('content')
<div class="row mt-4">
    <div class="col-md-4">
        @php
            $images = json_decode($book->image, true);
        @endphp
        <img src="{{ asset('images/' . $images[0]) }}" alt="{{ $book->name }}" class="img-fluid">
    </div>

    <div class="col-md-8">
        <h2>{{ $book->name }}</h2>
        <p><strong>Condition:</strong> {{ $book->condition }}</p>
        @if ($book->stock !== null && $book->stock <= 5)
            <span class="badge bg-danger text-white fs-6 mb-3">Last stock: {{ $book->stock }}</span>
        @elseif ($book->stock !== null && $book->stock <= 10 && $book->stock > 5)
            <span class="badge bg-warning text-dark fs-6 mb-3">Low stock: {{ $book->stock }}</span>
        @else
            <p><strong>Stock:</strong> {{ $book->stock }}</p>
        @endif
        @if ($book->is_special_edition)
            <span class="badge bg-warning text-dark"> Special Edition</span>
        @endif
        <p><strong>Author:</strong> {{ $book->author }}</p>
        <p><strong>Detail:</strong> {{ $book->detail }}</p>
        <p><strong>Price:</strong> {{ $book->price }}</p>
        @if($book->audio_path)
            <p><strong>Audiobook:</strong>
                <audio controls style="width:100%; margin-bottom:1rem;">
                    <source src="{{ asset($book->audio_path) }}" type="audio/mpeg">
                    Seu navegador não suporta element audio.
                </audio>
            </p>
        @endif
        @if($book->ebook_path)
            <p><strong>Ebook:</strong>
                <a href="{{ asset($book->ebook_path) }}" target="_blank" class="btn btn-outline-primary mb-3"><i class="fa fa-file-pdf"></i> Download eBook (PDF)</a>
                <!-- ou embed viewer -->
                <iframe src="{{ asset($book->ebook_path) }}" width="100%" height="600px"></iframe>
            </p>
        @endif


        <div class="mb-3">
            <button class="btn btn-warning btn-block btn-sm text-center add-to-cart" data-id="{{ $book->id }}" data-format="physical">
                <i class="fa-solid fa-cart-plus"></i> Add to cart</button>
            <button class="btn btn-sm btn-warning add-to-cart" data-id="{{ $book->id }}" data-format="ebook">
                <i class="fa fa-file-pdf"></i> Add eBook</button>
            <button class="btn btn-sm btn-warning add-to-cart" data-id="{{ $book->id }}" data-format="audio">
                <i class="fa fa-headphones"></i> Add Audiobook</button>

            @auth
                @if(auth()->user()->likedBooks()->where('book_id', $book->id)->exists())
                    <button type="button" class="btn btn-danger btn-sm toggle-wishlist" data-id="{{ $book->id }}">
                        <i class="fa-solid fa-heart-broken"></i> Remove from wishlist
                    </button>
                @else
                    <button type="button" class="btn btn-primary btn-sm toggle-wishlist" data-id="{{ $book->id }}">
                        <i class="fa-regular fa-heart"></i> Add to wishlist
                    </button>
                @endif
            @endauth

            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-regular fa-heart"></i> Login to add to wishlist
                </a>
            @endguest

            <div class="mt-3">
                <p><strong>Share this book</strong></p>
                @php
                    $shareUrl = urlencode(request()->fullUrl());
                    $bookName = urlencode($book->name);
                @endphp

                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="btn btn-sm btn-primary">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text=Check out this book: {{ $bookName }}" target="_blank" class="btn btn-sm btn-info">
                    <i class="fab fa-twitter"></i> Twitter
                </a>
                <a href="https://wa.me/?text={{ $bookName }}%20{{ $shareUrl }}" target="_blank" class="btn btn-sm btn-success">
                    <i class="fab fa-whatsapp"></i> Whatsapp
                </a>
                <a href="mailto:?subject=Check out this book&body={{ $bookName }} - {{ $shareUrl }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-envelope"></i> Email
                </a>
                <button id="copyLinkBtn" class="btn btn-sm btn-dark">
                    <i class="fas fa-link"></i> Copy Link
                </button>

                <span id="copyMessage" class="text-success ms-2" style="display: none;">Link Copied!</span>
            </div>
        </div>
    </div>
</div>

<hr>
<p>
    <strong>Average Rating:</strong>
    {{ $book->averageRating() }} / 5
    <span class="text warning">
        @for ($i = 0; $i < floor($book->averageRating()); $i++) ★ @endfor
        @for($i=floor($book->averageRating()); $i<5; $i++) ☆ @endfor
    </span>
</p>

<div class="mt-4">
    <h4>Reviews ({{ $book->reviews->count() }})</h4>

    @auth
        <form action="{{ route('reviews.store') }}" method="POST" class="mb-4">
            @csrf
            <input type="hidden" name="book_id" value="{{ $book->id }}">
            <div class="mb-2">
                <label for="rating">Your Rating:</label>
                <div class="star-rating">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                        <label for="star{{ $i }}" title="{{ $i }} star{{ $i>1?'s':'' }}">
                            <i class="fa fa-star"></i>
                        </label>
                    @endfor
                </div>
            </div>
            <div class="mb-2">
                <label for="comment">Comment:</label>
                <textarea name="comment" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Submit Review</button>
        </form>
    @endauth

    @foreach ($book->reviews as $review)
        <div class="border p-3 mb-3 rounded">
            <strong>{{ $review->user->name }}</strong>
            <div class="text-warning">
                @for ($i = 0; $i < $review->rating; $i++) ★ @endfor
                @for ($i = $review->rating; $i < 5; $i++) ☆ @endfor
            </div>
            <p>{{ $review->comment }}</p>
            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>

            <div class="ps-3 mt-2 border-start">
                @foreach ($review->comments as $reply)
                    <div class="mb-1">
                        <strong>{{ $reply->user->name }}:</strong> {{ $reply->comment }}
                    </div>
                @endforeach
            </div>

            @auth
                <form action="{{ route('review-comments.store') }}" method="POST" class="mt-2">
                    @csrf
                    <input type="hidden" name="review_id" value="{{ $review->id }}">
                    <textarea name="comment" class="form-control mb-1" rows="2" placeholder="Reply..."></textarea>
                    <button type="submit" class="btn btn-sm btn-secondary">Reply</button>
                </form>
            @endauth

            @if (auth()->id() === $review->user_id)
                <form action="{{ route('reviews.destroy', $review) }}" method="post" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            @endif
        </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
    //Adicionando ao cart
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const bookId = this.getAttribute('data-id');
            let format = this.getAttribute('data-format');

            fetch(`/add-to-cart/${bookId}/${format}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Book added to the cart.');
                    window.location.reload();
                } else {
                    toastr.error('Error on add the book to the cart.');
                }
            })
            .catch(error => {
                console.error(error);
                toastr.error('Error in AJAX requisition,')
            });
        });
    });

    //Adicionando a wishlist
    $(document).ready(function () {
        $('.toggle-wishlist').on('click', function () {
            let button = $(this);
            let bookId = button.data('id');

            $.ajax({
                url: `/books/toggle-like/${bookId}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.liked) {
                        button.html('<i class="fa-solid fa-heart-broken"></i> Remove from wishlist');
                        button.removeClass('btn-primary').addClass('btn-danger');
                        toastr.success('Book added to the wishlist.');
                    } else {
                        button.html('<i class="fa-regular fa-heart"></i> Add to wishlist');
                        button.removeClass('btn-danger').addClass('btn-primary');
                        toastr.success('Book removed to the wishlist.');
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Something went wrong. Please try again.');
                }
            });
        });
    });

    document.getElementById('copyLinkBtn').addEventListener('click', function () {
        const link = window.location.href;

        if(navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(link).then(() => {
                showCopyMessage();
            }).catch(err => {
                fallbackCopyText(link);
            });
        } else {
            fallbackCopyText(link);
        }
    });

    function fallbackCopyText(text) {
        const textarea = document.createElement("textarea");

        textarea.value = text;
        textarea.style.position = "fixed";
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        try {
            document.execCommand('copy');
            showCopyMessage();
        } catch (err) {
            toastr.error('Failed to copy the link.');
        }
        document.body.removeChild(textarea);
    }

    function showCopyMessage() {
        const message = document.getElementById('copyMessage');

        message.style.display = 'inline';
        setTimeout(() => {
            message.style.display = 'none';
        }, 2000);
    }

</script>
@endsection
