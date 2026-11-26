@if ($status === 'available')
    <span class="fg-pill ok">Tersedia</span>
@elseif ($status === 'unavailable')
    <span class="fg-pill out">Tidak tersedia</span>
@else
    <span class="fg-pill wait">Menunggu respons</span>
@endif
