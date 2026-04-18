<script setup>
import { ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3';
const page = usePage()
const open = ref(false)
const aboutOpen = ref(false)
const committeeOpen = ref(false)
const memberOpen = ref(false)
const userOpen = ref(false)
</script>

<template>
    <Head>
    <title>{{ page.props.siteSettings?.site_title }}</title>

    <link
        rel="icon"
        :href="page.props.siteSettings?.favicon
        ? `/storage/${page.props.siteSettings.favicon}`
        : '/favicon.ico'"
    />
    </Head>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <header class="sticky top-0 z-50 bg-[#001E3C] shadow-md">
        <nav class="relative w-full h-[70px] px-6 md:px-16 flex items-center justify-between">

        <!-- LOGO -->
        <div class="flex items-center gap-3">

            <!-- LOGO -->
            <img
            :src="page.props.siteSettings?.logo
            ? `/storage/${page.props.siteSettings.logo}`
            : '/assets/logo.png'"
            class="w-10 h-10 rounded-full border-2"
            style="border-color:#D4AF37;"
            />


            <!-- TEXT -->
            <div class="leading-tight">
                <div class="text-white font-bold text-xs md:text-base">
                    {{ page.props.siteSettings?.headline }}
                </div>
                <div class="text-xs text-gray-300">
                    {{ page.props.siteSettings?.subtitle }}
                </div>
            </div>

        </div>
        <!-- DESKTOP MENU -->
        <ul class="hidden md:flex items-center gap-6 text-white">
            <li><Link preserve-scroll class="text-white hover:text-[#D4AF37]" href="/">Home</Link></li>
            <li class="relative group">
                <button class="flex items-center gap-1 text-white hover:text-[#D4AF37]">About
                    <svg class="w-3 h-3 transition-transform group-hover:rotate-180" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="m1 1 4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="absolute left-0 top-full mt-2 w-48 bg-white border border-zinc-200 rounded-xl shadow-lg overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <Link preserve-scroll class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]" :href="route('about', 'president_message')">President Message</Link>
                    <Link preserve-scroll class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]" :href="route('about', 'vice_president_message')">Vice President Message</Link>
                    <Link preserve-scroll class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]" :href="route('about', 'about_seab')">About SEAB</Link>
                    <Link preserve-scroll class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]" :href="route('about', 'mission')">Mission</Link>
                    <Link preserve-scroll class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]" :href="route('about', 'vision')">Vision</Link>
                </div>
            </li>
            <li class="relative group">
                <button class="flex items-center gap-1 text-white hover:text-[#D4AF37]">Membership
                    <svg class="w-3 h-3 transition-transform group-hover:rotate-180" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="m1 1 4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="absolute left-0 top-full mt-2 w-48 bg-white border border-zinc-200 rounded-xl shadow-lg overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <Link preserve-scroll class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]" :href="route('member.list')">Membership List</Link>
                    <Link preserve-scroll class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]" :href="route('about', 'membership_process')">Membership Process</Link>
                    <Link preserve-scroll class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]" :href="route('register')">Become a Member</Link>
                </div>
            </li>
            <li class="relative group">
                <button class="flex items-center gap-1 text-white hover:text-[#D4AF37]">
                    Committee
                    <svg class="w-3 h-3 transition-transform group-hover:rotate-180" viewBox="0 0 10 6">
                        <path d="m1 1 4 4 4-4" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </button>
                <div class="absolute left-0 top-full mt-2 w-48 bg-white border border-zinc-200 rounded-xl shadow-lg overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">                    
                    <Link
                        v-for="item in (page.props.committees_cache ?? [])"
                        :key="item.id"
                        preserve-scroll
                        :href="route('committee.details', { param: item.name.toLowerCase().replace(/\s+/g, '-'), id: item.id })"
                        class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]"
                    >
                        {{ item.name }}
                    </Link>
                    <div v-if="!(page.props.committees_cache?.length)" class="px-4 py-2 text-sm text-gray-400">
                        No committee found
                    </div>
                </div>
            </li>  
            <li><Link preserve-scroll class="text-white hover:text-[#D4AF37]" href="#">Gallery</Link></li>
            <li><Link preserve-scroll class="text-white hover:text-[#D4AF37]" :href="route('contact')">Contact</Link></li>
            <template v-if="$page.props.auth?.user">
                <li class="relative group">
                    
                    <!-- BUTTON -->
                    <button class="flex items-center gap-2 text-white hover:text-[#D4AF37]">
                        <span class="material-symbols-outlined text-lg">account_circle</span>
                        {{ $page.props.auth.user.name }}
    
                        <svg class="w-3 h-3 transition-transform group-hover:rotate-180" viewBox="0 0 10 6">
                            <path d="m1 1 4 4 4-4" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </button>
                    
                    <!-- DROPDOWN -->
                    <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-zinc-200 rounded-xl shadow-lg overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <Link preserve-scroll v-if="$page.props.auth?.user?.is_admin == 1 && ($page.props.auth?.user?.admin_role_id == 1 || $page.props.auth?.user?.admin_role_id == 2)" href="/admin_acess" class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]">
                            Admin Access
                        </Link>
    
                        <Link preserve-scroll href="/user/profile" class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]">
                            Profile
                        </Link>
    
                        <Link preserve-scroll href="/user/change-password" class="block px-4 py-2 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-[#D4AF37]">
                            Change Password
                        </Link>
    
                        <Link preserve-scroll method="post" href="/logout" as="button"
                            class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50">
                            Logout
                        </Link>
    
                    </div>
                </li>            
            </template>

            <template v-else>
                <li>
                    <Link preserve-scroll v-if="page.props.canLogin" :href="route('login')" class="text-white hover:text-[#D4AF37]">
                        Log in
                    </Link>
                </li>
                <li v-if="page.props.canRegister">
                    <Link preserve-scroll :href="route('register')" class="text-white hover:text-[#D4AF37]">
                        Become a Member
                    </Link>
                </li>
            </template>
        </ul>

        <!-- HAMBURGER -->
        <button class="md:hidden text-white" @click="open = !open">
            ☰
        </button>

        <!-- MOBILE MENU -->
        <div v-show="open" class="absolute top-[70px] left-0 w-full bg-[#001E3C] p-6 md:hidden max-h-[calc(70vh-70px)] overflow-y-auto">
            <ul class="flex flex-col gap-4 text-white">
                <li><Link preserve-scroll class="text-white hover:text-[#D4AF37]" href="/">Home</Link></li>
                <li>
                    <button
                        @click="aboutOpen = !aboutOpen"
                        class="w-full flex items-center justify-between text-white py-2"
                    >
                        <span>About</span>

                        <svg
                        class="w-3 h-3 transition-transform"
                        :class="aboutOpen ? 'rotate-180' : ''"
                        viewBox="0 0 10 6"
                        fill="none"
                        >
                        <path d="m1 1 4 4 4-4" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </button>

                    <div v-show="aboutOpen" class="mt-2 ml-3 flex flex-col bg-[#001E3C] rounded-lg overflow-hidden">
                        <Link preserve-scroll :href="route('about', 'president_message')" class="px-4 py-2 text-sm hover:text-[#D4AF37]">President Message</Link>
                        <Link preserve-scroll :href="route('about', 'vice_president_message')" class="px-4 py-2 text-sm hover:text-[#D4AF37]">Vice President Message</Link>
                        <Link preserve-scroll :href="route('about', 'about_seab')" class="px-4 py-2 text-sm hover:text-[#D4AF37]">About SEAB</Link>
                        <Link preserve-scroll :href="route('about', 'mission')" class="px-4 py-2 text-sm hover:text-[#D4AF37]">Mission</Link>
                        <Link preserve-scroll :href="route('about', 'vision')" class="px-4 py-2 text-sm hover:text-[#D4AF37]">Vision</Link>
                    </div>
                </li>
                <li>
                    <button
                        @click="memberOpen = !memberOpen"
                        class="w-full flex items-center justify-between text-white py-2"
                    >
                        <span>Membership</span>

                        <svg
                        class="w-3 h-3 transition-transform"
                        :class="memberOpen ? 'rotate-180' : ''"
                        viewBox="0 0 10 6"
                        fill="none"
                        >
                        <path d="m1 1 4 4 4-4" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </button>

                    <div v-show="memberOpen" class="mt-2 ml-3 flex flex-col bg-[#001E3C] rounded-lg overflow-hidden">
                        <Link preserve-scroll :href="route('member.list')" class="px-4 py-2 text-sm hover:text-[#D4AF37]">Membership List</Link>
                        <Link preserve-scroll :href="route('about', 'membership_process')" class="px-4 py-2 text-sm hover:text-[#D4AF37]">Membership Process</Link>
                        <Link class="px-4 py-2 text-sm hover:text-[#D4AF37]">Become a Member</Link>
                    </div>
                </li>
                
                <li>
                    <button
                        @click="committeeOpen = !committeeOpen"
                        class="w-full flex items-center justify-between text-white py-2"
                    >
                        <span>Committee</span>

                        <svg
                        class="w-3 h-3 transition-transform"
                        :class="committeeOpen ? 'rotate-180' : ''"
                        viewBox="0 0 10 6"
                        fill="none"
                        >
                        <path d="m1 1 4 4 4-4" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </button>
                    <div v-show="committeeOpen" class="mt-2 ml-3 flex flex-col bg-[#001E3C] rounded-lg overflow-hidden">
                        <Link
                            v-for="item in (page.props.committees_cache ?? [])"
                            :key="item.id"
                            preserve-scroll
                            :href="route('committee.details', { param: item.name.toLowerCase().replace(/\s+/g, '-'), id: item.id })"
                            class="px-4 py-2 text-sm hover:text-[#D4AF37]"
                        >
                            {{ item.name }}
                        </Link>
                        <div v-if="!(page.props.committees_cache?.length)" class="px-4 py-2 text-sm text-gray-400">
                            No committee found
                        </div>
                    </div>
                </li>  
                <li><Link preserve-scroll class="text-white hover:text-[#D4AF37]" href="#">Gallery</Link></li>                
                <li><Link preserve-scroll class="text-white hover:text-[#D4AF37]" :href="route('contact')">Contact</Link></li>
                <!-- USER -->
                <li v-if="$page.props.auth.user">

                    <button
                        @click="userOpen = !userOpen"
                        class="w-full flex items-center justify-between py-2 text-white"
                    >
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined">account_circle</span>
                            {{ $page.props.auth.user.name }}
                        </div>

                        <svg
                            class="w-3 h-3 transition-transform"
                            :class="userOpen ? 'rotate-180' : ''"
                            viewBox="0 0 10 6"
                        >
                            <path d="m1 1 4 4 4-4" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </button>

                    <div v-show="userOpen" class="ml-4 flex flex-col">
                        <Link preserve-scroll v-if="$page.props.auth?.user?.is_admin == 1 && ($page.props.auth?.user?.admin_role_id == 1 || $page.props.auth?.user?.admin_role_id == 2)" href="/admin_acess" class="py-2 text-sm hover:text-[#D4AF37]">
                            Admin Access
                        </Link>
                        <Link preserve-scroll href="/user/profile" class="py-2 text-sm hover:text-[#D4AF37]">Profile</Link>
                        <Link preserve-scroll href="/user/change-password" class="py-2 text-sm hover:text-[#D4AF37]">Change Password</Link>

                        <Link preserve-scroll method="post" href="/logout" as="button"
                            class="text-left py-2 text-sm text-red-400">
                            Logout
                        </Link>
                    </div>
                </li>
                <!-- GUEST -->
                <template v-else>
                    <li ><Link href="/login" class="hover:text-[#D4AF37]">Login</Link></li>
                    <li><Link href="/register" class="hover:text-[#D4AF37]">Become a Member</Link></li>
                </template>
            </ul>
        </div>
        </nav>
    </header>
</template>