<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>All posts!</h1>
    <div> 
        <form action="{{ route('posts.index') }}" method="GET">

        <input 
            type="text" 
            name="search"
            placeholder="Search posts..."
            value="{{ request('search') }}"
        >

        <button type="submit">
            Search
        </button>

        </form>
    </div>

    <a href="{{ route('posts.create') }}">Create a New Post</a>

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
    @endif

    @foreach ($posts as $post)
    <div>
        <h2>{{ $post->title }}</h2>
        <p>{{ $post->body }}</p>  
        @if ($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" width="200">
        @endif
        <br>
        <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline">
            @csrf
            @method('DELETE')

            <button type="button" onclick="openDeleteModal({{ $post->id }})">
                Delete
            </button>
        </form>
    </div>      
    @endforeach

    <!-- Delete Modal -->
    <div id="deleteModal" style="
        display:none;
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background:rgba(0,0,0,0.4);
        backdrop-filter:blur(2px);
        justify-content:center;
        align-items:center;
        z-index:9999;
    ">

    <div style="
        background:rgba(30,31,30,0.75);
        color:white;
        padding:30px;
        border-radius:15px;
        text-align:center;
    ">

        <h2>Are you sure?</h2>
        <p>Do you want to delete this post?</p>

        <button onclick="closeDeleteModal()">
            Cancel
        </button>

        <form id="deleteForm" method="POST" style="display:inline">
            @csrf
            @method('DELETE')

            <button type="submit">
                Delete
            </button>
        </form>

    </div>

</div>


<script>
function openDeleteModal(id)
{
    document.getElementById('deleteModal').style.display = "flex";

    document.getElementById('deleteForm').action = "/posts/" + id;
}

function closeDeleteModal()
{
    document.getElementById('deleteModal').style.display = "none";
}
</script>
<script>
        setTimeout(() => {
            document.getElementById('alert').remove();
        }, 3000);
</script>

</body>
</html>