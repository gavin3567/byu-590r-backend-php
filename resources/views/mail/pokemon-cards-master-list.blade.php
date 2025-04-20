<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h2>Hello manager,</h2>

    <p>Here is the list of checked out Pokemon cards:</p>

    <table>
        <thead>
            <tr>
                <th>Card Name</th>
                <th>Pokemon Name</th>
                <th>Card Rarity</th>
                <th>Checked Out Qty</th>
                <th>Total Qty</th>
                <th>Late Fee</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cards as $card)
            <tr>
                <td>{{ $card->name }}</td>
                <td>{{ $card->pokemon_name }}</td>
                <td>{{ $card->card_rarity }}</td>
                <td>{{ $card->checked_qty }}</td>
                <td>{{ $card->inventory_total_qty }}</td>
                <td>$5.00</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>Thank you,<br>Pokemon Card Management System</p>
</body>
</html>