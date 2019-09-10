@extends('errors.minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message')
<h3>Something went wrong from our side, please try again later!</h3>
@endsection
