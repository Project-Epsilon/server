@extends('mail.layout')

@section('title')
    You have a received a new payment!
@endsection

@section('content')
    <h4>
        Hi {{ $name }},
    </h4>
    <p>
        {{ $sender }} sent you {{ $amount }}. Token#: {{ $token }}
    </p>
    @if(strlen($message) > 0)
        <h4>
            Message:
        </h4>
        <p>{{ $message }}</p>
    @endif
    <div class="text-center" style="margin-top: 2.2rem">
        <a href="" class="btn btn-primary">Click here to deposit your payment.</a>
    </div>
    <div class="text-center text-muted" style="font-size: .8rem;margin-top: .3rem;">
        Service rendered on {{ \Carbon\Carbon::now()->toDateString() }}.
    </div>
@endsection