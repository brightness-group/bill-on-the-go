<div>
    @if($isVisible)
        <div style="display: flex; justify-content: center;align-items: center;background-color: black;position: fixed;top: 0;left: 0;z-index: 9999;
                    width: 100%;height: 100%;opacity: 0.75;">
            <div class="row">
                <div class="col">
                    <div class="la-ball-spin-clockwise la-3x" style="margin: auto;">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <div class="w-100"></div>
                <div class="col mt-3" style="text-align: center;">
                    <p><strong>{{ $message }}</strong></p>
                </div>
            </div>
        </div>
    @endif
</div>
