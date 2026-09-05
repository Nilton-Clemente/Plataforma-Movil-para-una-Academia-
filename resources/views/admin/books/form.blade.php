<div class="col-md-8">
    <label class="form-label">Titulo</label>
    <input type="text" name="title" value="{{ old('title', $book?->title) }}" class="form-control" required>
</div>
<div class="col-md-4">
    <label class="form-label">Precio</label>
    <input type="number" name="price" value="{{ old('price', $book?->price) }}" class="form-control" min="0" max="999999.99" step="0.01" required>
</div>
<div class="col-12">
    <label class="form-label">Descripcion</label>
    <textarea name="description" rows="5" class="form-control" required>{{ old('description', $book?->description) }}</textarea>
</div>
