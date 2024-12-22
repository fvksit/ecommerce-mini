<template>
    <div v-if="isLoggedIn" class="dashboard-container">
        <Navbar />
        <div class="content">
            <Sidebar />
            <main class="main-content">
                <div class="canvas-container">
                    <div class="table-actions">
                        <button
                            @click="showCreateModal"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-plus"></i> Add Category
                        </button>
                    </div>
                    <canvas id="background-canvas"></canvas>
                    <div class="table-container">
                        <table
                            id="categories-table"
                            class="table table-striped table-bordered"
                        >
                            <thead>
                                <tr>
                                    <th class="number-column">No</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(category, index) in categories"
                                    :key="category.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ category.name }}</td>
                                    <td class="action-icons">
                                        <button
                                            @click="showEditModal(category)"
                                            class="btn btn-warning btn-sm"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button
                                            @click="deleteCategory(category.id)"
                                            class="btn btn-danger btn-sm"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Create Category Modal -->
                    <CreateCategoryModal @categoryCreated="fetchCategories" />

                    <!-- Edit Category Modal -->
                    <EditCategoryModal
                        :category="currentCategory"
                        @categoryUpdated="updateCategoryList"
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
import { Modal } from "bootstrap";
import $ from "jquery";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import "datatables.net-bs5";
import "datatables.net";

import CreateCategoryModal from "../modals/CreateCategoryModal.vue";
import EditCategoryModal from "../modals/EditCategoryModal.vue";
import Navbar from "../navbar/Navbar.vue";
import Sidebar from "../sidebar/Sidebar.vue";

export default {
    components: {
        CreateCategoryModal,
        EditCategoryModal,
        Navbar,
        Sidebar,
    },
    data() {
        return {
            categories: [],
            currentCategory: null,
            isLoggedIn: true,
        };
    },
    mounted() {
        if (this.isLoggedIn) {
            this.fetchCategories();
        }
    },
    methods: {
        async fetchCategories() {
            try {
                const response = await axios.get("/admin/category", {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
                this.categories = response.data.data;
                this.initDataTable();
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },
        initDataTable() {
            this.$nextTick(() => {
                $("#categories-table").DataTable();
            });
        },
        destroyDataTable() {
            if ($.fn.DataTable.isDataTable("#categories-table")) {
                $("#categories-table").DataTable().destroy();
            }
        },
        showCreateModal() {
            const createModal = new Modal(
                document.getElementById("createCategoryModal")
            );
            createModal.show();
        },
        showEditModal(category) {
            this.currentCategory = category;
            const editModal = new Modal(
                document.getElementById("editCategoryModal")
            );
            editModal.show();
        },
        updateCategoryList(updatedCategory) {
            const index = this.categories.findIndex(
                (category) => category.id === updatedCategory.id
            );
            this.categories.splice(index, 1, updatedCategory);
            this.destroyDataTable();
            this.initDataTable();
        },
        deleteCategory(id) {
            axios
                .delete(`/admin/category/${id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                })
                .then(() => {
                    this.categories = this.categories.filter(
                        (category) => category.id !== id
                    );
                    this.destroyDataTable();
                    this.initDataTable();
                })
                .catch((error) => {
                    console.error("Error deleting category:", error);
                });
        },
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
.dt-center {
    text-align: center;
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
