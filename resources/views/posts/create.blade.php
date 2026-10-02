<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>What's in your mind?</h1>

    @if(session('success'))
    <div id="alert" style="
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(30, 31, 30, 0.75);
        backdrop-filter: blur(5px);
        color: white;
        padding: 15px 30px;
        border-radius: 10px;
        font-size: 18px;
        z-index: 9999;
    ">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Title:</label>
        <input type="text" name="title" value="{{ old('title') }}">
        <br><br>
        <label>Body:</label>
        <textarea name="body">{{ old('body') }}</textarea>
        <br><br>
        <label>Image:</label>
        <input type="file" name="image" id="imageInput">
        <img id="preview" width="200" style="display:none;">
        <br><br>
        <button type="submit">Save</button>
    </form>

    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>            
        @endforeach
    </ul>
    @endif

    <script>
        const imageInput = document.getElementById('imageInput');
        const preview = document.getElementById('preview');

        imageInput.addEventListener('change', function(event) {

            const file = event.target.files[0];

            if(file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = "block";
            }

        });
    </script>
</body>
</html>