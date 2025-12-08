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
                             <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24">
                                 <path fill="#ffffff"
                                     d="M18 4h4v2h-4Zm-7 4v6a3 3 0 0 0 3 3h1a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3h-1a3 3 0 0 0-3 3Zm5 0v6a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1Zm5.6 0a1 1 0 0 0-.78 1.18a9 9 0 1 1-7-7a1 1 0 1 0 .4-2A10.8 10.8 0 0 0 12 1a11 11 0 1 0 11 11a10.8 10.8 0 0 0-.22-2.2A1 1 0 0 0 21.6 9ZM7 7v10a1 1 0 0 0 2 0V7a1 1 0 0 0-2 0Z" />
                             </svg>
                         </button>
                         <!-- PLAY / PAUSE -->
                         <button class="btnPlayer " id="play-pause">
                             <svg viewBox="0 0 24 24">
                                 <path d="M8 5v14l11-7z" />
                             </svg>
                         </button>
                         <!-- FORWARD +10s -->
                         <button class="btnPlayer " id="forward" title="+10 segundos">
                             <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 24 24">
                                 <path fill="#ffffff"
                                     d="M18 5h1v1a1 1 0 0 0 2 0V5h1a1 1 0 0 0 0-2h-1V2a1 1 0 0 0-2 0v1h-1a1 1 0 0 0 0 2Zm-7 4v6a3 3 0 0 0 3 3h1a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3h-1a3 3 0 0 0-3 3Zm5 0v6a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1Zm5.6 0a1 1 0 0 0-.78 1.18a9 9 0 1 1-7-7a1 1 0 1 0 .4-2A10.8 10.8 0 0 0 12 1a11 11 0 1 0 11 11a10.8 10.8 0 0 0-.22-2.2A1 1 0 0 0 21.6 9ZM7 7v10a1 1 0 0 0 2 0V7a1 1 0 0 0-2 0Z" />
                             </svg>
                         </button>
                     </div>
                     <div class="col-4 pt-4 px-5">
                         <div class="text-end">
                             <svg width="30" height="30" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 480 512">
                                 <path
                                     d="M215.03 71.05L126.06 160H24c-13.26 0-24 10.74-24 24v144c0 13.25 10.74 24 24 24h102.06l88.97 88.95c15.03 15.03 40.97 4.47 40.97-16.97V88.02c0-21.46-25.96-31.98-40.97-16.97zM480 256c0-63.53-32.06-121.94-85.77-156.24-11.19-7.14-26.03-3.82-33.12 7.46s-3.78 26.21 7.41 33.36C408.27 165.97 432 209.11 432 256s-23.73 90.03-63.48 115.42c-11.19 7.14-14.5 22.07-7.41 33.36 6.51 10.36 21.12 15.14 33.12 7.46C447.94 377.94 480 319.53 480 256zm-141.77-76.87c-11.58-6.33-26.19-2.16-32.61 9.45-6.39 11.61-2.16 26.2 9.45 32.61C327.98 228.28 336 241.63 336 256c0 14.38-8.02 27.72-20.92 34.81-11.61 6.41-15.84 21-9.45 32.61 6.43 11.66 21.05 15.8 32.61 9.45 28.23-15.55 45.77-45 45.77-76.88s-17.54-61.32-45.78-76.86z"
                                     fill="#ffffff" />
                             </svg>
                             <input type="range" id="volume" min="0" max="1" step="0.01"
                                 value="1">

                         </div>
                     </div>
                 </div>

                 <!-- VOLUME -->


             </div>
         </div>








     </div>
 </div>
 </div>
