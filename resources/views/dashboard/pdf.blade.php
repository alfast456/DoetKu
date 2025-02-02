<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income & Expense Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .container {
            margin: 0 auto;
            width: 80%;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Income & Expense Report</h1>
        <table>
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incomesAndExpenses as $incomeAndExpense)
                <tr>
                    <td>{{ number_format($incomeAndExpense->amount, 0, ',', '.') }}</td>
                    <td>{{ $incomeAndExpense->description }}</td>
                    <td>{{ $incomeAndExpense->date }}</td>
                    <td>{{ $categories->where('id_category', $incomeAndExpense->id_category)->first()->name_category ?? 'Tidak Diketahui' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>