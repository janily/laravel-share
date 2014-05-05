@extends('frontend_master')

@section('content')
	
	@if(count($images))
		<ul>
		@foreach($images as $each)
			<li>
				<a href="{{URL::to('snatch/'.$each->id)}}">{{HTML::image(Config::get('image.thumb_folder').'/'.$each->image)}}</a>
				<!-- <a href="{{URL::to('delete/'.$each->id)}}">delete</a> -->
			</li>
		@endforeach
		</ul> 
		
		<p>{{$images->links()}}</p>
	@else
		<p>No images uploaded yet, {{HTML::link('/','care to upload one?')}}</p>
	@endif
@stop