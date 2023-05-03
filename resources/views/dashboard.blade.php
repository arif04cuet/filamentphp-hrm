
@extends('layouts.app')

@section('content')
    <div class="card border-0">
        <div class="card-header">
            <h2>Dashboard</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <select class="select2 form-control" name="state">
                      <option value="AL">Alabama</option>
                      <option value="WY">Wyoming</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

