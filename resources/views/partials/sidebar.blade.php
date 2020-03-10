<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
      <ul class="nav flex-column">
          <li class="nav-item">
              <a  href="{{ route('home') }}">
                  <img src="{{ asset('img/logo.png') }}" alt="" style="width: 100%">
              </a>
          </li>
          <li class="nav-item">
          <a class="nav-link active" href="{{ route('home') }}">
            <span data-feather="home"></span>
            Tableau de bord <span class="sr-only">(current)</span>
          </a>
        </li>
          @if(session()->get('user_role') == 'SuperAdmin')
              <li class="nav-item">
                  <a class="nav-link" href="{{ route('roles.index') }}">
                      <span data-feather="file"></span>
                      Rôle et permissions
                  </a>
              </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <span data-feather="file"></span>
                    Utilisateurs
                </a>
            </li>
          @elseif(session()->get('user_role') == 'Admin')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('users.index') }}">
                <span data-feather="users"></span>
                Clients
              </a>
            </li>
          @else
              <li class="nav-item">
                  <a class="nav-link" href="{{ route('portefeuille.show') }}">
                      <span data-feather="users"></span>
                      Gérer le portefeuille
                  </a>
              </li>
          @endif
          <li class="nav-item">
              <a class="nav-link" href="{{ route('crypto.index') }}">
                  <span data-feather="users"></span>
                  Crypto monnaies
              </a>
          </li>
      </ul>
    </div>
  </nav>
