@extends('email.layout')

@section('title')
    You have received a new payment.
@endsection

@section('message')
    <h4>Hi {{ $receiver }},</h4>
    <p>You've received a amount of {{ $amount }} from {{ $sender }}.</p>
    @if(strlen($text) > 0)
        <h4>Message: </h4>
        <p>{{ $text }}</p>
    @endif
    <p class="text-center">
        <a class="btn" href="{{ config('app.transfer_link') }}{{ $token }}">Click here to claim.</a>
    </p>
    <p class="text-center" style="font-size:.8em"><small>Your token is <code>{{ $token }}</code>.</small></p>
@endsection