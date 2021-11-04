<div class="alert alert-danger text-left">
  @foreach ($errors->all() as $error)
    <p class="text-start">・{{ $error }}</p>
  @endforeach
</div>
