<template>
    <div v-if="isLoggedIn" class="dashboard-container">
        <Navbar />
        <div class="content">
            <Sidebar />
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
                    <div class="table-wrapper">
                        <div class="table-container">
                            <table
                                id="users-table"
                                class="table table-striped table-bordered"
                            >
                                <thead>
                                    <tr>
                                        <th class="number-column">No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="users.length === 0">
                                        <td colspan="4" class="text-center">
                                            No users available.
                                        </td>
                                    </tr>
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
                                                <i
                                                    class="fas fa-info-circle"
                                                ></i>
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
import { fetchUsers, fetchUserDetails } from "./services/userService";
import $ from "jquery";
import AddUserModal from "./modals/AddUserModal.vue";
import ModalDetail from "./modals/DetailUserModal.vue";
import ModalUpdate from "./modals/UpdateUserModal.vue";
import Navbar from "./navbar/Navbar.vue";
import Sidebar from "./sidebar/Sidebar.vue";

export default {
    components: {
        AddUserModal,
        ModalDetail,
        ModalUpdate,
        Navbar,
        Sidebar,
    },
    data() {
        return {
            users: [],
            selectedUser: null,
            showDetailModal: false,
            showUpdateModal: false,
            isLoggedIn: true,
        };
    },
    methods: {
        async fetchUsers() {
            const token = localStorage.getItem("token");
            try {
                this.users = await fetchUsers(token);
                this.$nextTick(() => {
                    $("#users-table").DataTable();
                });
            } catch (error) {
                console.error("Failed to fetch users:", error);
            }
        },
        async showDetail(userId) {
            const token = localStorage.getItem("token");
            try {
                this.selectedUser = await fetchUserDetails(userId, token);
                this.showDetailModal = true;
            } catch (error) {
                console.error("Failed to fetch user details:", error);
            }
        },
        showUpdate(user) {
            this.selectedUser = user;
            this.showUpdateModal = true;
        },
    },
    mounted() {
        if (this.isLoggedIn) {
            this.fetchUsers();
        }
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

.main-content {
    flex-grow: 1;
    padding: 10px;
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
    position: relative;
    z-index: 2;
}

.action-icons button {
    margin-right: 5px;
}

.table-wrapper {
    display: flex;
    justify-content: center;
    padding: 10px;
}

.table-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
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

#users-table {
    width: 100%;
    margin: 0 auto;
    border-collapse: collapse;
}

#users-table th,
#users-table td {
    text-align: left;
}

.content {
    display: flex;
    flex-grow: 1;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

.number-column {
    width: 5px;
    text-align: center;
}

.modal-backdrop {
    z-index: 1040 !important;
}
</style>
