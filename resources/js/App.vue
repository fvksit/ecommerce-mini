<template>
    <div id="app">
        <!-- Router View to display the component based on the route -->
        <main class="app-main">
            <router-view></router-view>
        </main>
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
    justify-content: space-between;
    width: 100%;
}

.nav-links {
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

/* Sidebar Styling */
.sidebar {
    display: none;
    flex-direction: column;
    background-color: #333;
    padding: 20px;
    position: fixed;
    top: 60px; /* Adjust based on header height */
    left: 0;
    height: 100%;
    width: 200px;
    z-index: 1000;
}

.sidebar a {
    color: white;
    text-decoration: none;
    padding: 10px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.sidebar a:hover {
    background-color: #575757;
}

@media (max-width: 768px) {
    .sidebar {
        display: flex;
    }

    .app-main {
        margin-left: 200px; /* Adjust based on sidebar width */
    }

    .navbar .nav-links {
        display: none;
    }
}
</style>
