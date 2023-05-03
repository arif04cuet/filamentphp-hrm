@extends('layouts.app')

@section('content')
    <div class="card custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Office On-Board</h5>
            <a href="{{route('admin.settings.office.list')}}" class="btn btn-success custom"><i class="fa fa-list"></i></a>
        </div>
        <div class="card-body">
        	<form method="POST" action="{{route('admin.settings.office.store')}}">
        		@csrf
	        	<div class="row form-group">
	        		<label class="col-md-3 text-right">Parent Office <span class="text-danger">*</span></label>
	        		<div class="col-md-4 form-group">
	        			<select name="parent_office_id" id="office_layers"  class="select2 form-control">
	        				<option value="">প্যারেন্ট অফিস নির্বাচন করুন</option>
	        				@if(isset($office_layers)) @foreach($office_layers as $id=>$name)
	        				<option value="{{$id}}">{{$name}}</option>
	        				@endforeach @endif
	        			</select>
	        		</div>
	        	</div>


	        	<div class="row form-group">
	        		<label class="col-md-3 text-right">Office <span class="text-danger">*</span></label>
	        		<div class="col-md-4 form-group">
	        			<select name="office_id" class="select2 form-control" id="offices"></select>
	        		</div>
	        	</div>

	        	<div class="row">
	        		<div class="col-md-7 text-right">
	        			<button class="btn btn-success">Add</button>
	        		</div>
	        	</div>
	        	
	        </form>
        </div>
    </div>
@endsection

@push('script')
<script>

	$('#office_layers').on("select2:select", function (e) 
	{ 
		var url = `{{route('admin.settings.office.layered_office')}}`;
		var office_id = e.params.data.id;
		var office_se = $('#offices'); office_se.empty();
		office_se.append((new Option('অফিস নির্বাচন করুন', '', true, true))).trigger('change');
		// 
		axios.get(url+'/'+office_id).then((res)=>{
			console.log(res.data);
			if(res.data) Object.values(res.data).forEach((row)=>{
				office_se.append((new Option(row.name, row.id, false, false))).trigger('change');
			});
		});
	});




</script>
@endpush