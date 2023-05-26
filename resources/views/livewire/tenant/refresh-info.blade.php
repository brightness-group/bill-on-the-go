<div>
    <style>
        .refresh-info {
            left: 0;
            top: 0;
            position: fixed;
            z-index: 1032;
            width: 100%;
            background: #ffffff;
            text-align: center;
            border: 2px solid #475F7B;
            margin-top: 0.5px;
            padding-top: 8px;
        }
    </style>
    <div class="refresh-info d-none">
        <h5>
            {{__('locale.Please reload the page or press F5!')}}
        </h5>
    </div>
    <script>
        var interval;
        interval = setInterval(function () {
            $.ajax({
                url: "{{route('check.session.expired')}}",
                type: 'GET',
                success: function () {
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    stopInterval();
                }
            });
        }, 30000);

        function stopInterval() {
            clearInterval(interval);
            $('.refresh-info').removeClass('d-none');
            return false;
        }
    </script>
</div>
