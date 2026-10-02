<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Manager</title>

    <style>
        label {
            display: block;
            margin-top: 10px;
        }

        input,
        textarea,
        select {
            display: block;
            margin-top: 5px;
        }

        .error {
            color: red;
        }

        nav[role="navigation"] svg {
            width: 16px;
            height: 16px;
        }

        .pagination {
            display: flex;
            gap: 8px;
            list-style: none;
            padding: 0;
        }
        
    </style>
</head>
<body>

    @yield('content')

</body>
</html>