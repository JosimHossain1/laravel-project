
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Product Management')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f5f7;
            margin: 0;
            padding: 40px 20px;
            color: #222;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
        }

        h1 {
            margin-top: 0;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0 18px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        label {
            font-weight: bold;
        }

        button, .btn {
            display: inline-block;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .primary {
            background: #2563eb;
            color: white;
        }

        .edit {
            background: #16a34a;
            color: white;
        }

        .delete {
            background: #dc2626;
            color: white;
        }

        .back {
            background: #64748b;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #eee;
        }

        .success {
            padding: 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: -12px;
            margin-bottom: 12px;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }
    </style>
</head>

<body>

    <div class="container">

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')

    </div>

</body>
</html>
