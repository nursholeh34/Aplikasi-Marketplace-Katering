<div class="col-md-6">
    <label class="form-label" for="name">Nama penanggung jawab</label>
    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
</div>
<div class="col-md-6">
    <label class="form-label" for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="email">
</div>
<div class="col-md-6">
    <label class="form-label" for="password">Kata sandi</label>
    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
    <div class="form-text">Minimal 8 karakter, mengandung huruf dan angka.</div>
</div>
<div class="col-md-6">
    <label class="form-label" for="password_confirmation">Ulangi kata sandi</label>
    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
</div>
