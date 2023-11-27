@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        <iframe src="https://grafana.buq.partners/api/?brands_id={{$brand->id}}&metrica={{$graph}}&token={{$graphToken}}" width="100%"
                height="900px" style="border: 0"></iframe>
    </div>
@endsection
