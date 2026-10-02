@extends('layouts.default')

@section('header')
<h2>This is the Header!</h2>
@endsection

@section('maincontent')

<a href="{{ ('/student') }}">Back</a>

<h1>Portfolio</h1>
<form action="{{ route('formsubmitted') }}" method="post">
    @csrf
    <label for="fullname">Full name:</label>
    <input type="text" id="fullname" name="fullname" placeholder="Type your full name!" required>
    <br><br>
    <label for="email">E-mail:</label>
    <input type="text" id="email" name="email" placeholder="Type your e-mail!" required>
    <br><br>
    <button type="submit">Submit</button>
</form>
@endsection

@section('footer')
<h2>This is the Footer</h2>
@endsection

