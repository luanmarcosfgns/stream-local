         @foreach ($homes as $home)
             @if ($home['is_dir'])
                 {{-- PASTA: abre listagem --}}
                 <a class="btn {{ $path === $home['name'] ? 'btn-light text-black' : 'btn-invisivel' }}  text-white text-decoration-none text m-2 rounded-pill"
                     href="{{ $home['url'] }}">
                     {{ $home['name'] }}
                 </a>
             @endif
         @endforeach
         <a onclick="exibirInputSearch()" class="btn btn-invisivel  text-white text-decoration-none text m-2 rounded-pill"
             href="#!">
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
         <div class="row">

             <div class="col-12">
                 <input id="search" onfocusout="esconderInputSearch()"
                     class="form-control bg-dark text-white d-none">
             </div>
         </div>
