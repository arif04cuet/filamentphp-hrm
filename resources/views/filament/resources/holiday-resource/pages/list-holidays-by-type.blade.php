<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <script type="text/javascript">
        // print
        $(document).ready(function() {
            $(".printbtn").click(function() {
                $('svg').hide();
                $('.printbtn').hide();
                $('app-switcher').hide();
                $('form').hide();
                window.print();
            });
        });

        // year dropdown
        window.onload = function() {
            //Reference the DropDownList.
            var ddlYears = document.getElementById("ddlYears");

            //Determine the Current Year.
            var currentYear = (new Date()).getFullYear();

            //Loop and add the Year values to DropDownList.
            for (var i = currentYear - 5; i <= currentYear + 5; i++) {
                var option = document.createElement("OPTION");
                option.innerHTML = i;
                option.value = i;
                ddlYears.appendChild(option);
            }
        };

        // year select
        $(document).ready(function() {
            $("#ddlYears").change(function(e) {
                var selected = this.value;
                $('#year_textbox').val(selected);
                var param = $('#year_textbox').val();
            });
        });
    </script>
</head>

<style>
    table {
        counter-reset: rowNumber;
    }

    table tr>td:first-child {
        counter-increment: rowNumber;
    }

    table tr td:first-child::before {
        content: counter(rowNumber) ".";
        min-width: 1em;
        margin-right: 0.5em;
    }

    table tr td:last-child::before {
        content: "";
        min-width: 1em;
        margin-right: 0.5em;
    }
</style>

<body>
    <div class="row">
        <div class="col-md-12 bg-light d-flex justify-between">
            <div>
                <form>
                    <select class="form-select" id='ddlYears' name="syear">
                        <option selected="true" disabled="disabled">Year</option>
                    </select>
                    <button type="submit" value="Confirm" class="btn btn-warning">View</button>
                </form>
            </div>
            <div>
                <button type="button" class="printbtn btn btn-warning">Print</button>
            </div>
        </div>
    </div><br>

    <?php $currentYear = date("Y");
    if (isset($_GET['syear'])) { ?>
        <h5 style="text-align: center;">Holiday Calendar of Year {{$_GET['syear']}}</h5>
    <?php } else { ?>
        <h5 style="text-align: center;">Holiday Calendar of Year {{$currentYear}}</h5>
    <?php } ?><br>

    <?php $name = App\Models\Holiday::type(); ?>
    @foreach($name as $nn)
    <?php
    $year = Request::input('syear') ? Request::input('syear') : $currentYear;
    $getDataByTypeAndYear = App\Models\Holiday::getDataByTypeAndYear($nn, $year);
    $friCount = 0;
    $satCount = 0;
    ?>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col" colspan="4" style="text-align: center;">{{$nn}}
                </th>
            </tr>
            <tr class="thead-light">
                <th>Name</th>
                <th>Day & Date</th>
                <th>Bangla Date</th>
                <th>Days</th>
            </tr>
        </thead>
        <tbody>
            @foreach($getDataByTypeAndYear as $item)
            <tr>
                <td scope="row"><strong>{{App\Models\Holiday::getFlagById($item->id)}}{{$item->holidayName->title}}</strong>
                </td>
                <td>{{$item->from}}</td>
                <td>{{App\Models\Holiday::dateBn($item->from)}}</td>
                <td>{{$item->count}}</td>
            </tr>
            <?php
            $fri = 'Friday';
            $sat = 'Saturday';
            if (strpos($item->from, $fri) !== false) {
                $friCount++;
            }
            if (strpos($item->from, $sat) !== false) {
                $satCount++;
            }
            ?>
            @endforeach
            <tr>
                <td colspan="4" style="text-align: right;">
                    <strong>
                        <?php
                        $doptorSetting = App\Models\DoptorSetting::where('doptor_id', auth()->user()->employee->doptor_id)->first();
                        if ($doptorSetting) {
                            echo "Weekly holiday on ";
                            foreach ($doptorSetting->weekend as $day) {
                                echo $day . " ";
                            }
                            echo ".";
                        }
                        ?>
                        Total {{count($getDataByTypeAndYear)}} {{$nn}}
                    </strong>
                    {{-- Including <?php echo $friCount ?> Friday &
                        <?php echo $satCount ?> Saturday, Total
                        {{count($getDataByTypeAndYear)}} Holidays --}}
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
</body>

</html>