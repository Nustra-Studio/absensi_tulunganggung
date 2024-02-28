<tbody id="tb-category">
    @php
                use Carbon\Carbon;

    @endphp
    @foreach ($absen as $item)
    @php
        $start_time = Carbon::createFromFormat('H:i', $item->absen_masuk);
        $end_time = $sm ? Carbon::createFromFormat('H:i', $sm) : null;
        $minutes_difference = $end_time ? $end_time->diffInMinutes($start_time) : null;
        $telat = $minutes_difference * $gpm;
        $finalValue = $gaji->jumlah - $telat;
        $finalValue = ($finalValue == $gaji->jumlah) ? 0 : $finalValue;
    @endphp
    <tr>
        <td>{{ $loop->index + 1 }}</td>
        <td>{{ "Rp " . number_format($gaji->jumlah, 0, ',', '.') }}</td>
        <td>{{ "Rp " . number_format($minutes_difference * $gpm, 0, ',', '.') }} / {{$minutes_difference}}</td>
        <td>{{ $item->absen_masuk }}</td>
        <td>{{ $item->absen_pulang ?? 0 }}</td>
        <td>{{$item->keterangan}}</td>
    </tr>
@endforeach

 </tbody>
