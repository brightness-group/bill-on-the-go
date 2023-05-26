{{-- Footer --}}
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ (!empty($containerNav) ? $containerNav : 'container-fluid') }} d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
    <div class="mb-2 mb-md-0">
      © <script>
        document.write(new Date().getFullYear())

      </script>

      @if (config('app.app_edition') == 'billonthego')
        - Teambilling, made with ❤️ by <a href="#" target="_blank" class="footer-link fw-semibold">TBL</a>
      @endif
    </div>
    {{--<div>
      <a href="#" class="footer-link me-4">Item 1</a>
      <a href="#" class="footer-link me-4">Item 2</a>
      <a href="#" class="footer-link me-4">Item 3</a>
      <a href="#" class="footer-link d-none d-sm-inline-block">Item 4</a>
    </div>--}}
  </div>
</footer>
{{--/ Footer --}}
