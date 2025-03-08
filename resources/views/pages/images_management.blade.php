@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ajouter un Produit</h2>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Nom du Produit</label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" id="description"></textarea>
        </div>
        <div class="form-group">
            <label for="price">Prix</label>
            <input type="number" name="price" class="form-control" id="price" required>
        </div>
        <div class="form-group">
            <label for="images">Images du Produit</label>
            <input type="file" name="images[]" class="form-control" id="images" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection

<div class="product-images">
    @foreach($product->images as $image)
        <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}" class="img-fluid">
    @endforeach
</div>
