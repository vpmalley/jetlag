@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">

      @if ($descriptionMediaUrl)
			<div class="panel panel-default">
				<div class="panel-heading">{{$title}}</div>
        <div class="panel-body media">
          <img src={{$descriptionMediaUrl}} width="200px" />
        </div>
      </div>
      @endif
          
			<div class="panel panel-default">
				<div class="panel-heading">{{$title}}</div>

				<div class="panel-body">

					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

          <div class="print">
              <label class="col-md-4 control-label">Description</label>
              <div class="col-md-6">
                <label class="col-md-4 control-label">{{$descriptionText}}</label>
              </div>

          </div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
