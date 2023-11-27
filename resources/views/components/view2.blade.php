
@extends('admin.layout.master')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/jquery.dataTables.min.css') }}">
    @endsection

@section('jsPostApp')
<script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#dashboard-permisionlist').DataTable({
                pagingType: "simple",
                pageLength: 4,
                language: {
            paginate: {'next': 'Next &rarr;', 'previous': '&larr; Prev'}
        }
            });
        } );
    </script>
@endsection

@section(content)

<h1>View 2</h1>
@endsection
