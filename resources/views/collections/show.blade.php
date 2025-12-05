@extends('default.layout')

@section('title', 'Stream')

@section('content')
    <div class="container mt-4">


        <div class="row">
            <div class="col-12 text-center ">

                @foreach($homes as $home)

                    @if($home['is_dir'] )
                        {{-- PASTA: abre listagem --}}
                        <a class="btn {{$path===$home['name']?'btn-light text-black':'btn-invisivel'}}  text-white text-decoration-none text m-2 rounded-pill"
                           href="{{ $home['url'] }}">
                            {{$home['name'] }}
                        </a>
                    @endif

                @endforeach
                <a onclick="exibirInputSearch()"
                   class="btn btn-invisivel  text-white text-decoration-none text m-2 rounded-pill" href="#!">
                    <svg height="20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>
                </a>
            </div>
        </div>
        <div id="listaArquivos" class="col-12 text-center ">

            <div class="row">

                <div class="col-12">
                    <input id="search" onfocusout="esconderInputSearch()"
                           class="form-control bg-dark text-white d-none">
                </div>
            </div>
            <div class="row">

                @foreach($items as $item)
                    <div data-name-search="{{$item['name']}}" class="col-12 col-md-4 my-2  card-zoom card-item">


                        @if($item['is_dir'] && $path)
                            <a class="decoration-none" href="{{ $item['url'] }}">
                                <div class="card bg-dark">
                                    <div class="card-header">
                                        {{substr($item['name'],0,29 )}}
                                    </div>
                                    <div
                                        class="card-body">
                                        <img src="{{ $item['thumbnail'] }}"
                                             alt="{{ $item['name'] }}"
                                             @if(!stripos($item['thumbnail'],'folder.png'))
                                                 style="width:100%; object-fit:cover; border-radius:5px;">
                                        @else
                                            style="width:171px;">
                                        @endif


                                    </div>
                                </div>


                            </a>
                        @endif

                        @if(!$item['is_dir'])

                            {{-- ARQUIVO: abre modal via iframe (não carrega antes) --}}
                            <a href="#"
                               class="open-file"
                               data-url="{{ $item['url'] }}"
                               data-name="{{ $item['name'] }}"
                               data-id="{{ $item['id'] }}"
                               style="text-decoration: none; color: #333;">
                                <div
                                    style="border:1px solid #363639; padding:10px; border-radius:8px; background:#232329; color: white">
                                    <img src="{{ $item['thumbnail'] }}"
                                         alt="{{ $item['name'] }}"
                                         style="width:100%; height:190px; object-fit:cover; border-radius:5px;">
                                    <div class="mt-2 text-truncate text-md"
                                         title="{{ $item['name'] }}">{{substr($item['name'],0,-4)}}</div>

                                </div>
                            </a>

                        @endif


                    </div>
                @endforeach

            </div>


        </div>
        <div id="playerVideoWindow" class="d-none col-12">
            @include('componentes.player')
        </div>
    </div>


    </div>
    <input type="hidden" id="input-playlist" value="{{json_encode($items) }}">
    <p id="delarar" class="text-center">Eu te amo ❤ Obrigado por ser a melhor mãe e exposa do mundo️</p>
@endsection

@push('scripts')
    <script>
        if (localStorage.getItem('delarar') !== null) {
            document.getElementById('delarar').classList.add('d-none');
        }
        setTimeout(() => {
            localStorage.setItem('delarar', 'ocultar')
            document.getElementById('delarar').classList.add('d-none');
        }, 10000)


        function exibirInputSearch() {
            document.getElementById('search').classList.remove('d-none')
        }

        function esconderInputSearch() {
            document.getElementById('search').classList.add('d-none')
        }

        this.playList = document.getElementById('input-playlist').value

        document.getElementById('search').addEventListener('input', function (e) {
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

        document.addEventListener("DOMContentLoaded", function () {
            try {
                document.querySelectorAll('.card-item').forEach((card, index) => {
                    let video = 'status-' + card.querySelector('.open-file').dataset.id;
                    let status = localStorage.getItem(video)
                    if (!status) {
                        localStorage.setItem(video, 'novo');
                    }
                    if (status === 'assistido') {

                        card.querySelector('.open-file').querySelector('div').innerHTML = card.querySelector('.open-file').querySelector('div').innerHTML + '<span class="badge text-bg-success">Assitido</span>';
                    }


                });
            }catch (e) {
                
            }

            // Ao clicar em arquivo, abre modal e injeta a url no iframe
            document.querySelectorAll(".open-file").forEach(el => {
                el.addEventListener("click", function (e) {
                    e.preventDefault();

                    const url = this.dataset.url;
                    document.getElementById('display-titulo').innerHTML = this.dataset.name;
                    document.getElementById('playerVideoElement').dataset.video_id = this.dataset.id;

                    const playerVideoElement = document.getElementById("playerVideoElement");


                    playerVideoElement.src = url;

                    document.getElementById('playerVideoWindow').classList.remove('d-none')

                    document.getElementById('listaArquivos').classList.add('d-none');

                });


            });


        });
    </script>

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
