@extends('frontend_master')


@section('content')
	{{Form::open(array('url' => '/', 'files' => true))}}
	{{Form::text('title','',array('placeholder'=>'请输入标题'))}}
	{{Form::file('image')}}
	{{Form::submit('save!',array('name'=>'send'))}}
	{{Form::close()}}
@stop