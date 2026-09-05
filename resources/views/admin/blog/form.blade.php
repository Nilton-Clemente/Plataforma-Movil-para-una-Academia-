<div class="col-12">
    <label class="form-label">Titulo</label>
    <input type="text" name="title" value="{{ old('title', $blogPost?->title) }}" class="form-control" required>
</div>
<div class="col-12">
    <label class="form-label">Imagen URL</label>
    <input type="url" name="image_url" value="{{ old('image_url', $blogPost?->image_url) }}" class="form-control" placeholder="https://ejemplo.com/imagen.jpg">
</div>
<div class="col-12">
    <label class="form-label">Contenido</label>
    <textarea name="content" rows="8" class="form-control" required>{{ old('content', $blogPost?->content) }}</textarea>
</div>
