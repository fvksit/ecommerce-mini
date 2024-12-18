<template>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

            <!-- Tampilkan error jika ada -->
            <div v-if="error" class="text-red-500 mb-4 text-center">
                {{ error }}
            </div>

            <!-- Form Register -->
            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                        >Name</label
                    >
                    <input
                        v-model="name"
                        type="text"
                        id="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                        >Email</label
                    >
                    <input
                        v-model="email"
                        type="email"
                        id="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="password"
                        class="block text-sm font-medium text-gray-700"
                        >Password</label
                    >
                    <input
                        v-model="password"
                        type="password"
                        id="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="password_confirmation"
                        class="block text-sm font-medium text-gray-700"
                        >Confirm Password</label
                    >
                    <input
                        v-model="password_confirmation"
                        type="password"
                        id="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        required
                    />
                </div>
                <div class="mb-6">
                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200"
                    >
                        Register
                    </button>
                </div>
                <div class="text-center">
                    <router-link
                        to="/admin.login"
                        class="text-blue-600 hover:text-blue-500"
                        >Already have an account? Login here</router-link
                    >
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Register",
    data() {
        return {
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
            error: null,
        };
    },
    methods: {
        async submitForm() {
            try {
                const response = await axios.post(
                    window.Laravel.routes.register,
                    {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                    }
                );
                this.$router.push("/admin.login");
            } catch (error) {
                console.error("Registration Error", error);
                if (error.response && error.response.data) {
                    this.error =
                        error.response.data.message ||
                        "Something went wrong during registration";
                } else {
                    this.error = "Network error or server error!";
                }
            }
        },
    },
};
</script>

<style scoped>
/* Add some styling for better form layout */
</style>
