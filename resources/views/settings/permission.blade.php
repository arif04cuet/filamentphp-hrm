
@extends('layouts.app')

@section('content')
    <div class="card custom">
        <div class="card-header">
            <h4>User Role</h4>
        </div>
        <div class="card-body">
        	<div class="table-responsive">
        		<table class="table table-bordered custom">
        			<tr>
        				<th width="20">SL</th>
        				<th>Name</th>
        			</tr>
		            @if($permissions) @foreach($permissions as $key=>$permission)
		            <tr>
		            	<td>{{$key+1}}</td>
		            	<td class="text-capitalize">{{str_replace('_', ' ', $permission->name)}}</td>
		            </tr>
		            @endforeach @endif
        		</table>
        	</div>
        	{!! $permissions->links() !!}
        </div>
    </div>
@endsection