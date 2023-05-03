@extends('layouts.app')

@section('content')
    <div class="card custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>All Offices</h5>
            <a href="{{route('admin.settings.office.add')}}" class="btn btn-success"><i class="fa fa-plus"></i></a>
        </div>
        <div class="card-body">
        	<div class="table-responsive">
        		<table class="table table-bordered custom">
        			<tr>
        				<th width="20">SL</th>
        				<th>Name EN</th>
                        <th>Name BN</th>
        			</tr>
                    @if(isset($offices)) @foreach($offices as $key=>$office)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$office->name_en}}</td>
                        <td>{{$office->name_bn}}</td>
                    </tr>
                    @endforeach @endif
        		</table>
        	</div>
            {!! $offices->links() !!}
        </div>
    </div>
@endsection