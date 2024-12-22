<template>
    <header class="app-header">
        <nav class="navbar">
            <h1 class="navbar-title">Admin Dashboard</h1>
            <div class="user-menu">
                <i class="fas fa-user"></i>
                <span class="user-name">{{ userName }}</span>
                <div class="dropdown-menu">
                    <a @click.prevent="logout" class="logout-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            userName: "",
        };
    },
    methods: {
        async fetchUserName() {
            const token = localStorage.getItem("token");
            if (!token) {
                console.error("No token found. Unable to fetch user name.");
                return;
            }
            try {
                const response = await axios.get("/admin/index", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                });

                if (response.data.loggedInUser) {
                    this.userName = response.data.loggedInUser.name;
                } else {
                    console.error("loggedInUser is undefined in the response.");
                }
                this.users = response.data.users;
            } catch (error) {
                console.error(
                    "Failed to fetch user name:",
                    error.response?.data || error.message
                );
            }
        },
        async logout() {
            const token = localStorage.getItem("token");
            if (!token) {
                this.$router.push({ name: "admin.login" });
                return;
            }
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
    mounted() {
        this.fetchUserName();
    },
};
</script>

<style scoped>
.app-header {
    flex: 0 0 auto;
    background-color: #007bff;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.navbar-title {
    margin: 0;
    font-size: 1.5rem;
}

.user-menu {
    display: flex;
    align-items: center;
    position: relative;
    cursor: pointer;
}

.user-menu .user-name {
    margin-left: 10px;
}

.user-menu .dropdown-menu {
    display: none;
    position: absolute;
    background-color: white;
    color: black;
    right: 0;
    top: 100%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    overflow: hidden;
    z-index: 1000;
}

.user-menu:hover .dropdown-menu {
    display: block;
}

.logout-link {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    color: #343a40;
    text-decoration: none;
}

.logout-link i {
    margin-right: 0.5rem;
}

.logout-link:hover {
    background-color: #f1f1f1;
}
</style>
