<template>
    <div class="flex flex-col min-h-screen bg-gray-100">
        <!-- Navbar -->
        <nav class="bg-blue-600 text-white p-4 shadow-lg">
            <div class="container mx-auto flex justify-between items-center">
                <!-- Logo with image -->
                <img alt="Logo" class="w-10 h-10 mr-2" />
                <span
                    class="text-3xl font-bold hover:text-blue-200 transition duration-200"
                    >Your Company</span
                >

                <!-- Desktop Menu -->
                <ul class="hidden md:flex space-x-6">
                    <!-- Jika Belum Login -->
                    <li v-if="!isLoggedIn">
                        <router-link
                            to="/admin/login"
                            class="hover:text-yellow-300 transition duration-200"
                            >Login</router-link
                        >
                    </li>

                    <!-- Jika Sudah Login -->
                    <li v-else>
                        <button
                            @click="handleLogout"
                            class="hover:text-red-300 transition duration-200"
                        >
                            Logout
                        </button>
                    </li>
                </ul>

                <!-- Mobile Menu Icon -->
                <div class="md:hidden">
                    <button
                        @click="toggleMobileMenu"
                        class="text-white focus:outline-none"
                    >
                        ☰
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div
                v-if="isMobileMenuOpen"
                class="md:hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-10"
            >
                <div class="flex justify-end p-4">
                    <button
                        @click="toggleMobileMenu"
                        class="text-white text-3xl"
                    >
                        &times;
                    </button>
                </div>
                <div
                    class="flex flex-col items-center text-white space-y-6 mt-12"
                >
                    <!-- Jika Belum Login -->
                    <router-link
                        v-if="!isLoggedIn"
                        to="/admin/login"
                        class="text-lg"
                    >
                        Login
                    </router-link>

                    <!-- Jika Sudah Login -->
                    <button
                        v-else
                        @click="handleLogout"
                        class="text-lg hover:text-red-300"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 container mx-auto p-4">
            <router-view></router-view>
        </main>

        <!-- Footer -->
        <footer class="bg-blue-600 text-white p-4 mt-10">
            <div class="container mx-auto text-center">
                <p>&copy; 2024 Your Company. All Rights Reserved.</p>
                <div class="flex justify-center space-x-4 mt-4">
                    <a href="#" class="text-white hover:text-blue-300">
                        <img alt="Facebook" class="w-6 h-6" />
                    </a>
                    <a href="#" class="text-white hover:text-blue-300">
                        <img alt="Twitter" class="w-6 h-6" />
                    </a>
                    <a href="#" class="text-white hover:text-blue-300">
                        <img alt="LinkedIn" class="w-6 h-6" />
                    </a>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isMobileMenuOpen: false, // Untuk menu mobile
            isLoggedIn: false, // Status login pengguna
        };
    },
    created() {
        this.checkLoginStatus(); // Cek status login saat komponen dibuat
    },
    methods: {
        toggleMobileMenu() {
            this.isMobileMenuOpen = !this.isMobileMenuOpen; // Toggle menu mobile
        },
        checkLoginStatus() {
            // Cek apakah pengguna memiliki token (login status)
            this.isLoggedIn = !!localStorage.getItem("token"); // Jika ada token, isLoggedIn = true
        },
        handleLogout() {
            // Hapus token login
            localStorage.removeItem("token");
            this.isLoggedIn = false; // Set status login ke false
            this.$router.push("/admin/login"); // Redirect ke halaman login
        },
    },
};
</script>

<style scoped>
/* Additional custom styles for responsive design */
@media (max-width: 768px) {
    /* Mobile Menu */
    .mobile-menu {
        background-color: rgba(0, 0, 0, 0.5);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
}
</style>
