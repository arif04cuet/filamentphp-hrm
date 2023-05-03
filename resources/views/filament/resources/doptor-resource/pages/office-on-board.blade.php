<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"
        integrity="sha512-qzrZqY/kMVCEYeu/gCm8U2800Wz++LTGK4pitW/iswpCbjwxhsmUwleL1YXaHImptCHG0vJwU7Ly7ROw3ZQoww=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{config('services.dashboard.url')}}/components/app-switcher/app-switcher.js" type="text/javascript">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script>
    $(document).ready(function() {
        $("#office_layers").change(function(e) {
            var url = `{{route('admin.settings.office.layered_office')}}`;
            var office_id = this.value;
            var office_se = $('#offices');
            office_se.empty();
            office_se.append((new Option('অফিস নির্বাচন করুন', '', true, true))).trigger('change');
            // 
            axios.get(url + '/' + office_id).then((res) => {
                console.log(res.data);
                if (res.data) Object.values(res.data).forEach((row) => {
                    office_se.append((new Option(row.name, row.id, false, false)))
                        .trigger(
                            'change');
                });
            });
        });
    });
    </script>
</head>

<style>
</style>

<body>
    <?php
    $office_layers = App\Http\Controllers\OfficeController::create();
    ?>
    <div class="card custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Office On-Board</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('admin.settings.office.store')}}">
                @csrf
                <div class="row form-group">
                    <label class="col-md-2 text-right">Parent Office <span class="text-danger">*</span></label>
                    <div class="col-md-9 form-group">
                        <select name="parent_office_id" id="office_layers" class="select2 form-control">
                            <option value="">প্যারেন্ট অফিস নির্বাচন করুন</option>
                            @if(isset($office_layers)) @foreach($office_layers as $id=>$name)
                            <option value="{{$id}}">{{$name}}</option>
                            @endforeach @endif
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-2 text-right">Office <span class="text-danger">*</span></label>
                    <div class="col-md-9 form-group">
                        <select name="office_id" class="select2 form-control" id="offices"></select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-11 text-right">
                        <button class="btn btn-success">Add</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</body>

</html>