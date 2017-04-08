@extends('email.layout')

@section('title')
    You have received a new payment.
@endsection

@section('message')
    <h4>Hi {{ $receiver }},</h4>
    <p>You've received a amount of {{ $amount }} from {{ $sender }}. Token#: {{ $token }} </p>
    @if(strlen($text) > 0)
        <h4>Message: </h4>
        <p>{{ $text }}</p>
    @endif
    <p class="text-center">
        <a class="btn" href="">Click here to claim.</a>
    </p>
@endsection