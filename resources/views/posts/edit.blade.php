<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Edit Post</h1>

    <form action="{{ route('posts.update', $post->id) }}" 
        method="POST" 
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <label>Title:</label>
        <input type="text" name="title" value="{{ old('title', $post->title) }}">
        
        <br><br>
        
        <label>Body:</label>
        <textarea name="body">{{ old('body', $post->body) }}</textarea>
        
        <br><br>
        
        @if ($post->image)
            <img id="currentImage" 
            src="{{ asset('storage/' . $post->image) }}" 
            width="200">
        @endif

        <br><br>

        <img id="previewImage" 
            width="200" 
            style="display:none;">
            
        <br><br>

        <input type="file" name="image" id="imageInput">
        
        <br><br>

        <button type="button" id="cancelBtn">
            Cancel
        </button>
        
        <button type="submit">
            Update
        </button>
    
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
        
        const currentImage = document.getElementById('currentImage');
        
        const previewImage = document.getElementById('previewImage');
        
        const cancelBtn = document.getElementById('cancelBtn');

        imageInput.addEventListener('change', function(event) {

            const file = event.target.files[0];

            if(!file) return;

                previewImage.src = URL.createObjectURL(file);
                previewImage.style.display = "block";
            
                //hide old image
                if(currentImage){
                    currentImage.style.display = "none";
                }

            cancelBtn.style.display = 'inline-block';

        });

            cancelBtn.addEventListener('click', function () {

            imageInput.value = "";

            previewImage.src = "";
            previewImage.style.display = "none";

            if (currentImage) {
                currentImage.style.display = "block";
            }

            cancelBtn.style.display = "none";
        });

    </script>
</body>
</html>