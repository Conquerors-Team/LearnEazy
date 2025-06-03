    <header class="main_menu single_page_menu">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand logo_1" href="{{URL_HOME}}"> <img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt="logo"> </a>
                        <a class="navbar-brand logo_2" href="{{URL_HOME}}"> <img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt="logo"> </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item justify-content-end"
                            id="navbarSupportedContent">
                            <ul class="navbar-nav align-items-center">
                           <li class="nav-item">
                                    <a class="nav-link" href="{{route('site.about')}}">About</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{route('site.courses')}}">Courses</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('site.blog')}}">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('site.pricing')}}">Pricing</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('site.practice')}}">Practice</a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('site.contact')}}">Contact</a>
                                </li>
                                <li class="nav-item">
                                    <!--<a  href="#" class="btn_1" data-target="#registerModal" data-toggle="modal">Register</a>-->
                                    <a  href="{{route('site.institute')}}" class="btn_1">Institute</a>
                                     <a href="#" class="btn_2" data-toggle="modal" data-target="#loginModal">Login </a>
                                    <!-- <a  href="{{route('user.register')}}" class="btn_1">Register as</a>
                                    <a href="{{route('user.login')}}" class="btn_2">Login </a> -->

                                     <!-- <button type="button" class="btn_1 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       Register as&nbsp;<span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu">
                                          <li><a href="{{route('user.register')}}">Student</a></li>
                                          <li><a href="{{route('user.register', ['role' => 'institute'])}}">Institute</a></li>
                                      </ul>
                                     <a href="{{route('user.login')}}" class="btn_2">Login </a> -->
                                </li>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Header part end