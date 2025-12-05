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
    <div class="col-12">
                <div class="controls">
                    <div class="row w-100">
                        <div class="col-12">
                            <!-- BARRA DE PROGRESSO -->
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-filled" id="progressFilled"></div>
                                    <div class="progress-thumb" id="progressThumb"></div>
                                </div>
                                <div class="progress-tooltip" id="progressTooltip">00:00</div>

                            </div>


                        </div>

                        <div class="col-12 ">
                            <div class="row">
                                <div class="col-4 pt-4 px-5
                                ">
                                    <!-- Tempo atual / duração total -->
                                    <span id="timeClock">00:00</span> / <span id="durationClock">00:00</span>
                                </div>
                                <div class="col-4 text-center"><!-- REWIND -10s -->
                                    <button class="btnPlayer " id="rewind" title="-10 segundos">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24"><path fill="#ffffff" d="M18 4h4v2h-4Zm-7 4v6a3 3 0 0 0 3 3h1a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3h-1a3 3 0 0 0-3 3Zm5 0v6a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1Zm5.6 0a1 1 0 0 0-.78 1.18a9 9 0 1 1-7-7a1 1 0 1 0 .4-2A10.8 10.8 0 0 0 12 1a11 11 0 1 0 11 11a10.8 10.8 0 0 0-.22-2.2A1 1 0 0 0 21.6 9ZM7 7v10a1 1 0 0 0 2 0V7a1 1 0 0 0-2 0Z"/></svg>
                                    </button>
                                    <!-- PLAY / PAUSE -->
                                    <button class="btnPlayer " id="play-pause">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </button>
                                    <!-- FORWARD +10s -->
                                    <button class="btnPlayer " id="forward" title="+10 segundos">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 24 24"><path fill="#ffffff" d="M18 5h1v1a1 1 0 0 0 2 0V5h1a1 1 0 0 0 0-2h-1V2a1 1 0 0 0-2 0v1h-1a1 1 0 0 0 0 2Zm-7 4v6a3 3 0 0 0 3 3h1a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3h-1a3 3 0 0 0-3 3Zm5 0v6a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1Zm5.6 0a1 1 0 0 0-.78 1.18a9 9 0 1 1-7-7a1 1 0 1 0 .4-2A10.8 10.8 0 0 0 12 1a11 11 0 1 0 11 11a10.8 10.8 0 0 0-.22-2.2A1 1 0 0 0 21.6 9ZM7 7v10a1 1 0 0 0 2 0V7a1 1 0 0 0-2 0Z"/></svg>
                                    </button>
                                </div>
                                <div class="col-4 pt-4 px-5">
                                    <div class="text-end">
                                        <svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 480 512"><path d="M215.03 71.05L126.06 160H24c-13.26 0-24 10.74-24 24v144c0 13.25 10.74 24 24 24h102.06l88.97 88.95c15.03 15.03 40.97 4.47 40.97-16.97V88.02c0-21.46-25.96-31.98-40.97-16.97zM480 256c0-63.53-32.06-121.94-85.77-156.24-11.19-7.14-26.03-3.82-33.12 7.46s-3.78 26.21 7.41 33.36C408.27 165.97 432 209.11 432 256s-23.73 90.03-63.48 115.42c-11.19 7.14-14.5 22.07-7.41 33.36 6.51 10.36 21.12 15.14 33.12 7.46C447.94 377.94 480 319.53 480 256zm-141.77-76.87c-11.58-6.33-26.19-2.16-32.61 9.45-6.39 11.61-2.16 26.2 9.45 32.61C327.98 228.28 336 241.63 336 256c0 14.38-8.02 27.72-20.92 34.81-11.61 6.41-15.84 21-9.45 32.61 6.43 11.66 21.05 15.8 32.61 9.45 28.23-15.55 45.77-45 45.77-76.88s-17.54-61.32-45.78-76.86z" fill="#ffffff"/></svg>
                                        <input type="range" id="volume" min="0" max="1" step="0.01" value="1">

                                    </div>
                                </div>
                            </div>

                            <!-- VOLUME -->


                        </div>
                    </div>








                </div>
            </div>
    </div>

<script>
    const video = document.getElementById('playerVideoElement');
    const playPauseBtn = document.getElementById('play-pause');
    const forwardBtn = document.getElementById('forward');
    const rewindBtn = document.getElementById('rewind');
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

    forwardBtn.addEventListener('click', () => video.currentTime += 10);
    rewindBtn.addEventListener('click', () => video.currentTime -= 10);

    volumeSlider.addEventListener('input', () => {
        video.volume = volumeSlider.value;
    });

    function setVideo(e) {
        let proximoVideo = document.getElementById('playerVideoElement');
        proximoVideo.src = e.url;
        proximoVideo.dataset.video_id = e.id;
        document.getElementById('display-titulo').innerHTML= e.name;
    }

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
        document.getElementById('timeClock').innerHTML = formatarSegundos(video.currentTime)

        if(video.duration==video.currentTime){
            localStorage.setItem( video_id, 0);
            let proximo = false;
            let listVideo = JSON.parse(this.playList);
            listVideo.forEach((e)=>{
                if(proximo){
                    proximo = false;
                   setVideo(e)

                    return;
                }
                if(e.id==video_id){
                    proximo = true;
                    console.log(e.name)
                }

            })
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
    const progressContainer = document.querySelector('.progress-container');
    const progressTooltip = document.getElementById('progressTooltip');
    const durationClock = document.getElementById('durationClock');

    // Atualiza a barra de progresso
    function atualizarBarra() {
        if (video.duration) {
            const percent = (video.currentTime / video.duration) * 100;
            progressFilled.style.width = percent + '%';
            progressThumb.style.left = percent + '%';

            document.getElementById('timeClock').textContent = formatarSegundos(video.currentTime);
            durationClock.textContent = formatarSegundos(video.duration);

            if(percent>95){
                console.log('assistido');
                localStorage.setItem('status-'+video.dataset.video_id,'assistido')
                localStorage.setItem( video.dataset.video_id,0);
            }
        }
    }

    // Clique na barra para pular
    progressContainer.addEventListener('click', (e) => {
        const rect = progressContainer.getBoundingClientRect();
        const pos = (e.clientX - rect.left) / rect.width;
        video.currentTime = pos * video.duration;
    });

    // Mostrar tooltip ao passar o mouse
    progressContainer.addEventListener('mousemove', (e) => {
        const rect = progressContainer.getBoundingClientRect();
        const pos = (e.clientX - rect.left) / rect.width;
        const tempo = pos * video.duration;

        progressTooltip.style.left = e.clientX - rect.left + 'px';
        progressTooltip.textContent = formatarSegundos(tempo);
    });

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
