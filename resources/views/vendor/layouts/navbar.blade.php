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
          <a class="nav-link" aria-current="page" href="{{url(config('dynamic-extract.prefix').'/report/index')}}"><i class="fas fa-download"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url(config('dynamic-extract.prefix').'/report/new')}}"><i class="fas fa-list"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url(config('dynamic-extract.prefix').'/report/config')}}"><i class="fa  fa-cog"></i></a>
        </li>
      </ul>
    </div>
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <a   class="nav-link text-muted" href="https://github.com/enhacudima" target="_blank"><i class="fab fa-github"></i></a>
            </li>
             <li class="nav-item">
                <a   class="nav-link text-muted" href="{{url(config('dynamic-extract.my_home_page'))}}" target="_blank"><i class="fas fa-home"></i></a>
            </li>
            @if(config('dynamic-extract.auth'))
                @if (config('dynamic-extract.sign_out_method') == 'post')
                    <li class="nav-item">
                        <form method="POST" action="{{url(config('dynamic-extract.sign_out'))}}" >
                                @csrf
                            <button type="submit"  class="btn btn-dark nav-link text-muted">{{auth()->user()->name}} <i class="fas fa-sign-out-alt"></i></button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a   class="nav-link text-muted" href="{{url(config('dynamic-extract.sign_out'))}}" ><i class="fas fa-sign-out-alt"></i></a>
                    </li>
                @endif
            @else
                @if(Cookie::get('access_user_token'))
                <li class="nav-item">
                    <a   class="nav-link text-muted" href="{{url(config('dynamic-extract.prefix').'/sign-out')}}" ><i class="fas fa-sign-out-alt"></i></a>
                </li>
                @endif
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
