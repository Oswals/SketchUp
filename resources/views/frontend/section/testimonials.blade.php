{{-- ============================================================
     TESTIMONIALS SECTION — fix swiper
     ============================================================ --}}

<!-- Testimonials Section -->
<section id="testimonials" class="testimonials section light-background">

    <div class="container section-title" data-aos="fade-up">
        <h2>Testimonials</h2>
        <p>What our users say about DownConvert</p>
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="swiper init-swiper">
            <script type="application/json" class="swiper-config">
                {
                  "loop": true,
                  "speed": 600,
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": "auto",
                  "pagination": {
                    "el": ".swiper-pagination",
                    "type": "bullets",
                    "clickable": true
                  },
                  "breakpoints": {
                    "320": {
                      "slidesPerView": 1,
                      "spaceBetween": 40
                    },
                    "1200": {
                      "slidesPerView": 3,
                      "spaceBetween": 1
                    }
                  }
                }
            </script>
            <div class="swiper-wrapper">

                <div class="swiper-slide">
                    <div class="testimonial-item">
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            "I received a SketchUp 2026 file from a client, but I was still using SketchUp 2020. Instead of asking them to export it again, I used DownConvert and had a compatible file in less than a minute. It saved me time and made me look professional."
                        </p>
                        <div class="profile mt-auto">
                            <img src="{{ asset('frontend/assets/img/testimonials/testimonials-1.jpg') }}" class="testimonial-img" alt="Michael R.">
                            <h3>Michael R.</h3>
                            <h4>Architect, United States</h4>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="testimonial-item">
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            "As an interior designer, I regularly receive files from suppliers and collaborators using different SketchUp versions. DownConvert has become part of my workflow. Fast, simple, and much cheaper than upgrading software just to open a few files."
                        </p>
                        <div class="profile mt-auto">
                            <img src="{{ asset('frontend/assets/img/testimonials/testimonials-2.jpg') }}" class="testimonial-img" alt="Sarah T.">
                            <h3>Sarah T.</h3>
                            <h4>Interior Designer, United Kingdom</h4>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="testimonial-item">
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            "I was skeptical at first, but the conversion worked perfectly. Materials and textures were preserved, and the file opened immediately in SketchUp 2019. This tool saved me hours of frustration."
                        </p>
                        <div class="profile mt-auto">
                            <img src="{{ asset('frontend/assets/img/testimonials/testimonials-3.jpg') }}" class="testimonial-img" alt="David L.">
                            <h3>David L.</h3>
                            <h4>Freelance 3D Artist, Canada</h4>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="testimonial-item">
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            "Our studio works with architects across three countries, and version mismatches were a constant headache. DownConvert eliminated that friction entirely. We now share files with any client regardless of their SketchUp version — no back-and-forth, no delays."
                        </p>
                        <div class="profile mt-auto">
                            <img src="{{ asset('frontend/assets/img/testimonials/testimonials-4.jpg') }}" class="testimonial-img" alt="Lucas M.">
                            <h3>Lucas M.</h3>
                            <h4>BIM Manager, Netherlands</h4>
                        </div>
                    </div>
                </div>



            </div>
            <div class="swiper-pagination"></div>
        </div>

    </div>

</section><!-- /Testimonials Section -->
