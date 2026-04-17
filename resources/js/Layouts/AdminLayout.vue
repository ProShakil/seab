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
            Admin Panel
          </h2>

          <nav class="flex flex-col gap-2">

            <Link href="/admin/committee-names" :class="$page.url.startsWith('/admin/committee-names') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Committee Name
            </Link>

            <Link href="/admin/committee-designation" :class="$page.url.startsWith('/admin/committee-designation') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Committee Designation
            </Link>

            <Link href="/admin/membership-type" :class="$page.url.startsWith('/admin/membership-type') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Membership Type
            </Link>

            <Link href="/admin/occupation" :class="$page.url.startsWith('/admin/occupation') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Occupation List
            </Link>

            <Link href="/admin/relationship" :class="$page.url.startsWith('/admin/relationship') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Relationship List
            </Link>

            <Link href="/admin/technology" :class="$page.url.startsWith('/admin/technology') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Technology List
            </Link>

            <Link href="/admin/users" :class="$page.url.startsWith('/admin/users') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              User List
            </Link>

            <Link href="/admin/front-message" :class="$page.url.startsWith('/admin/front-message') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Front Message
            </Link>
            <Link href="/admin/gallery" :class="$page.url.startsWith('/admin/gallery') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Gallery
            </Link>
            <Link href="/admin/committee_list" :class="$page.url.startsWith('/admin/committee_list') ? 'text-[#D4AF37] font-normal' : 'hover:text-[#D4AF37]'">
              Contact
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