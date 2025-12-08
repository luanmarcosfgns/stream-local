  @foreach ($items as $item)
      <div data-name-search="{{ $item['name'] }}" class="col-12 col-md-4 my-2  card-zoom card-item">


          @if ($item['is_dir'] && $path)
              <a class="decoration-none" href="{{ $item['url'] }}">
                  <div class="card bg-dark">
                      <div class="card-header">
                          {{ substr($item['name'], 0, 29) }}
                      </div>
                      <div class="card-body">
                          <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}"
                              @if (!stripos($item['thumbnail'], 'folder.png')) style="width:100%; object-fit:cover; border-radius:5px;">
                                        @else
                                            style="width:171px;"> @endif
                              </div>
                      </div>


              </a>
          @endif

          @if (!$item['is_dir'])
              {{-- ARQUIVO: abre modal via iframe (não carrega antes) --}}
              <a href="#" class="open-file" data-url="{{ $item['url'] }}" data-name="{{ $item['name'] }}"
                  data-id="{{ $item['id'] }}" style="text-decoration: none; color: #333;">
                  <div
                      style="border:1px solid #363639; padding:10px; border-radius:8px; background:#232329; color: white">
                      <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}"
                          style="width:100%; height:190px; object-fit:cover; border-radius:5px;">
                      <div class="mt-2 text-truncate text-md" title="{{ $item['name'] }}">
                          {{ substr($item['name'], 0, -4) }}</div>

                  </div>
              </a>
          @endif


      </div>
  @endforeach
