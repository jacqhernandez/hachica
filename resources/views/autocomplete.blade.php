

@extends('layouts.app')


@section('content')


<div class="panel-heading">
	Autocomplete Test
</div>
<div class="panel-body">
  <div class="form-group">                
    {!! Form::text('search_text', null, array('placeholder' => 'Search Text','class' => 'form-control','id'=>'search_text')) !!}
  </div>
</div>




@endsection

@section('script')

<script src="{{ asset('js/typeahead.js') }}"></script> 

<script type="text/javascript">
  var url = "{{ route('autocomplete.ajax') }}";
  $('#search_text').typeahead({
      source:  function (query, process) {
      return $.get(url, { query: query }, function (data) {
              return process(data);
          });
      }
  });
</script>

@endsection