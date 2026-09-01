{{-- En el archivo pdf no puedo usar los estilos tailwind, por eso lo hago con css --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del Moviemiento</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .section {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="title">Detalle del Movimiento
        #{{ $movement->serie }}-{{ str_pad($movement->correlative, 4, '0', STR_PAD_LEFT) }}</div>

    <div>
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($movement->date)->format('d/m/Y') }}<br>
        <strong>Tipo de movimiento:</strong> {{ $movement->type== 1 ? 'Ingreso' : 'Salida'}}<br>
        <strong>Motivo:</strong> {{ $movement->reason->name ?? '—' }} <br>
        <strong>Almacén:</strong> {{ $movement->warehouse->name ?? '—' }}<br>
        <strong>Observación:</strong> {{ $movement->observation ?? '—' }}
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movement->products as $i => $product)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>S/ {{ number_format($product->pivot->price, 2) }}</td>
                        <td>S/ {{ number_format($product->pivot->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section" style="text-align: right;">
        <strong>Total: S/ {{ number_format($movement->total, 2) }}</strong>
    </div>

</body>

</html>
