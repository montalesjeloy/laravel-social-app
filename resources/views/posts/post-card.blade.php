{{-- Post Card --}}
<div
    x-data="{
        hidden: false,
        loading: false,

        async hidePost() {
            this.loading = true;

            const response = await fetch('{{ route('posts.hide', $post) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                this.hidden = true;
            }

            this.loading = false;
        },

        async unhidePost() {
            this.loading = true;

            const response = await fetch('{{ route('posts.unhide', $post) }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                this.hidden = false;
            }

            this.loading = false;
        }
    }"
    class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
>

    {{-- Normal Post Content --}}
    <div x-show="!hidden">

        {{-- Header --}}
        <div class="flex items-start justify-between">

            <div class="flex gap-3">

                {{-- User Avatar --}}
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-neutral-200 font-semibold text-neutral-700 dark:bg-neutral-700 dark:text-white">
                    {{ $post->user->initials() }}
                </div>

                {{-- User Info --}}
                <div>

                    {{-- User Name --}}
                    <h3 class="font-semibold text-neutral-900 dark:text-white">
                        {{ $post->user->name }}
                    </h3>

                    {{-- Date --}}
                    <p class="text-sm text-neutral-500">
                        {{ $post->created_at->diffForHumans() }}
                    </p>

                </div>

            </div>

            {{-- More Button --}}
            {{-- Show menu for all posts --}}
            <div class="relative" x-data="{ open: false }">

                {{-- Three dots --}}
                <button
                    @click="open = !open"
                    class="rounded-full p-2 transition hover:bg-neutral-100 dark:hover:bg-neutral-800"
                >
                    ⋯
                </button>

                {{-- Dropdown --}}
                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition
                    class="absolute right-0 z-50 mt-2 w-40 overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-lg dark:border-neutral-700 dark:bg-neutral-900"
                >

                    @if(auth()->id() === $post->user_id)

                        {{-- Edit --}}
                        <a
                            href="{{ route('posts.edit', $post) }}"
                            class="block px-4 py-3 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800"
                        >
                            ✏️ Edit
                        </a>

                        {{-- Delete --}}
                        <form
                            action="{{ route('posts.destroy', $post) }}"
                            method="POST"
                            onsubmit="return confirm('Delete this post?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="block w-full px-4 py-3 text-left text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30"
                            >
                                🗑 Delete
                            </button>
                        </form>

                    @else

                        {{-- Hide Post --}}
                        <button
                            type="button"
                            @click="open = false; hidePost()"
                            :disabled="loading"
                            class="block w-full px-4 py-3 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800"
                        >
                            🚫 Hide Post
                        </button>

                        {{-- Report --}}
                        <button
                            type="button"
                            class="block w-full px-4 py-3 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800"
                        >
                            🚩 Report
                        </button>

                    @endif

                </div>

            </div>

        </div>

        {{-- Post Body --}}
        @if($post->body)
            <p class="mt-4 whitespace-pre-line text-[15px] leading-6 text-neutral-900 dark:text-white">
                {{ $post->body }}
            </p>
        @endif

        {{-- Post Images --}}
        @if($post->images->count())

            @php $count = $post->images->count(); @endphp

            <div class="mt-4 overflow-hidden rounded-2xl">

                {{-- 1 Image --}}
                @if($count == 1)

                    <img
                        src="{{ asset('storage/'.$post->images[0]->image) }}"
                        class="max-h-[520px] w-full object-cover"
                    >

                {{-- 2 Images --}}
                @elseif($count == 2)

                    <div class="grid grid-cols-2 gap-1">

                        @foreach($post->images as $image)

                            <img
                                src="{{ asset('storage/'.$image->image) }}"
                                class="h-80 w-full object-cover"
                            >

                        @endforeach

                    </div>

                {{-- 3 Images --}}
                @elseif($count == 3)

                    <div class="grid grid-cols-2 gap-1">

                        <img
                            src="{{ asset('storage/'.$post->images[0]->image) }}"
                            class="h-[500px] w-full object-cover"
                        >

                        <div class="grid grid-rows-2 gap-1">

                            <img
                                src="{{ asset('storage/'.$post->images[1]->image) }}"
                                class="h-[249px] w-full object-cover"
                            >

                            <img
                                src="{{ asset('storage/'.$post->images[2]->image) }}"
                                class="h-[249px] w-full object-cover"
                            >

                        </div>

                    </div>

                {{-- 4+ Images --}}
                @else

                    <div class="grid grid-cols-2 gap-1">

                        @foreach($post->images->take(4) as $image)

                            <div class="relative">

                                <img
                                    src="{{ asset('storage/'.$image->image) }}"
                                    class="h-64 w-full object-cover"
                                >

                                @if($loop->last && $count > 4)

                                    <div class="absolute inset-0 flex items-center justify-center bg-black/60 text-3xl font-bold text-white">
                                        +{{ $count - 4 }}
                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        @endif

        {{-- Actions --}}
        <div class="mt-4 flex items-center justify-around border-t border-neutral-200 pt-3 dark:border-neutral-700">

            {{-- Like --}}
            <button
                class="flex items-center gap-2 rounded-lg px-4 py-2 text-neutral-500 transition hover:bg-neutral-100 hover:text-red-500 dark:hover:bg-neutral-800"
            >
                ❤️
                <span>Like</span>
            </button>

            {{-- Comment --}}
            <button
                class="flex items-center gap-2 rounded-lg px-4 py-2 text-neutral-500 transition hover:bg-neutral-100 hover:text-blue-500 dark:hover:bg-neutral-800"
            >
                💬
                <span>Comment</span>
            </button>

            {{-- Repost --}}
            <button
                class="flex items-center gap-2 rounded-lg px-4 py-2 text-neutral-500 transition hover:bg-neutral-100 hover:text-green-500 dark:hover:bg-neutral-800"
            >
                🔁
                <span>Repost</span>
            </button>

            {{-- Share --}}
            <button
                class="flex items-center gap-2 rounded-lg px-4 py-2 text-neutral-500 transition hover:bg-neutral-100 hover:text-sky-500 dark:hover:bg-neutral-800"
            >
                ↗
                <span>Share</span>
            </button>

        </div>

    </div>
    {{-- End Normal Post Content --}}


    {{-- Hidden Post Message --}}
    <div
        x-show="hidden"
        class="flex items-center justify-between"
    >
        <span class="text-sm text-neutral-500">
            Post hidden.
        </span>

        <button
            type="button"
            @click="unhidePost()"
            :disabled="loading"
            class="text-sm font-semibold text-blue-500 hover:underline"
        >
            Undo
        </button>
    </div>

</div>
{{-- End Post Card --}}