<style>
    .video {
        margin: 0;
        padding: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }



    #player-container {
        position: fixed;
        inset: 0;
        display: flex;
        flex-direction: column;
        background: #000;
        overflow: hidden;
    }

    video {
        position: absolute !important;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;        /* ou 'fill' se quiser esticar sem manter proporção */
        background: #000;
        overflow: hidden;
        z-index: 99;
    }
    h5{
        background: transparent !important;
    }

    .controls {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.95));
        padding: 50px 40px 25px;
        display: flex;
        align-items: center;
        gap: 30px;
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 100;
    }

    #player-container:hover .controls {
        opacity: 1;
    }

    .btnPlayer {
        background: none;
        border: none;
        cursor: pointer;
        padding: 14px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .btnPlayer:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: scale(1.2);
    }

    .btnPlayer svg {
        width: 48px;
        height: 48px;
        fill: #ffffff;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.6));
    }

    /* Volume slider estilo premium */
    #volume {
        width: 140px;
        height: 6px;
        -webkit-appearance: none;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
        outline: none;
    }

    #volume::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
    }

    #close-btn {
        position: absolute;
        top: 25px;
        right: 30px;
        background: rgba(0, 0, 0, 0.7);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        font-size: 32px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 110;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;

    }
    #display-titulo {
        position: absolute;
        top: 25px;
        left: 10px;
        background: rgba(0, 0, 0, 0.7);

        color: white;
        font-size: 32px;

        height: 56px;

        cursor: pointer;
        z-index: 102;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    #close-btn:hover {
        background: #e50914;
        border-color: #e50914;
        transform: scale(1.1);
    }
    .progress-container {
        flex: 1;
        position: relative;
        height: 8px;
        margin: 0 20px;
        cursor: pointer;
    }

    .progress-bar {
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-filled {
        height: 100%;
        width: 0%;
        background: #e50914;
        transition: width 0.1s ease;
        border-radius: 4px;
    }

    .progress-thumb {
        position: absolute;
        top: 50%;
        left: 0%;
        width: 16px;
        height: 16px;
        background: #e50914;
        border: 3px solid white;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        opacity: 0;
        transition: opacity 0.2s, transform 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.6);
    }

    .progress-container:hover .progress-thumb {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.3);
    }

    .progress-container:hover .progress-filled {
        height: 10px;
        margin-top: -1px;
    }

    .progress-tooltip {
        position: absolute;
        bottom: 20px;
        left: 0;
        background: rgba(0,0,0,0.9);
        color: white;
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 14px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
        white-space: nowrap;
        transform: translateX(-50%);
    }

    .progress-container:hover .progress-tooltip {
        opacity: 1;
    }
</style>
<div class="row">
    <div class="col-12 ">
        <div class="nav-video">
            <button id="close-btn" class="float-end " title="Fechar">✕</button>
            <h5 class="px-4" id="display-titulo"></h5>
        </div>

        <video id="playerVideoElement" src="" preload="metadata"></video>

    </div>
   @include('players.parcial.tv-bar-control')

<script>
    const video = document.getElementById('playerVideoElement');
    const playPauseBtn = document.getElementById('play-pause');

    const volumeSlider = document.getElementById('volume');
    const closeBtn = document.getElementById('close-btn');
    const container = document.getElementById('player-container');

    var STORAGE_KEY = btoa(video.src);

    // Ícones Play ↔ Pause
    const playIcon = `<svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>`;
    const pauseIcon = `<svg viewBox="0 0 24 24"><path d="M6 4h4v16H6z m8 0h4v16h-4z"/></svg>`;

    function abrirFullscreen() {
        const elemento = document.getElementById("player-container");

        if (elemento.requestFullscreen) {
            elemento.requestFullscreen();
        } else if (elemento.mozRequestFullScreen) {       // Firefox
            elemento.mozRequestFullScreen();
        } else if (elemento.webkitRequestFullscreen) {    // Chrome, Safari, Opera
            elemento.webkitRequestFullscreen();
        } else if (elemento.msRequestFullscreen) {        // IE/Edge
            elemento.msRequestFullscreen();
        }
    }

    // Sair do fullscreen
    function sairFullscreen() {
        if (!document.fullscreenElement &&
            !document.mozFullScreenElement &&
            !document.webkitFullscreenElement &&
            !document.msFullscreenElement) {
            return; // já não está em fullscreen → não faz nada
        }

        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }

    function formatarSegundos(segundos) {
        // Garante que é número e trata valores negativos ou NaN
        segundos = Math.max(0, Number(segundos) || 0);

        const h = Math.floor(segundos / 3600);
        const m = Math.floor((segundos % 3600) / 60);
        const s = Math.floor(segundos % 60);

        // Sempre 2 dígitos nos minutos e segundos
        const mm = m.toString().padStart(2, '0');
        const ss = s.toString().padStart(2, '0');

        // Se tiver 1 hora ou mais → mostra a hora
        if (h > 0) {
            return `${h}:${mm}:${ss}`;        // ex: 1:05:23
        } else {
            return `${m}:${ss}`;              // ex: 9:08  ou 59:59
        }
    }

    closeBtn.addEventListener('click', () => {
        video.pause();
        document.getElementById('playerVideoWindow').classList.add('d-none');
        document.getElementById('listaArquivos').classList.remove('d-none');
        sairFullscreen();
    });

    playPauseBtn.addEventListener('click', () => {
        abrirFullscreen
        if (video.paused) {
            video.play();
            playPauseBtn.innerHTML = pauseIcon;
        } else {
            video.pause();
            playPauseBtn.innerHTML = playIcon;
        }
    });

    volumeSlider.addEventListener('input', () => {
        video.volume = volumeSlider.value;
    });

    

    // Salvar posição
    video.addEventListener('timeupdate', () => {

       let video_id = document.getElementById('playerVideoElement').dataset.video_id;
        let saved = localStorage.getItem(video_id);
        if(video.currentTime>saved ){
            localStorage.setItem( video_id, video.currentTime);
        }
        if(!saved){
            localStorage.setItem( video_id, 0);
        }



    });

    // Carregar posição salva
    video.addEventListener('loadedmetadata', () => {
        let video_id = document.getElementById('playerVideoElement').dataset.video_id;
        let saved = localStorage.getItem(video_id);
        console.log(saved)
        if (saved) video.currentTime = parseFloat(saved);
        video.play();
        playPauseBtn.innerHTML = pauseIcon;

    });


    // Mostrar controles ao mover mouse
    let timeout;
    document.addEventListener('mousemove', () => {
        document.querySelector('.controls').style.opacity = '1';
        document.querySelector('.nav-video').style.opacity = '1';
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            if (!video.paused) document.querySelector('.controls').style.opacity = '0';
            if (!video.paused) document.querySelector('.nav-video').style.opacity = '0';
        }, 3500);
    });
    // Elementos da barra
    const progressFilled = document.getElementById('progressFilled');
    const progressThumb = document.getElementById('progressThumb');

    const durationClock = document.getElementById('durationClock');

    // Atualiza a barra de progresso
    function atualizarBarra() {
        if (video.duration) {
            const percent = (video.currentTime / video.duration) * 100;
            progressFilled.style.width = percent + '%';
            progressThumb.style.left = percent + '%';

            document.getElementById('timeClock').textContent = formatarSegundos(video.currentTime);
            durationClock.textContent = formatarSegundos(video.duration);

        }
    }




    // Atualizar a cada frame
    video.addEventListener('timeupdate', atualizarBarra);

    // Quando carregar os metadados (duração)
    video.addEventListener('loadedmetadata', () => {
        durationClock.textContent = formatarSegundos(video.duration);
        atualizarBarra();
    });

    // Atualizar também quando mudar manualmente
    video.addEventListener('seeking', atualizarBarra);
</script>
