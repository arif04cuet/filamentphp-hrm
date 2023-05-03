
@extends('layouts.app')

@section('content')
    <div class="card custom">
        <div class="card-header">
            <h4>All User</h4>
        </div>
        <div class="card-body">
        	<div class="table-responsive">
        		<table class="table table-bordered custom">
        			<tr>
        				<th width="20">SL</th>
        				<th>Name</th>
        			</tr>
		            @if($users) @foreach($users as $key=>$user)
		            <tr>
		            	<td>{{$key+1}}</td>
		            	<td class="text-capitalize">{{str_replace('_', ' ', $user->name)}}</td>
		            </tr>
		            @endforeach @endif
        		</table>
        	</div>
        	{!! $users->links() !!}
        </div>
    </div>
@endsection