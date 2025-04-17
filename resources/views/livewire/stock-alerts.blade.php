<div>
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h5 class="modal-title">⚠️ Products Stock Levels</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach ($products as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $product->name }}
                            <span>
                                <span class="badge bg-danger rounded-pill">{{ $product->stock }}
                                    left</span>

                                <a class="badge bg-primary rounded-pill"
                                    href="{{ route('products.edit', ['product' => $product->uuid]) }}">Restock</a>
                            </span>
                        </li>
                    @endforeach
                </ul>
                <div class="mx-3 mt-3">
                    @isset($products)
                        {{ $products->links() }}
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
