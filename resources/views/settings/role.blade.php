
@extends('layouts.app')

@section('content')
    <div class="card custom">
        <div class="card-header">
            <h4>User Role</h4>
        </div>
        <div class="card-body">
            @if($roles) @foreach($roles as $key=>$role)
            <div class="card">
                <div class="card-header">
                    <h5>{{$key+1}}. {{$role->name}}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($role->permission) @foreach($role->permission as $permision)
                        <div class="col-md-3 text-capitalize border">{{str_replace('_', ' ', $permision->name)}}</div>
                        @endforeach @endif
                    </div>
                </div>
            </div>
            @endforeach @endif
        </div>
    </div>
@endsection