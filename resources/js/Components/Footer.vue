<template>
  <footer class="bg-slate-900 text-white">

    <!-- MAIN FOOTER -->
    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">

      <!-- COLUMN 1 -->
      <div>
        <h2 class="text-lg font-semibold mb-3">SEAB</h2>
        <p class="text-sm text-gray-300">
          Sarishabari Engineers Association Bangladesh is a professional platform for engineers.
        </p>
      </div>

      <!-- COLUMN 2 -->
      <div>
        <h2 class="text-lg font-semibold mb-3">Quick Links</h2>
        <ul class="space-y-2 text-sm text-gray-300">
          <li><a href="#" class="hover:text-[#D4AF37]">Home</a></li>
          <li><a href="#" class="hover:text-[#D4AF37]">About</a></li>
          <li><a href="#" class="hover:text-[#D4AF37]">Committee</a></li>
          <li><a href="#" class="hover:text-[#D4AF37]">Contact</a></li>
        </ul>
      </div>

      <!-- COLUMN 3 -->
      <div>
        <h2 class="text-lg font-semibold mb-3">Membership</h2>
        <ul class="space-y-2 text-sm text-gray-300">
          <li><a href="#" class="hover:text-[#D4AF37]">Membership List</a></li>
          <li><a href="#" class="hover:text-[#D4AF37]">Process</a></li>
          <li><a href="#" class="hover:text-[#D4AF37]">Join Now</a></li>
        </ul>
      </div>

      <!-- COLUMN 4 -->
      <div>
        <h2 class="text-lg font-semibold mb-3">Contact</h2>
        <ul class="space-y-2 text-sm text-gray-300">
          <li>Email: info@seab.org</li>
          <li>Phone: +880 1XXXXXXXXX</li>
          <li>Location: Bangladesh</li>
        </ul>
      </div>

    </div>

    <!-- COPYRIGHT -->
    <div class="border-t border-white/10 py-4 text-center text-sm text-gray-400">
      © {{ year }} SEAB Engineering Association. All Rights Reserved.
    </div>

  </footer>
</template>

<script setup>
const year = new Date().getFullYear()
</script>