<template>

  <div class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <Header />

    <!-- PAGE WRAPPER (adds left/right spacing) -->
    <div class="flex-1 bg-gray-100">

      <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 flex gap-6 py-6">

        <!-- LEFT SIDEBAR -->
        <aside class="w-64 bg-secondary text-white p-4 rounded-xl shadow hidden md:block">

          <h2 class="text-lg font-bold mb-4 text-[#D4AF37]">
            Users Panel
          </h2>

          <nav class="flex flex-col gap-2">

            <Link href="/user/profile" :class="$page.url.startsWith('/user/profile') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              View Profile
            </Link>

            <Link href="/user/membership-form" :class="$page.url.startsWith('/user/membership-form') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Membership Form
            </Link>

            <Link href="/user/change-password" :class="$page.url.startsWith('/user/change-password') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Change Password
            </Link>   
          </nav>
        </aside>

        <!-- RIGHT CONTENT -->
        <main class="flex-1 bg-white rounded-xl shadow p-6">

          <slot />

        </main>

      </div>
    </div>

    <!-- FOOTER -->
    <Footer />

  </div>
</template>

<script setup>
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'
import { Link } from '@inertiajs/vue3'
</script>