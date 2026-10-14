<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}}</title>
</head>
<body>
    @foreach ($users as $user )
    <h1> Nome: {{ $user->name }}</h1>
    <p> Enail: {{ $user->email }}</p>

    @if($user->id === 10)
        <p>Usuario com id 10</p>
    @else
        <p>Usuario com id  <> 10</p>
    @endif
<<<<<<< HEAD
        git
=======
        
>>>>>>> 809676c2b0296ab7cb5f8b0d616893aea033e9df
    @endforeach

    {{ $users->links() }}
    
</body>
</html>