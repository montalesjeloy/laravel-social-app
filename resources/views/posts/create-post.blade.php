{{-- Create Post --}}
<div class="rounded-xl border border-neutral-200 bg-white px-6 py-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

    <form
        x-data="{
            focused: false,
            body: '',
            images: [],
            previews: []
        }"
        @click.outside="
            if(body.trim() === '' && previews.length === 0) {
                focused = false
            }
        "
        action="{{ route('posts.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="flex items-start gap-2">

            {{-- Avatar --}}
            <div class="shrink-0">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-neutral-200 font-semibold text-neutral-700 dark:bg-neutral-700 dark:text-white">
                    {{ auth()->user()->initials() }}
                </div>
            </div>

            {{-- Content --}}
            <div class="flex flex-1 flex-col">

                {{-- Text --}}
                <textarea
                    x-ref="textarea"
                    x-init="
                        $nextTick(() => {
                            $refs.textarea.style.height = '0px';
                            $refs.textarea.style.height = $refs.textarea.scrollHeight + 'px';
                        })
                    "
                    name="body"
                    x-model="body"
                    @focus="focused = true"
                    @input="
                        $refs.textarea.style.height = '0px';
                        $refs.textarea.style.height = $refs.textarea.scrollHeight + 'px';
                    "
                    rows="2"
                    placeholder="What's happening?"
                    class="w-full resize-none border-none bg-transparent p-1 text-lg outline-none placeholder:text-neutral-500 focus:ring-0 dark:text-white"
                >{{ old('body') }}</textarea>

                {{-- Image Preview --}}
                @include('posts.media-grid')
                
                @error('body')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

                {{-- Controls --}}
                <div
                    :class="
                        focused || body.trim() !== '' || images.length > 0
                        ? 'border-t border-neutral-200 pt-3 dark:border-neutral-700'
                        : 'pt-3'
                    "
                    class="mt-3 transition-all"
                >

                    <div class="flex items-center justify-between">

                        {{-- Icons --}}
                        <div class="flex items-center gap-5">

                            {{-- Image --}}
                            <label class="cursor-pointer">

                                <svg
                                    class="h-6 w-6 text-neutral-500 hover:text-blue-500 dark:text-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path fill-rule="evenodd"
                                        d="M13 10a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2H14a1 1 0 0 1-1-1Z"
                                        clip-rule="evenodd"/>

                                    <path fill-rule="evenodd"
                                        d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12c0 .556-.227 1.06-.593 1.422A.999.999 0 0 1 20.5 20H4a2.002 2.002 0 0 1-2-2V6Zm6.892 12 3.833-5.356-3.99-4.322a1 1 0 0 0-1.549.097L4 12.879V6h16v9.95l-3.257-3.619a1 1 0 0 0-1.557.088L11.2 18H8.892Z"
                                        clip-rule="evenodd"/>

                                </svg>

                                <input
                                    type="file"
                                    name="images[]"
                                    accept="image/*"
                                    multiple
                                    class="hidden"

                                    @change="
                                        let newImages = Array.from($event.target.files);
                                        images = [...images, ...newImages];
                                        previews = [
                                            ...previews,
                                            ...newImages.map(
                                                image => URL.createObjectURL(image)
                                            )
                                        ];
                                        
                                        focused = true;
                                    "
                                >
                            </label>

                            <span class="cursor-pointer text-neutral-500 hover:text-blue-500">
                                😊
                            </span>

                            <span class="cursor-pointer text-neutral-500 hover:text-blue-500">
                                📍
                            </span>

                            <span class="cursor-pointer text-neutral-500 hover:text-blue-500">
                                ⚑
                            </span>

                        </div>

                        {{-- Button --}}
                        <button

                            type="submit"

                            :disabled="body.trim() === '' && images.length === 0"

                            :class="
                                body.trim() === '' && images.length === 0
                                ? 'bg-neutral-500 cursor-not-allowed'
                                : 'bg-blue-500 hover:bg-blue-600'
                            "
                            class="rounded-full px-6 py-2 font-semibold text-white transition"
                        >
                            Post
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>