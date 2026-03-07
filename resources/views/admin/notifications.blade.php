@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ auth()->user()->unreadNotifications->count() }} non lue(s)
            </p>
        </div>

        @if(auth()->user()->unreadNotifications->isNotEmpty())
            <form method="POST" action="{{ route('admin.notifications.markAllRead') }}">
                @csrf
                <button type="submit"
                        class="text-sm text-gray-500 hover:text-orange-600 transition font-medium border px-4 py-2 rounded-lg hover:border-orange-400">
                    ✓ Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    {{-- ✅ $notifications vient du contrôleur, paginé, non lues en premier --}}
    @forelse($notifications as $notification)
        @php $isUnread = is_null($notification->read_at); @endphp

        <div class="bg-white p-6 rounded-xl shadow border transition
            {{ $isUnread ? 'border-l-4 border-orange-400' : 'border-gray-100 opacity-75' }}">

            <div class="flex justify-between items-start gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        @if($isUnread)
                            <span class="w-2 h-2 bg-orange-500 rounded-full shrink-0"></span>
                        @endif
                        <p class="font-semibold text-gray-800">
                            {{ $notification->data['message'] ?? 'Nouvelle commande reçue' }}
                        </p>
                    </div>

                    @if(isset($notification->data['user_name']))
                        <p class="text-sm text-gray-500 mt-1">
                            Client : <span class="font-medium">{{ $notification->data['user_name'] }}</span>
                        </p>
                    @endif

                    @if(isset($notification->data['total']))
                        <p class="text-orange-600 font-bold mt-1">
                            {{ number_format($notification->data['total'], 0, ',', ' ') }} FCFA
                        </p>
                    @endif

                    @if(isset($notification->data['order_id']))
                        {{-- ✅ Cliquer sur ce lien marque la notification comme lue via show() --}}
                        <a href="{{ route('admin.orders.show', $notification->data['order_id']) }}"
                           class="inline-block mt-3 text-sm font-medium text-orange-500 hover:underline">
                            Voir la commande #{{ $notification->data['order_id'] }} →
                        </a>
                    @endif
                </div>

                <div class="text-right shrink-0">
                    <p class="text-xs text-gray-400">
                        {{ $notification->created_at?->diffForHumans() }}
                    </p>
                    @if($isUnread)
                        <span class="text-xs bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full mt-1 inline-block">
                            Non lue
                        </span>
                    @else
                        <span class="text-xs text-gray-300 mt-1 inline-block">Lue</span>
                    @endif
                </div>
            </div>
        </div>

    @empty
        <div class="bg-white rounded-xl border p-14 text-center">
            <p class="text-gray-400 text-lg">Aucune notification</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div>{{ $notifications->links() }}</div>
    @endif

</div>
@endsection
