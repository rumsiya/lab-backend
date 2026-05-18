<!DOCTYPE html>
<html>
<head>
    <title>Laboratory report</title>
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $css = $manifest['resources/css/app.css']['file'];
    @endphp

    <link rel="stylesheet" href="{{ public_path('build/' . $css) }}">
</head>
<body >
    <div class="flex bg-blue-400 text-white p-6 text-2xl text-center">
        <h1>Star Laboratory</h1>

    </div>

    <table class="bg-green-100 mt-2 p-2" style="width:100%; border-collapse: collapse;" >
    <tr>
        <td style="width:50%;">
            <b>Booking No:</b> {{ $appointment->booking_id }}
        </td>
        <td style="width:50%;">
            <b>Patient Name:</b> {{ $appointment->getPatient->username }}
        </td>
    </tr>
    <tr>
        <td style="width:50%;">
            <b>Age/Gender:</b> {{ $appointment->getPatient->age }}/{{$appointment->getPatient->gender==0?'M':'F' }}
        </td>
        <td style="width:50%;">
            <b>Phone:</b> {{ $appointment->getPatient->phone }}
        </td>
    </tr>

</table>

<table class="table-auto border border-blue-300 w-full mt-6" >
    <tr class="bg-blue-200">
        <th>Test Name</th>
        <th>Value</th>
        <th>Unit</th>
        <th>Reference Value</th>
    </tr>
    <tr >
        <td class="text-center">{{$report->getTest->test_name}}</td>
        <td class="text-center">{{$report->min_result}}-{{$report->max_result}}</td>
        <td class="text-center">{{$report->getUnit->unit_name}}</td>
        <td class="text-center">{{$report->getTest->normal_min}}-{{$report->getTest->normal_max}}</td>
    </tr>

</table>
@if($report->description)
<p class="mt-2">Description : <b>{{$report->description}}</b></p>
@endif

</body>

</html>
