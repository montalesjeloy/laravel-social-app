{{-- Edit Post Modal --}}
<div
    x-show="editOpen"
    x-cloak
    x-transition.opacity
    @keydown.escape.window="editOpen = false"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4"
>
    {{-- Modal Window --}}
    <div
        @click.outside="editOpen = false"
        x-data="{
            body: @js($post->body ?? ''),
            originalBody: @js($post->body ?? ''),

            images: [],
            previews: [],
            removedImages: [],

            saving: false,
            error: null,

            hasChanges() {
                return this.body !== this.originalBody
                    || this.images.length > 0
                    || this.removedImages.length > 0;
            },

            async savePost() {
                if (!this.hasChanges() || this.saving) return;

                this.saving = true;
                this.error = null;

                const formData = new FormData();

                formData.append('_method', 'PUT');
                formData.append('body', this.body);

                this.images.forEach((image) => {
                    formData.append('images[]', image);
                });

                this.removedImages.forEach((imageId) => {
                    formData.append('removed_images[]', imageId);
                });

                const response = await fetch('{{ route('posts.update', $post) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.ok) {
                    window.location.reload();
                    return;
                }

                const data = await response.json();

                this.error = data.message ?? 'Something went wrong.';
                this.saving = false;
            }
        }"
        class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl border border-neutral-200 bg-white shadow-2xl dark:border-neutral-700 dark:bg-neutral-900"
    >

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">

            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                Edit Post
            </h2>

            {{-- Close --}}
            <button
                type="button"
                @click="editOpen = false"
                class="flex h-9 w-9 items-center justify-center rounded-full text-xl text-neutral-500 transition hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800"
            >
                ×
            </button>

        </div>


        {{-- Edit Content --}}
        <div class="px-6 py-5">

            {{-- Text --}}
            <textarea
                x-ref="editTextarea"
                x-model="body"
                x-init="
                    $nextTick(() => {
                        $refs.textarea.style.height = '0px';
                        $refs.textarea.style.height = $refs.textarea.scrollHeight + 'px';
                    })
                "
                @input="
                    $refs.editTextarea.style.height = '0px';
                    $refs.editTextarea.style.height = $refs.editTextarea.scrollHeight + 'px';
                "
                rows="2"
                class="w-full resize-none border-none bg-transparent p-1 text-lg text-neutral-900 outline-none placeholder:text-neutral-500 focus:ring-0 dark:text-white"
            ></textarea>

            {{-- Existing Images --}}
            @if($post->images->count())

                @php
                    $existingCount = $post->images->count();
                @endphp

                <div class="mt-3">

                    {{-- 1 Existing Image --}}
                    @if($existingCount === 1)

                        @foreach($post->images as $image)

                            <div
                                x-show="!removedImages.includes({{ $image->id }})"
                                class="relative overflow-hidden rounded-2xl"
                            >
                                <img
                                    src="{{ asset('storage/' . $image->image) }}"
                                    class="max-h-[500px] w-full object-cover"
                                >

                                {{-- Remove Existing Image --}}
                                <button
                                    type="button"
                                    @click="removedImages.push({{ $image->id }})"
                                    class="absolute left-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white transition hover:bg-red-500"
                                >
                                    ✕
                                </button>
                            </div>

                        @endforeach


                    {{-- Multiple Existing Images --}}
                    @else

                        <div class="grid grid-cols-2 gap-2">

                            @foreach($post->images as $image)

                                <div
                                    x-show="!removedImages.includes({{ $image->id }})"
                                    class="relative overflow-hidden rounded-2xl"
                                >
                                    <img
                                        src="{{ asset('storage/' . $image->image) }}"
                                        class="h-[250px] w-full object-cover"
                                    >

                                    {{-- Remove Existing Image --}}
                                    <button
                                        type="button"
                                        @click="removedImages.push({{ $image->id }})"
                                        class="absolute left-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white transition hover:bg-red-500"
                                    >
                                        ✕
                                    </button>
                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>

            @endif


            {{-- New Image Preview --}}
            @include('posts.media-grid')


            {{-- Controls --}}
            <div class="mt-4 border-t border-neutral-200 pt-4 dark:border-neutral-700">

                <div class="flex items-center justify-between">

                    {{-- Icons --}}
                    <div class="flex items-center gap-5">

                        {{-- Add Image --}}
                        <label class="cursor-pointer">

                            <svg
                                class="h-6 w-6 text-neutral-500 transition hover:text-blue-500 dark:text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M13 10a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2H14a1 1 0 0 1-1-1Z"
                                    clip-rule="evenodd"
                                />

                                <path
                                    fill-rule="evenodd"
                                    d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12c0 .556-.227 1.06-.593 1.422A.999.999 0 0 1 20.5 20H4a2.002 2.002 0 0 1-2-2V6Zm6.892 12 3.833-5.356-3.99-4.322a1 1 0 0 0-1.549.097L4 12.879V6h16v9.95l-3.257-3.619a1 1 0 0 0-1.557.088L11.2 18H8.892Z"
                                    clip-rule="evenodd"
                                />
                            </svg>

                            <input
                                type="file"
                                accept="image/*"
                                multiple
                                class="hidden"
                                @change="
                                    let newImages = Array.from($event.target.files);

                                    images = [...images, ...newImages];

                                    previews = [
                                        ...previews,
                                        ...newImages.map(image => URL.createObjectURL(image))
                                    ];
                                "
                            >

                        </label>


                        {{-- Emoji --}}
                        <span class="cursor-pointer text-neutral-500 transition hover:text-blue-500">
                            😊
                        </span>

                        {{-- Location --}}
                        <span class="cursor-pointer text-neutral-500 transition hover:text-blue-500">
                            📍
                        </span>

                        {{-- Flag --}}
                        <span class="cursor-pointer text-neutral-500 transition hover:text-blue-500">
                            ⚑
                        </span>

                    </div>

                    {{-- Save Changes --}}
                    <button
                        type="button"
                        @click="savePost()"
                        :disabled="!hasChanges() || saving"
                        :class="
                            !hasChanges() || saving
                                ? 'bg-neutral-500 cursor-not-allowed'
                                : 'bg-blue-500 hover:bg-blue-600'
                        "
                        class="rounded-full px-6 py-2 font-semibold text-white transition"
                    >
                        <span x-show="!saving">
                            Save Changes
                        </span>

                        <span x-show="saving">
                            Saving...
                        </span>
                    </button>

                </div>
                {{-- End controls row --}}

                {{-- Error Message --}}
                <p
                    x-show="error"
                    x-text="error"
                    class="mt-3 text-sm text-red-500"
                ></p>

            </div>

        </div>

    </div>

</div>