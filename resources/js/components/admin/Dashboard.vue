<template>
    <div>
        <h1>Admin Dashboard</h1>
        <button @click="logout">Logout</button>
        <ul>
            <li>
                <router-link :to="{ name: 'admin.categories' }"
                    >Manage Categories</router-link
                >
            </li>
            <li>
                <router-link :to="{ name: 'admin.products' }"
                    >Manage Products</router-link
                >
            </li>
            <li>
                <router-link :to="{ name: 'admin.orders' }"
                    >Manage Orders</router-link
                >
            </li>
        </ul>
    </div>
</template>

<script>
import axios from "axios";

export default {
    methods: {
        async logout() {
            const token = localStorage.getItem("token");
            if (!token) {
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
/* Tambahkan styling yang diperlukan di sini */
</style>
