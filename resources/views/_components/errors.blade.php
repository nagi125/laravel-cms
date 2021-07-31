<div class="alert alert-danger text-left">
  @foreach ($errors->all() as $error)
    <p>・{{ $error }}</p>
  @endforeach
</div>
