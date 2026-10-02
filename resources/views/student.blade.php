<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test App</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body>

    <header>
        <h2>This is the header!</h2>
    </header>

    <h1>Hello Student</h1>
    
<p>Welcome to Laravel 13</p>

    <h1>Test Form</h1>
    <a href="{{ ('/portfolio') }}">Go to Portfolio</a>
    

    <footer>
        <h2>This is Footer</h2>
    </footer>

</body>

</html>