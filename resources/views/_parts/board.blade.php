<div class="grid-container">
    @for($y=0; $y<=$dimension; $y++)
        @for($x=0; $x<=$dimension; $x++)
            @if($x == 0 && $y == 0)
                <div class="grid-item">Y/X</div>
            @else
                @if($x>0 && $y == 0)
                    <div class="grid-item">{{$x-1}}</div>
                @else
                    @if($x==0 && $y > 0)
                        <div class="grid-item">{{$y-1}}</div>
                    @else
                        <div class="grid-item board_cell"
                             id="{{$boardType}}_y{{$y-1}}_x{{$x-1}}"
                             data-type="{{$boardType}}"
                             data-x="{{$x-1}}"
                             data-y="{{$y-1}}">&#8291;</div>
                    @endif
                @endif
            @endif

        @endfor
    @endfor
</div>