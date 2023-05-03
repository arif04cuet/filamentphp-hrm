@php
   $doptor = \Auth::user()->doptor ?? '';
@endphp

<div class="app-nav">
   <div class="app-nav position-absolute">
      <a class="app-company" href="{{route('admin.dashboard')}}" title="{{$doptor->name_bng ?? ''}}">
         <img src="{{asset('images/logo.png')}}" class="rounded-circle" />
         <span class="brand office-name">{{env('APP_NAME')}}</span>
      </a>
      <!-- // -->
      <div class="app-quick-actions">
         <div>
            <span class="aside-toggle"><i class="fa fa-bars" aria-hidden="true"></i></span>
         </div>
         <ul>
            <!-- // -->
            <li>
               <div class="dropdown" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                  <img src="{{asset('icons/user.webp')}}" alt="">
                  <div class="dropdown-menu dropdown-menu-right navbar-log-wrapper" aria-labelledby="navbarDropdown">
                     <a class="dropdown-item username"><i class="fa fa-user"></i> <span>{{Auth::user()->name}}</span></a>
                     <hr class="m-0 p-0">
                     <form method="post" name="logout">@csrf</form>
                  </div>
               </div>
            </li>
            <!-- // -->
            <li>
               @php $curr = App::getLocale(); if(Session::has('locale')) $curr = Session::get('locale'); $key = $curr == 'bn' ? 'en' : 'bn'; @endphp
               <a href="{{url('/lang/'.$key)}}" title="{{($key == 'en' ? 'English' : 'বাংলা')}}">
                  <img class="rounded-circle" src="{{($key == 'en' ? 'http://btm.rdcd.orangebd.com/images/en.webp' : 'http://btm.rdcd.orangebd.com/images/bn.webp')}}" alt="">
               </a>
            </li>
            <!-- // -->
            <li>
               <app-switcher />
            </li>
         </ul>
      </div>
   </div>
</div>