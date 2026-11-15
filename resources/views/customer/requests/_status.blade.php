@if ($status === 'available')
    <span class="badge bg-success">Tersedia</span>
@elseif ($status === 'unavailable')
    <span class="badge bg-danger">Tidak tersedia</span>
@else
    <span class="badge bg-warning text-dark">Menunggu respons</span>
@endif
