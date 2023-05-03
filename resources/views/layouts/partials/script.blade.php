<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js" integrity="sha512-qzrZqY/kMVCEYeu/gCm8U2800Wz++LTGK4pitW/iswpCbjwxhsmUwleL1YXaHImptCHG0vJwU7Ly7ROw3ZQoww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{config('services.dashboard.url')}}/components/app-switcher/app-switcher.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{asset('js/perfect-scrollbar.js')}}"></script>
<script type="text/javascript" src="{{asset('js/app.js')}}"></script>



<script>
   (new AppSwitcher()).serve({
      dashboard_url : `{{config("services.dashboard.url")}}`,
      token         : `{{session()->has('token') ? session()->get('token') : ''}}`,
      onLogout:(response)=>{
         $('form[name=logout]').attr('action', `{{route('admin.logout')}}?redirect_url=`+response.redirect_url);
         $('form[name=logout]').submit();
      },
      onSwitch:(model)=>{
         $('form[name=logout]').attr('action', `{{route('admin.logout')}}?redirect_url=`+model.login_handler+'?token='+`{{session()->has('token') ? session()->get('token') : ''}}`);
         $('form[name=logout]').submit();
      },
   });

   $(document).ready(function() {
      $('.select2').select2();
   });


   toastr.options = {
     "closeButton": true,
     "newestOnTop": false,
     "progressBar": true,
     "positionClass": "toast-top-right",
     "preventDuplicates": false,
     "onclick": null,
     "showDuration": "300",
     "hideDuration": "1000",
     "timeOut": "5000",
     "extendedTimeOut": "1000",
     "showEasing": "swing",
     "hideEasing": "linear",
     "showMethod": "fadeIn",
     "hideMethod": "fadeOut"
   }

   @if(session()->has('msg')) {{session()->get('msg').session()->forget('msg')}}  @endif


</script>