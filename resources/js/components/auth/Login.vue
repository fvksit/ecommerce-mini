<template>
    <div class="login-form">
        <h2>Login</h2>

        <!-- Tampilkan error jika ada -->
        <div v-if="error" class="error">{{ error }}</div>

        <form @submit.prevent="submitForm">
            <div>
                <label for="email">Email:</label>
                <input type="email" v-model="email" required />
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" v-model="password" required />
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            email: "",
            password: "",
            error: null,
        };
    },
    methods: {
        async submitForm() {
            try {
                // Menggunakan URL yang disuntikkan ke dalam window.Laravel.routes.login
                const response = await axios.post(window.Laravel.routes.login, {
                    email: this.email,
                    password: this.password,
                });

                // Jika login berhasil, arahkan pengguna ke dashboard
                this.$router.push("/admin/dashboard");
            } catch (error) {
                console.error("Login Error", error);
                // Cek pesan error yang dikembalikan dari backend
                if (error.response && error.response.data) {
                    this.error =
                        error.response.data.message ||
                        "Invalid credentials or something went wrong!";
                } else {
                    this.error = "Network error or server error!";
                }
            }
        },
    },
};
</script>
