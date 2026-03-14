<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu {{ $order->reference }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', 'Courier New', monospace;
            font-size: 11px;
            color: #1a1a1a;
            background: #fff;
            width: 220px; /* ticket 80mm ≈ 226px à 72dpi */
            padding: 12px 10px;
        }

        /* ──── EN-TÊTE ──── */
        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
            margin-bottom: 10px;
        }

        .logo-badge {
            display: inline-block;
            background: #f97316;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
            width: 42px;
            height: 42px;
            line-height: 42px;
            border-radius: 8px;
            margin-bottom: 6px;
            text-align: center;
        }

        .shop-name {
            font-size: 15px;
            font-weight: bold;
            color: #f97316;
            letter-spacing: 0.5px;
        }

        .shop-subtitle {
            font-size: 9px;
            color: #888;
            margin-top: 2px;
        }

        /* ──── INFOS REÇU ──── */
        .receipt-info {
            margin-bottom: 10px;
            font-size: 10px;
            color: #555;
        }

        .receipt-info .ref {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 3px;
        }

        .badge-pos {
            display: inline-block;
            background: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
            border-radius: 4px;
            padding: 1px 5px;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        /* ──── ARTICLES ──── */
        .divider {
            border: none;
            border-top: 1px dashed #ccc;
            margin: 8px 0;
        }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .item {
            margin-bottom: 6px;
        }

        .item-name {
            font-weight: bold;
            font-size: 11px;
            color: #1a1a1a;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #555;
            margin-top: 1px;
        }

        .item-promo {
            font-size: 9px;
            color: #f97316;
        }

        /* ──── TOTAL ──── */
        .total-section {
            background: #fff7ed;
            border-radius: 6px;
            padding: 8px;
            margin: 8px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #555;
            margin-bottom: 3px;
        }

        .total-final {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
            color: #f97316;
            padding-top: 5px;
            border-top: 1px solid #fed7aa;
            margin-top: 5px;
        }

        /* ──── QR / RÉFÉRENCE ──── */
        .qr-section {
            text-align: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
        }

        .qr-ref {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 5px 10px;
            display: inline-block;
            margin: 6px 0;
        }

        /* ──── PIED DE PAGE ──── */
        .footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px dashed #ccc;
            font-size: 9px;
            color: #aaa;
            line-height: 1.6;
        }

        .footer strong {
            color: #f97316;
        }
    </style>
</head>
<body>

{{-- EN-TÊTE --}}
<div class="header">
    <div class="logo-badge">O'G</div>
    <div class="shop-name">O'G Délice</div>
    <div class="shop-subtitle">Restaurant • Fast-Food • Traiteur</div>
    <div class="shop-subtitle">Dakar, Sénégal</div>
</div>

{{-- INFOS REÇU --}}
<div class="receipt-info">
    <div class="badge-pos">VENTE DIRECTE</div>
    <div class="ref">{{ $order->reference }}</div>
    <div>Date : {{ $order->created_at->format('d/m/Y à H:i') }}</div>
    <div>Caissier : {{ $order->vendeur?->name ?? '—' }}</div>
    @if($order->customer_name)
        <div>Client : {{ $order->customer_name }}</div>
    @endif
</div>

<hr class="divider">

{{-- ARTICLES --}}
<div class="section-title">Articles</div>

@foreach($order->items as $item)
    <div class="item">
        <div class="item-name">{{ $item->product?->name ?? 'Produit supprimé' }}</div>
        <div class="item-detail">
            <span>{{ $item->quantity }} × {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</span>
            <span><strong>{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</strong></span>
        </div>
        @if($item->had_promo)
            <div class="item-promo">
                ↓ Prix promo appliqué ({{ number_format($item->unit_price_promo, 0, ',', ' ') }} FCFA)
            </div>
        @endif
    </div>
@endforeach

<hr class="divider">

{{-- TOTAL --}}
<div class="total-section">
    <div class="total-row">
        <span>Nombre d'articles</span>
        <span>{{ $order->items->sum('quantity') }}</span>
    </div>
    <div class="total-row">
        <span>Sous-total</span>
        <span>{{ number_format($order->total_price, 0, ',', ' ') }} FCFA</span>
    </div>
    <div class="total-row">
        <span>Mode de paiement</span>
        <span>Espèces</span>
    </div>
    <div class="total-final">
        <span>TOTAL</span>
        <span>{{ number_format($order->total_price, 0, ',', ' ') }} FCFA</span>
    </div>
</div>

@if($order->note)
    <div style="font-size:9px; color:#888; margin-bottom:6px;">
        Note : {{ $order->note }}
    </div>
@endif

{{-- RÉFÉRENCE / QR CODE --}}
<div class="qr-section">
    <div style="font-size:9px; color:#aaa;">Référence de la transaction</div>
    <div class="qr-ref">{{ $order->reference }}</div>
    <div style="font-size:8px; color:#ccc;">{{ $order->created_at->format('YmdHis') }}-{{ $order->id }}</div>
</div>

{{-- PIED DE PAGE --}}
<div class="footer">
    <div>Merci pour votre visite !</div>
    <div><strong>O'G Délice</strong></div>
    <div>Reçu valable comme justificatif d'achat</div>
    <div style="margin-top:4px;">━━━━━━━━━━━━━━━━━━</div>
</div>

</body>
</html>
