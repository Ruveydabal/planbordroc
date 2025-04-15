<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
</head>
<body>
    <h1>Create new student</h1>

    @if($errors-> any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors-> all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('posts.store')}}">
        @csrf
        <div>
            <label for="name">Name: </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"></input>
        </div>

        <button type="submit">Create Name</button>
    </form>
</body>
</html>