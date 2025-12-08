@extends('default.layout')

@section('title', 'Stream')

@section('template')
    <div class="container mt-4">


        <div class="row">
            <div class="col-12 text-center ">

                @include('collections.parcial.menu')

                @include('collections.parcial.canais')


                <div id="playerVideoWindow" class="d-none col-12">
                    @include('players.tv-player')
                </div>


            @endsection
            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        const imagens = document.querySelectorAll(".thumbnail-icon");
                        const imagemPadrao = "{{ asset('storage/icons/tv.jpg') }}";

                        imagens.forEach(img => {
                            img.addEventListener("error", () => {
                                img.src = imagemPadrao;
                            });
                        });
                    });
                </script>
                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        const imagens = document.querySelectorAll(".thumbnail-icon");
                        const imagemPadrao = "{{ asset('storage/icons/tv.jpg') }}";

                        imagens.forEach(img => {
                            img.addEventListener("error", () => {
                                img.src = imagemPadrao;
                            });
                        });
                    });
                </script>
                <script>
                    function exibirInputSearch() {
                        document.getElementById('search').classList.remove('d-none')
                    }

                    function esconderInputSearch() {
                        document.getElementById('search').classList.add('d-none')
                    }



                    document.getElementById('search').addEventListener('input', function(e) {
                        document.querySelectorAll('.card-item').forEach((card, index) => {
                            let search = e.target.value;
                            if (!search) {
                                card.classList.remove('d-none');
                            }
                            if (!card.innerHTML.toLowerCase().includes(search.toLowerCase())) {
                                card.classList.add('d-none');
                            }

                        });

                    })

                    document.addEventListener("DOMContentLoaded", function() {
                        try {
                            document.querySelectorAll('.card-item').forEach((card, index) => {
                                let video = 'status-' + card.querySelector('.open-file').dataset.id;
                                let status = localStorage.getItem(video)
                                if (!status) {
                                    localStorage.setItem(video, 'novo');
                                }
                                if (status === 'assistido') {

                                    card.querySelector('.open-file').querySelector('div').innerHTML = card
                                        .querySelector('.open-file').querySelector('div').innerHTML +
                                        '<span class="badge text-bg-success">Assitido</span>';
                                }


                            });
                        } catch (e) {

                        }

                        // Ao clicar em arquivo, abre modal e injeta a url no iframe
                        document.querySelectorAll(".open-file").forEach(el => {
                            el.addEventListener("click", function(e) {
                                e.preventDefault();

                                const url = this.dataset.url;
                                document.getElementById('display-titulo').innerHTML = this.dataset.name;
                                document.getElementById('playerVideoElement').dataset.video_id = this.dataset
                                    .id;

                                const playerVideoElement = document.getElementById("playerVideoElement");



                                const src = url

                                if (Hls.isSupported()) {
                                    const hls = new Hls();
                                    hls.loadSource(src);
                                    hls.attachMedia(playerVideoElement);
                                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                                    // Suporte nativo (Safari)
                                    playerVideoElement.src = src;
                                }

                                document.getElementById('playerVideoWindow').classList.remove('d-none')

                                document.getElementById('listaArquivos').classList.add('d-none');

                            });


                        });


                    });
                </script>
            @endpush
            @push('css')
                <style>
                    .card-zoom {
                        transition: transform 0.25s ease, box-shadow 0.25s ease;
                    }

                    .card-zoom:hover,
                    .card-zoom:focus-within {
                        transform: scale(1.04);
                        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.15);
                        cursor: pointer;
                    }

                    .bg-dark {
                        background-color: #2d3748 !important;
                    }

                    .decoration-none {
                        text-decoration: none;
                    }

                    .text-black {
                        color: #000000 !important;
                    }
                </style>
            @endpush
