<div class="row" id="listaArquivos">
                    @foreach ($canais as $canal)
                        <div data-name-search="{{ $canal['name'] }}" class="col-12 col-md-4 my-2  card-zoom card-item">






                            {{-- ARQUIVO: abre modal via iframe (não carrega antes) --}}
                            <a href="#" class="open-file" data-url="{{ $canal['url'] }}"
                                data-name="{{ $canal['name'] }}" data-id="{{ $canal['id'] }}"
                                style="text-decoration: none; color: #333;">
                                <div
                                    style="border:1px solid #363639; padding:10px; border-radius:8px; background:#232329; color: white">
                                    <img class="thumbnail-icon" src="{{ $canal['thumbnail'] }}" alt="{{ $canal['name'] }}"
                                        style="width:100%; height:190px; object-fit:cover; border-radius:5px;">
                                    <div class="mt-2 text-truncate text-md" title="{{ $canal['name'] }}">
                                        {{ substr($canal['name'], 0, -4) }}</div>

                                </div>
                            </a>




                        </div>
                    @endforeach
                </div>