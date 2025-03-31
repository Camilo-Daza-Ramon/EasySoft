@if(! $errors->isEmpty())
	<div class="alert alert-danger">
		<p><strong>Oops!</strong> Por favor corrija lo siguiente:</p>
		<ul>
			@foreach($errors->getMessages() as $key => $error)
				<li>{{$key}} - {{$error[0]}}</li>
			@endforeach
		</ul>
	</div>
@endif

