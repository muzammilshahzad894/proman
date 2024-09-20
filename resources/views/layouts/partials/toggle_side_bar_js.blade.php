<script type="text/javascript">
  
  @if(config('site.hide_side_bar'))
  $('body').addClass('sidebar-collapse');
  @endif
  $(document).on('click', '.sidebartoggle-pin', function(event) {
    $.ajax({
    url: '{{route("admin.settings.store.site_toggle_sidebar_pin")}}',
    type: 'POST',
    data: {config: 
        {
          'hide_side_bar':`{{!config('site.hide_side_bar')}}`
        }
      },
  })
  .done(function() {
    console.log("success");
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
  });
            
</script>