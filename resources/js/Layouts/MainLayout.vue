<template>
  <div class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <Header />

    <!-- PAGE CONTENT -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- FOOTER -->
    <Footer />

  </div>
</template>

<script setup>
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'
</script>