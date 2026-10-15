@php($m = $menu ?? null)
<div class="col-12">
    <label class="form-label" for="name">Nama menu</label>
    <input type="text" id="name" name="name" value="{{ old('name', $m?->name) }}" class="form-control @error('name') is-invalid @enderror" required>
</div>
<div class="col-12">
    <label class="form-label" for="description">Deskripsi</label>
    <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $m?->description) }}</textarea>
</div>
<div class="col-md-6">
    <label class="form-label" for="price">Harga per porsi (Rp)</label>
    <input type="number" id="price" name="price" min="1000" step="500" value="{{ old('price', $m?->price) }}" class="form-control @error('price') is-invalid @enderror" required>
</div>
<div class="col-md-6">
    <label class="form-label" for="photo">Foto menu {{ $m ? '(kosongkan jika tidak diganti)' : '' }}</label>
    <input type="file" id="photo" name="photo" accept="image/png,image/jpeg,image/webp" class="form-control @error('photo') is-invalid @enderror" {{ $m ? '' : 'required' }}>
    <div class="form-text">JPG, PNG, atau WEBP. Maksimal 2 MB.</div>
</div>
@if($m?->photo_url)
    <div class="col-12"><img src="{{ $m->photo_url }}" alt="Foto saat ini" class="rounded" style="max-height:140px"></div>
@endif
<div class="col-12">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" id="is_available" name="is_available" value="1" @checked(old('is_available', $m?->is_available ?? true))>
        <label class="form-check-label" for="is_available">Tersedia untuk dipesan</label>
    </div>
</div>
