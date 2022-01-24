<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-fixed-top ">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{url(config('dynamic-extract.prefix').'/report/new')}}">
        <img src="{{asset('enhacudima/dynamic-extract/icons/database.png')}}" alt="" width="30" height="24" class="d-inline-block align-text-top">
        Dynamic Extract
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="{{url(config('dynamic-extract.prefix').'/report/index')}}"><i class="far fa-file-excel"></i> Files</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url(config('dynamic-extract.prefix').'/report/new')}}"><i class="fas fa-database"></i> Generate</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url(config('dynamic-extract.prefix').'/report/config')}}"><i class="fa  fa-cog"></i> Configuration</a>
        </li>
      </ul>
    </div>
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <a   class="nav-link text-muted" href="https://github.com/enhacudima" target="_blank"><i class="fab fa-github"></i></a>
            </li>
            @if(Cookie::get('access_user_token'))
            <li class="nav-item">
                <a   class="nav-link text-muted" href="{{url(config('dynamic-extract.prefix').'/sign-out')}}" ><i class="fas fa-sign-out-alt"></i></a>
            </li>
            @endif
        </ul>
  </div>
</nav>
<style>
    .navbar-fixed-top {
    position: fixed;
    right: 0;
    left: 0;
    z-index: 1030;
}
</style>
