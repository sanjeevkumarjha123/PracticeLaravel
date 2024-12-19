<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Hello, {{ $model->fullname }}</h1>
    <h2>Your email {{$model->email}} !!</h2>
    <h3>Your role {{$model->role}}.</h3>
    <h4>Your password {{$password}}.</h4>
    <p>Thank you for joining our platform.</p>
</body>
</html>
