@extends('layouts.app')

@section('content')
    <div class="card custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>All Department</h5>
            <a href="{{route('admin.settings.office.add')}}" class="btn btn-success"><i class="fa fa-plus"></i></a>
        </div>
        <div class="card-body">
        	<div class="table-responsive">
        		<table class="table table-bordered custom">
        			<tr>
        				<th width="20">SL</th>
        				<th>Name EN</th>
                        <th>Name BN</th>
                        <th>Office</th>
        			</tr>
                    @if(isset($departments)) @foreach($departments as $key=>$department)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$department->name_en}}</td>
                        <td>{{$department->name_bn}}</td>
                        <td>{{$department?->doptor?->name_bn}}</td>
                    </tr>
                    @endforeach @endif
        		</table>
        	</div>
            {!! $departments->links() !!}
        </div>
    </div>
@endsection