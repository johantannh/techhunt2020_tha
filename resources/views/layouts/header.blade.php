
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3 >Govtech TechHunt2020</h3>
        </div>

        <ul class="list-unstyled components">
            <p>The related user stories examples are below</p>
            @if (Request::is('users/upload')) 
              <li class="active">
            @else
              <li>
            @endif  
                <a href="{{ url('/users/upload') }}">Upload</a>
            </li>
            @if (Request::is('users/dashboard')) 
              <li class="active">
            @else
              <li>
            @endif  
                <a href="{{ url('/users/dashboard') }}">Dashboard</a>
            </li>
        </ul>
    </nav>
