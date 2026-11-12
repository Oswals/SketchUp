{{-- FINAL CTA Section - Full width, collée au footer --}}
<section id="final-cta">

    <div class="cta-blob cta-blob-1" aria-hidden="true"></div>
    <div class="cta-blob cta-blob-2" aria-hidden="true"></div>
    <div class="cta-blob cta-blob-3" aria-hidden="true"></div>

    <div class="cta-content">
        <p class="cta-eyebrow">No more version headaches</p>
        <h2 class="cta-heading">
            Stop Fighting SketchUp
            <span class="cta-heading-accent">&nbsp;Compatibility Issues&nbsp;</span>
        </h2>
        <p class="cta-sub">Convert your file now and get back to work.</p>

        <a href="#hero-conversion" class="cta-btn" onclick="smoothScrollToHero(event)">
            <i class="bi bi-arrow-repeat me-2"></i>
            Convert My File
        </a>

        <div class="cta-trust">
            <span><i class="bi bi-lightning-charge-fill"></i> Instant conversion</span>
            <span class="cta-trust-dot" aria-hidden="true">·</span>
            <span><i class="bi bi-shield-check"></i> Files auto-deleted</span>
            <span class="cta-trust-dot" aria-hidden="true">·</span>
            <span><i class="bi bi-infinity"></i> All SKP versions</span>
        </div>
    </div>
</section>

<style>
/* Neutralise tout espace autour de la section */
#final-cta {
    background: linear-gradient(135deg, #3951bcff 0%, #194ec7ff 100%);
    padding: 55px 24px;
    text-align: center;
    position: relative;
    overflow: hidden;
    width: 100%;
    margin: 0 !important;
    display: block;
}

/* Supprime tout margin-bottom sur l'élément précédent */
#final-cta + *,
* + #final-cta {
    margin: 0 !important;
}

.cta-blob {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.07);
    pointer-events: none;
}
.cta-blob-1 { width: 300px; height: 300px; top: -110px; left: -80px; }
.cta-blob-2 { width: 200px; height: 200px; bottom: -70px; right: -40px; }
.cta-blob-3 { width: 130px; height: 130px; top: 50%; right: 14%; transform: translateY(-50%); }

.cta-content {
    position: relative;
    z-index: 2;
    max-width: 640px;
    margin: 0 auto;
}

.cta-eyebrow {
    display: inline-block;
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.8px;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 10px;
}

.cta-heading {
    font-size: 2.1rem;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.2;
    margin-bottom: 0.9rem;
    text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.cta-heading-accent {
    background-color: #ffffff;
    color: #3150bf;
    padding: 1px 6px;
    border-radius: 5px;
    white-space: nowrap;
}

.cta-sub {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 300;
    margin-bottom: 1.8rem;
}

.cta-btn {
    display: inline-flex;
    align-items: center;
    background: #ffffff;
    color: #3150bf;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 12px 36px;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
    transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
    margin-bottom: 1.6rem;
}

.cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(0, 0, 0, 0.22);
    background: #f0f4ff;
    color: #3150bf;
    text-decoration: none;
}

.cta-trust {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    font-size: 0.78rem;
    color: rgba(255, 255, 255, 0.68);
}

.cta-trust span {
    display: flex;
    align-items: center;
    gap: 4px;
}

.cta-trust-dot { opacity: 0.4; }
.cta-trust i { font-size: 0.8rem; }

@media (max-width: 768px) {
    #final-cta { padding: 44px 20px; }
    .cta-heading { font-size: 1.6rem; }
    .cta-heading-accent { white-space: normal; }
}
</style>

<script>
function smoothScrollToHero(e) {
    e.preventDefault();
    const hero = document.getElementById('hero-conversion');
    if (hero) {
        const top = hero.getBoundingClientRect().top + window.pageYOffset - 80;
        window.scrollTo({ top: top, behavior: 'smooth' });
    }
}
</script>
