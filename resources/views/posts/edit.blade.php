@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Edit student</h1>

        @if($errors-> any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors-> all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('posts.update', $student->id) }}">
            @csrf
            @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name: </label>
                    <input type="text" class="form-control" name="name" id="name" value="{{old('name', $student->name)}}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Name</button>
                <a href="{{route('posts.index')}}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection