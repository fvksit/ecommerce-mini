<template>
    <div v-if="isLoggedIn" class="dashboard-container">
        <header class="app-header">
            <nav class="navbar">
                <h1 class="navbar-title">Admin Dashboard</h1>
                <button @click="logout" class="btn-logout">Logout</button>
            </nav>
        </header>
        <div class="content">
            <aside class="sidebar">
                <ul class="dashboard-menu">
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
            </aside>
            <main class="main-content">
                <router-view></router-view>
            </main>
        </div>
    </div>
    <div v-else>
        <router-view></router-view>
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
.dashboard-container {
    display: flex;
    flex-direction: column;
    max-width: 1200px;
    margin: 0 auto;
}

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
    justify-content: space-between;
    width: 100%;
}

.navbar-title {
    margin: 0;
}

.btn-logout {
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-left: auto;
}

.btn-logout:hover {
    background-color: #c82333;
}

.content {
    display: flex;
    margin-top: 20px;
}

.sidebar {
    width: 250px;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.dashboard-menu {
    list-style: none;
    padding: 0;
}

.dashboard-menu li {
    margin-bottom: 10px;
}

.dashboard-menu a {
    text-decoration: none;
    color: #007bff;
    padding: 10px 15px;
    display: block;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.dashboard-menu a:hover {
    background-color: #e9ecef;
}

.main-content {
    flex-grow: 1;
    padding: 20px;
}

@media (max-width: 768px) {
    .sidebar {
        display: none;
    }

    .app-main {
        margin-left: 0;
    }

    .navbar .nav-links {
        display: flex;
        flex-direction: column;
        background-color: #007bff;
        position: absolute;
        top: 60px;
        left: 0;
        width: 100%;
        padding: 10px;
    }

    .navbar .nav-links a {
        margin: 5px 0;
    }
}
</style>
