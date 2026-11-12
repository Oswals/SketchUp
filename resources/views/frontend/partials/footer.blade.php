
<footer id="footer" class="footer position-relative dark-background" style="margin-top: -30px;">

{{--<div class="container footer-top">
    <div class="row gy-4">
    <div class="col-lg-4 col-md-6 footer-about">
        <a href="{{ url('/') }}" class="logo d-flex d-lg-none align-items-center me-auto">
            <img src="{{ asset('frontend/assets/img/4.png') }}" width="240" height="95">
        </a>
        <a href="{{ url('/') }}" class="logo d-none d-lg-flex align-items-center me-auto">
            <img src="{{ asset('frontend/assets/img/4.png') }}" width="200" height="700">
        </a>
        <div class="footer-contact pt-3">
        <p>A108 Adam Street</p>
        <p>New York, NY 535022</p>
        <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
        <p><strong>Email:</strong> <span>info@example.com</span></p>
        </div>
        <div class="social-links d-flex mt-4">
        <a href=""><i class="bi bi-twitter-x"></i></a>
        <a href=""><i class="bi bi-facebook"></i></a>
        <a href=""><i class="bi bi-instagram"></i></a>
        <a href=""><i class="bi bi-linkedin"></i></a>
        </div>
    </div>

    <div class="col-lg-2 col-md-3 footer-links">
        <h4>Useful Links</h4>
        <ul>
        @php
            $currentRoute = Route::currentRouteName();
            $isResultOrProfile = in_array($currentRoute, ['home']);
        @endphp

        <!-- Home -->
        @if($isResultOrProfile)
            <li><a href="#hero-conversion">Home</a></li>
            <li><a href="#about">About us</a></li>
            <li><a href="#pricing">Pricing</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
        @else
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('home') }}#about">About us</a></li>
            <li><a href="{{ route('home') }}#pricing">Pricing</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
        @endif

        </ul>
    </div>



    <div class="col-lg-6 col-md-12 footer-newsletter">
        <h4>Our Newsletter</h4>
        <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
        <form action="forms/newsletter.php" method="post" class="php-email-form">
        <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
        <div class="loading">Loading</div>
        <div class="error-message"></div>
        <div class="sent-message">Your subscription request has been sent. Thank you!</div>
        </form>
    </div>

    </div>
</div>--}}

<div class="container copyright text-center mt-4">
    <p>© <span>Copyright</span> <a
     href="https://how-to-sketchup.com/"
    class="px-1 sitename">how-to-sketchup.com</a><span>All Rights Reserved</span></p>
    <div class="credits">
    <!-- All the links in the footer should remain intact. -->
    <!-- You can delete the links only if you've purchased the pro version. -->
    <!-- Licensing information: https://bootstrapmade.com/license/ -->
    <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
    <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
    </div>
</div>

</footer>

