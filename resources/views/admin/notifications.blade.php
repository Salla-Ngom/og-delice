@extends('layouts.admin')

@section('content')

<div class="space-y-4">

    <h1 class="text-2xl font-bold mb-6">
        Notifications
    </h1>

    @forelse(auth()->user()->notifications as $notification)

        <div class="bg-white p-6 rounded-xl shadow border">
            <p class="font-semibold">
                {{ $notification->data['message'] }}
            </p>

            <p class="text-sm text-gray-500">
                Client : {{ $notification->data['user_name'] }}
            </p>

            <p class="text-orange-600 font-bold">
                {{ number_format($notification->data['total'],0,',',' ') }} FCFA
            </p>
        </div>

    @empty
        <p>Aucune notification</p>
    @endforelse

</div>

@endsection
