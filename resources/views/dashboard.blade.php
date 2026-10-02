<x-layouts::app :title="__('Dashboard')">
    
    <div class="flex h-full w-full gap-6">

        {{-- Feed --}}
        <div class="flex flex-1 flex-col gap-4">

            {{-- Create Post --}}
            @include('posts.create-post')

            {{-- Feed --}}

            <div class="space-y-4">

                @foreach($posts as $post)

                    @include('posts.post-card', ['post' => $post])

                @endforeach

            </div>
            
        </div>

        {{-- Right Panel --}}
        <div class="hidden w-80 shrink-0 lg:flex lg:flex-col gap-4">

            {{-- Search --}}
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                Search
            </div>

            {{-- Trending --}}
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                Trending
            </div>

            {{-- Suggested Users --}}
            <div class="flex-1 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                Suggested Users
            </div>

        </div>

    </div>

</x-layouts::app>
