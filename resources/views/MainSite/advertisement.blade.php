<link href="{{ asset('css/side_bar.css') }}" rel="stylesheet">
<div class="side-bar">
    @for($i =0;$i<6;$i++)
    <div class="side-bar-container">
    aside{{$i}}
    </div>
    @endfor
</div>

