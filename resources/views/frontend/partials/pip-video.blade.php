{{-- Picture-in-Picture Video Widget --}}
<div id="pip-widget" style="display:none;">

    {{-- Lecteur vidéo --}}
    <div id="pip-player">
        <video id="pip-video" autoplay muted playsinline disablePictureInPicture controlsList="nodownload nofullscreen noremoteplayback">
            <source src="{{ asset('frontend/assets/videos/promo.mp4') }}" type="video/mp4">
        </video>

        {{-- Bouton fermer --}}
        <button id="pip-close" title="Fermer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>

    {{-- Bouton replay (affiché après la fin ou après fermeture) --}}
    <div id="pip-replay" style="display:none;">
        <p id="pip-help-text">Need help getting started?</p>
        <button id="pip-replay-btn" title="Revoir la vidéo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                 fill="currentColor">
                <path d="M8 5v14l11-7z"/>
            </svg>
        </button>
    </div>

</div>

<style>
#pip-widget {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9999;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* ─── Lecteur ─── */
#pip-player {
    position: relative;
    width: 340px;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.28), 0 2px 8px rgba(0,0,0,0.15);
    animation: pip-slide-in 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    background: #000;
}

#pip-video {
    width: 100%;
    display: block;
    border-radius: 14px;
}

/* Désactiver le curseur PiP natif au survol */
#pip-video::-webkit-media-controls-picture-in-picture-button {
    display: none !important;
}

/* ─── Bouton fermer ─── */
#pip-close {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 32px;
    height: 32px;
    background: rgba(0,0,0,0.55);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: background 0.2s, transform 0.2s;
    padding: 0;
    backdrop-filter: blur(4px);
}

#pip-close svg {
    width: 14px;
    height: 14px;
}

#pip-close:hover {
    background: rgba(220, 38, 38, 0.85);
    transform: scale(1.1);
}

/* ─── Zone replay ─── */
#pip-replay {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
    animation: pip-slide-in 0.3s ease;
}

/* Texte d'aide */
#pip-help-text {
    margin: 0;
    font-size: 0.75rem;
    font-weight: 500;
    color: #fff;
    background: rgba(21, 101, 192, 0.82);
    backdrop-filter: blur(6px);
    padding: 5px 10px;
    border-radius: 20px;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18);
}

/* Bouton replay */
#pip-replay-btn {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1565c0, #0d47a1);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 4px 16px rgba(21,101,192,0.45);
    transition: transform 0.2s, box-shadow 0.2s;
    padding: 0;
}

#pip-replay-btn svg {
    width: 22px;
    height: 22px;
    margin-left: 3px;
}

#pip-replay-btn:hover {
    transform: scale(1.12);
    box-shadow: 0 6px 22px rgba(21,101,192,0.55);
}

/* ─── Animation d'entrée ─── */
@keyframes pip-slide-in {
    from { opacity: 0; transform: translateY(20px) scale(0.92); }
    to   { opacity: 1; transform: translateY(0)   scale(1); }
}

@media (max-width: 480px) {
    #pip-widget  { bottom: 16px; right: 16px; }
    #pip-player  { width: 260px; }
}
</style>

<script>
(function () {
    // Deux clés distinctes :
    // - pip_ended  : la vidéo s'est terminée → montrer le bouton replay au prochain refresh
    // - pip_gone   : l'utilisateur a cliqué ✕ APRÈS le replay → ne plus rien montrer
    const KEY_ENDED = 'pip_ended';
    const KEY_GONE  = 'pip_gone';

    const widget     = document.getElementById('pip-widget');
    const player     = document.getElementById('pip-player');
    const video      = document.getElementById('pip-video');
    const closeBtn   = document.getElementById('pip-close');
    const replayWrap = document.getElementById('pip-replay');
    const replayBtn  = document.getElementById('pip-replay-btn');

    // ── L'utilisateur a définitivement fermé → rien à faire ──
    if (sessionStorage.getItem(KEY_GONE)) return;

    // Afficher le widget dans tous les cas
    widget.style.display = 'block';

    // ── La vidéo était déjà terminée (refresh après fin) → montrer replay directement ──
    if (sessionStorage.getItem(KEY_ENDED)) {
        player.style.display = 'none';
        replayWrap.style.display = 'flex';
        return; // le reste des listeners n'est pas nécessaire
    }

    // ── Fin naturelle de la vidéo ──
    video.addEventListener('ended', function () {
        sessionStorage.setItem(KEY_ENDED, '1');
        player.style.display = 'none';
        replayWrap.style.display = 'flex';
    });

    // ── Fermer manuellement PENDANT la lecture ──
    // → on considère que la vidéo est "terminée" : on montre le replay au prochain refresh
    closeBtn.addEventListener('click', function () {
        video.pause();
        player.style.display = 'none';
        replayWrap.style.display = 'flex';
        sessionStorage.setItem(KEY_ENDED, '1');
    });

    // ── Replay ──
    replayBtn.addEventListener('click', function () {
        replayWrap.style.display = 'none';
        player.style.display = 'block';
        video.currentTime = 0;
        video.play();
    });

    // ── Fermer le widget depuis l'état replay (clic en dehors ou via une éventuelle croix future) ──
    // Pour l'instant : un double-clic sur le bouton replay ferme définitivement
    replayBtn.addEventListener('dblclick', function () {
        widget.style.display = 'none';
        sessionStorage.setItem(KEY_GONE, '1');
        sessionStorage.removeItem(KEY_ENDED);
    });

})();
</script>