<script>
  @if(Session::has('success'))
    toastr.options.positionClass = 'toast-bottom-right';
  	toastr.success("{{ Session::get('success') }}");
  @endif

  @if(Session::has('info'))
    toastr.options.positionClass = 'toast-bottom-right';
  	toastr.info("{{ Session::get('info') }}");
  @endif

  @if(Session::has('warning'))
    toastr.options.positionClass = 'toast-bottom-right';
  	toastr.warning("{{ Session::get('warning') }}");
  @endif

  @if(Session::has('error'))
    toastr.options.positionClass = 'toast-bottom-right';
  	toastr.error("{{ Session::get('error') }}");
  @endif
</script>