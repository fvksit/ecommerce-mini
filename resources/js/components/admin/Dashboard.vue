<template>
    <div v-if="isLoggedIn" class="dashboard-container">
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
                <div class="canvas-container">
                    <div class="table-actions">
                        <button
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#addUserModal"
                        >
                            Add User
                        </button>
                    </div>
                    <canvas id="background-canvas"></canvas>
                    <div class="table-container">
                        <table
                            id="users-table"
                            class="table table-striped table-bordered"
                        >
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(user, index) in users"
                                    :key="user.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td class="action-icons">
                                        <button
                                            @click="showDetail(user.id)"
                                            class="btn btn-info btn-sm"
                                        >
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <button
                                            @click="showUpdate(user)"
                                            class="btn btn-warning btn-sm"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <AddUserModal @userAdded="fetchUsers" />
                    <ModalDetail
                        :user="selectedUser"
                        v-if="showDetailModal"
                        @close="showDetailModal = false"
                    />

                    <ModalUpdate
                        :user="selectedUser"
                        v-if="showUpdateModal"
                        @close="showUpdateModal = false"
                        @userUpdated="fetchUsers"
                    />
                </div>
            </main>
        </div>
    </div>
    <div v-else>
        <router-view></router-view>
    </div>
</template>

<script>
import axios from "axios";
import $ from "jquery";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import "datatables.net-bs5";
import AddUserModal from "./modals/AddUserModal.vue";
import ModalDetail from "./modals/DetailUserModal.vue";
import ModalUpdate from "./modals/UpdateUserModal.vue";

export default {
    components: {
        AddUserModal,
        ModalDetail,
        ModalUpdate,
    },
    data() {
        return {
            userName: "",
            users: [],
            selectedUser: null,
            showDetailModal: false,
            showUpdateModal: false,
            isLoggedIn: true,
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
                this.initDataTable();
                this.users = response.data.users;
            } catch (error) {
                console.error(
                    "Failed to fetch user name:",
                    error.response?.data || error.message
                );
            }
        },
        async fetchUsers() {
            const token = localStorage.getItem("token");
            if (!token) {
                console.error("No token found. Unable to fetch users.");
                return;
            }
            try {
                const response = await axios.get("/admin/index", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                });

                this.users = response.data.users;
                this.initDataTable();
            } catch (error) {
                console.error("Failed to fetch users:", error);
            }
        },
        initDataTable() {
            this.$nextTick(() => {
                // Destroy previous DataTable if already initialized
                if ($.fn.dataTable.isDataTable("#users-table")) {
                    $("#users-table").DataTable().destroy();
                }

                $("#users-table").DataTable({
                    dom:
                        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                        '<"row"<"col-sm-12"tr>>' +
                        '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    pagingType: "simple_numbers",
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                    },
                    responsive: true,
                    autoWidth: false,
                    lengthMenu: [10, 50, 100],
                    columnDefs: [{ targets: "_all", className: "dt-center" }],
                });
            });
        },
        async showDetail(userId) {
            const token = localStorage.getItem("token");
            if (!token) {
                console.error("No token found. Unable to fetch user details.");
                return;
            }
            try {
                const response = await axios.get(`/admin/index/${userId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                });
                this.selectedUser = response.data.data;
                this.showDetailModal = true;
            } catch (error) {
                console.error("Failed to fetch user details:", error);
            }
        },
        showUpdate(user) {
            this.selectedUser = user;
            this.showUpdateModal = true;
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
        drawCanvas() {
            const canvas = document.getElementById("background-canvas");
            const context = canvas.getContext("2d");
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            context.fillStyle = "rgba(200, 200, 200, 0.2)";
            context.fillRect(0, 0, canvas.width, canvas.height);
        },
    },
    mounted() {
        if (this.isLoggedIn) {
            this.fetchUsers();
            this.fetchUserName();
            this.drawCanvas();
            window.addEventListener("resize", this.drawCanvas);
        }
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.drawCanvas);
    },
};
</script>

<style scoped>
.dashboard-container {
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow: hidden;
}

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

.main-content {
    flex-grow: 1;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}

.canvas-container {
    position: relative;
}

.table-actions {
    margin: 1rem 0;
}

.action-icons button {
    margin-right: 5px;
}

.table-container {
    position: relative;
    z-index: 1;
    background: white;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#background-canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
}

.table-actions {
    position: relative;
    z-index: 2;
}

#users-table {
    width: 100%;
    margin: 0 auto;
    border-collapse: collapse;
}
.dt-center {
    text-align: center;
}

.content {
    display: flex;
    flex-grow: 1;
    overflow: hidden;
}

.sidebar {
    width: 250px;
    background-color: #333;
    color: white;
    padding: 20px;
    box-sizing: border-box;
    overflow-y: auto;
}

.sidebar .dashboard-menu {
    list-style: none;
    padding: 0;
}

.sidebar .dashboard-menu li {
    margin-bottom: 10px;
}

.sidebar .dashboard-menu a {
    color: white;
    text-decoration: none;
    font-size: 18px;
}

.sidebar .dashboard-menu a:hover {
    background-color: #575757;
    display: block;
    padding: 10px;
    border-radius: 5px;
}

.modal-backdrop {
    z-index: 1040 !important;
}
</style>
