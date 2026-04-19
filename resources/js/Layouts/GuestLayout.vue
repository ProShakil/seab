<script setup>
// import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage  } from '@inertiajs/vue3';
const { props } = usePage()
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <img
                    :src="props.siteSettings?.logo
                    ? `/storage/${props.siteSettings.logo}`
                    : '/assets/logo.png'"
                    class="h-20 w-20 fill-current text-gray-500"
                />
                <!-- <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" /> -->
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
