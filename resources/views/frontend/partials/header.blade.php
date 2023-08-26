    <!-- HEADER -->
    <header>
        <div class="container">
            <div class="row">

                <div class="col-md-4 col-sm-5">
                    <p>Welcome to Care-Logs</p>
                </div>

                <div class="col-md-8 col-sm-7 text-align-right">
                    <span class="phone-icon"><i class="fa fa-phone"></i> 010-060-0160</span>
                    <span class="date-icon"><i class="fa fa-calendar-plus-o"></i> 6:00 AM - 10:00 PM (Mon-Fri)</span>
                    <span class="email-icon"><i class="fa fa-envelope-o"></i> <a href="#">info@company.com</a></span>
                    @if (Auth::user())
                    <span class="email-icon"><i class="fa fa-sign-out"></i> <a href="#" onclick="event.preventDefault();
                                                                    document.getElementById('logout-form').submit();" class="dropdown-item">
                            Logout
                        </a> </span>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    @endif
                </div>

            </div>
        </div>
    </header>


    <!-- MENU -->
    <section class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">

            <div class="navbar-header">
                <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                </button>

                <!-- lOGO TEXT HERE -->
                <a href="index.html" class="navbar-brand"><i class="fa fa-h-square"></i>ealth Center</a>
            </div>

            <!-- MENU LINKS -->
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/" class="smoothScroll">Home</a></li>
                    <li><a href="#about" class="smoothScroll">About Us</a></li>
                    <li><a href="{{ route('frontend.doctor') }}" class="smoothScroll">Doctors</a></li>
                    <!-- <li><a href="#news" class="smoothScroll">News</a></li> -->
                    <li><a href="#google-map" class="smoothScroll">Contact</a></li>
                    @if (Auth::user())
                    <li><a href="#news" class="smoothScroll">Appointment</a></li>
                    <li><a href="#news" class="smoothScroll">Prescription</a></li>
                    <li class="appointment-btn"><a href="#appointment">Make an appointment</a></li>
                    @else
                    <li><a href="{{ route('register') }}" class="smoothScroll">Sign up</a></li>
                    <li><a href="{{ route('login') }}" class="smoothScroll">Sign in</a></li>
                    @endif
                </ul>
            </div>

        </div>
    </section>