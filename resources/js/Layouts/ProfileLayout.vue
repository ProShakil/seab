<template>

  <div class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <Header />
    <!-- PAGE WRAPPER (adds left/right spacing) -->
    <div class="flex-1 bg-gray-100">
      <div class="w-[98%] mx-auto md:max-w-7xl md:mx-auto md:px-6 lg:px-8 flex flex-col md:flex-row gap-2 py-6">
        <div class="md:hidden">
          <button 
            @click="open = !open"
            class="w-full bg-secondary text-white p-3 rounded-xl flex justify-between items-center"
          >
            <span>Users Panel</span>
            <span>{{ open ? '−' : '+' }}</span>
          </button>
        </div>
        <!-- LEFT SIDEBAR -->
        <aside :class="[
          'bg-secondary text-white p-4 rounded-xl shadow transition-all duration-300',
          'w-full md:w-64',
          open ? 'block' : 'hidden md:block'
        ]">

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
            <Link
                v-if="$page.props.showReunionLink"
                href = "/user/reunion"
                class="relative flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-50 border border-red-200 text-red-600 font-semibold shadow-sm hover:bg-red-100 transition"
                :class="$page.url.startsWith('/user/reunion')
                  ? 'bg-red-100 text-red-700 border border-red-300 ring-1 ring-red-200'
                  : 'bg-red-50 text-red-600 hover:bg-red-100 border border-red-200'"
                >
                <!-- blinking dot -->
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
                </span>

                <!-- text -->
                <span class="tracking-wide animate-pulse">
                  Reunion Open
                </span>

                <!-- badge -->
                <span class="ml-1 text-[10px] bg-red-600 text-white px-2 py-[2px] rounded-full">
                  LIVE
                </span>
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
import { ref } from 'vue'
const open = ref(false)
</script>