<template>
    <div id="app">
        <!-- Header atau navigasi global -->
        <header class="app-header">
            <nav class="navbar">
                <router-link v-if="isLoggedIn" to="/admin/dashboard"
                    >Dashboard</router-link
                >
                <router-link v-if="isLoggedIn" to="/admin/categories"
                    >Categories</router-link
                >
                <router-link v-if="isLoggedIn" to="/admin/products"
                    >Products</router-link
                >
                <router-link v-if="isLoggedIn" to="/admin/orders"
                    >Orders</router-link
                >
                <button v-if="isLoggedIn" @click="logout" class="btn-logout">
                    Logout
                </button>
            </nav>
        </header>

        <!-- Router View untuk menampilkan komponen sesuai dengan rute -->
        <main class="app-main">
            <router-view></router-view>
        </main>

        <!-- Footer global -->
        <footer class="app-footer">
            <p>&copy; 2024 Admin Panel</p>
        </footer>
    </div>
</template>

<script>
import axios from "axios";

export default {
    computed: {
        isLoggedIn() {
            return !!localStorage.getItem("token");
        },
    },
    methods: {
        async logout() {
            const token = localStorage.getItem("token");
            if (!token) {
                console.error("No token found. Redirecting to login.");
                this.$router.push({ name: "admin.login" });
                return;
            }

            console.log("Token from localStorage:", token);
            try {
                await axios.post(
                    "/admin/logout",
                    {},
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    }
                );
                // Remove token from localStorage after successful logout
                localStorage.removeItem("token");
                this.$router.push({ name: "admin.login" });
            } catch (error) {
                console.error(
                    "Logout failed:",
                    error.response?.data || error.message
                );
            }
        },
    },
};
</script>

<style scoped>
/* General Styling */
body {
    font-family: "Arial", sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Header Styling */
.app-header {
    background-color: #007bff;
    padding: 10px 20px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar {
    display: flex;
    gap: 10px;
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.navbar a:hover {
    background-color: #0056b3;
}

.btn-logout {
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-logout:hover {
    background-color: #c82333;
}

/* Main Content Styling */
.app-main {
    padding: 20px;
}

/* Footer Styling */
.app-footer {
    background-color: #f1f1f1;
    text-align: center;
    padding: 10px;
    position: absolute;
    bottom: 0;
    width: 100%;
}
</style>
