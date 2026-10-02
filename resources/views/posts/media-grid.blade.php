{{-- One Image --}}
<template x-if="previews.length === 1">
    <div class="mt-3">
        <div class="relative overflow-hidden rounded-2xl">
            <img
                :src="previews[0]"
                class="max-h-[500px] w-full object-cover"
            >
            <button
                type="button"

                @click="
                    previews.splice(0,1);
                    images.splice(0,1);
                "

                class="absolute left-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-red-500"
            >
                ✕
            </button>
        </div>
    </div>
</template>

{{-- Two Images --}}
<template x-if="previews.length === 2">
    <div class="mt-3 grid grid-cols-2 gap-2">
        <template x-for="(preview,index) in previews" :key="index">
            <div class="relative overflow-hidden rounded-2xl">
                <img
                    :src="preview"
                    class="h-[300px] w-full object-cover"
                >
                <button
                    type="button"
                    @click="
                        previews.splice(index,1);
                        images.splice(index,1);
                    "
                    class="absolute left-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-red-500"
                >
                    ✕
                </button>
            </div>
        </template>
    </div>
</template>

{{-- Three Images --}}
<template x-if="previews.length === 3">
    <div class="mt-3 grid grid-cols-2 gap-2 h-[300px]">
        {{-- Left --}}
        <div class="relative h-[300px] overflow-hidden rounded-2xl">
            <img
                :src="previews[0]"
                class="h-full w-full object-cover"
            >
            <button
                type="button"
                @click="
                    previews.splice(0,1);
                    images.splice(0,1);
                "
                class="absolute left-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-red-500"
            >
                ✕
            </button>
        </div>
        {{-- Right --}}
        <div class="grid h-[300px] grid-rows-2 gap-2">
            <template
                x-for="(preview,index) in previews.slice(1)"
                :key="index"
            >

                <div class="relative overflow-hidden rounded-2xl">
                    <img
                        :src="preview"
                        class="h-full w-full object-cover"
                    >
                    <button
                        type="button"
                        @click="
                            previews.splice(index+1,1);
                            images.splice(index+1,1);
                        "
                        class="absolute left-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-red-500"
                    >
                        ✕
                    </button>
                </div>
            </template>
        </div>
    </div>
</template>


{{-- Four Images --}}
<template x-if="previews.length >= 4">

    <div class="mt-3 grid grid-cols-2 gap-2">

        <template
            x-for="(preview,index) in previews.slice(0,4)"
            :key="index"
        >
            <div class="relative overflow-hidden rounded-2xl">
                <img
                    :src="preview"
                    class="h-[200px] w-full object-cover"
                >
                <button
                    type="button"
                    @click="
                        previews.splice(index,1);
                        images.splice(index,1);
                    "
                    class="absolute left-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-red-500"
                >
                    ✕
                </button>
            </div>
        </template>
    </div>
</template>