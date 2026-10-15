@php($m = $merchant ?? null)
<div class="col-md-6">
    <label class="form-label" for="company_name">Nama perusahaan katering</label>
    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $m?->company_name) }}" class="form-control @error('company_name') is-invalid @enderror" required>
</div>
<div class="col-md-6">
    <label class="form-label" for="cuisine_type">Jenis makanan utama</label>
    <select id="cuisine_type" name="cuisine_type" class="form-select @error('cuisine_type') is-invalid @enderror" required>
        <option value="">— Pilih —</option>
        @foreach($cuisines as $c)
            <option value="{{ $c }}" @selected(old('cuisine_type', $m?->cuisine_type) === $c)>{{ $c }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-8">
    <label class="form-label" for="address">Alamat</label>
    <input type="text" id="address" name="address" value="{{ old('address', $m?->address) }}" class="form-control @error('address') is-invalid @enderror" required>
</div>
<div class="col-md-4">
    <label class="form-label" for="city">Kota</label>
    <input type="text" id="city" name="city" value="{{ old('city', $m?->city) }}" class="form-control @error('city') is-invalid @enderror" required>
</div>
<div class="col-md-6">
    <label class="form-label" for="phone">Kontak (telepon/WhatsApp)</label>
    <input type="text" id="phone" name="phone" value="{{ old('phone', $m?->phone) }}" class="form-control @error('phone') is-invalid @enderror" required>
</div>
<div class="col-12">
    <label class="form-label" for="description">Deskripsi katering</label>
    <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $m?->description) }}</textarea>
</div>
