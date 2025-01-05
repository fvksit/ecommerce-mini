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
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                    </div>
                    <div class="table-container">
                        <table
                            id="products-table"
                            class="table table-striped table-bordered"
                        >
                            <thead>
                                <tr>
                                    <th class="number-column">No</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="products.length === 0">
                                    <td colspan="5" class="text-center">
                                        No products available.
                                    </td>
                                </tr>
                                <tr
                                    v-for="(product, index) in products"
                                    :key="product.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.category.name }}</td>
                                    <td>{{ product.stock }}</td>
                                    <td class="action-icons">
                                        <button
                                            @click="showDetail(product.id)"
                                            class="btn btn-info btn-sm"
                                        >
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <button
                                            @click="showUpdateModal(product)"
                                            class="btn btn-warning btn-sm"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button
                                            @click="confirmDelete(product.id)"
                                            class="btn btn-danger btn-sm"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <CreateProductModal @productCreated="fetchProducts" />
                    <EditProductModal
                        :product="currentProduct"
                        @productUpdated="onProductUpdated"
                    />
                    <DeleteProductModal
                        :productId="currentProductId"
                        @productDeleted="fetchProducts"
                    />
                    <DetailProductModal
                        :product="currentProduct"
                        v-if="showDetailModal"
                        @close="showDetailModal = false"
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
import {
    getProducts,
    getProductById,
    deleteProduct,
} from "../services/productService";
import $ from "jquery";
import "datatables.net-responsive";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import * as bootstrap from "bootstrap";

import CreateProductModal from "../modals/CreateProductModal.vue";
import EditProductModal from "../modals/EditProductModal.vue";
import DeleteProductModal from "../modals/DeleteProductModal.vue";
import DetailProductModal from "../modals/DetailProductModal.vue";
import Navbar from "../navbar/Navbar.vue";
import Sidebar from "../sidebar/Sidebar.vue";

export default {
    components: {
        CreateProductModal,
        EditProductModal,
        DeleteProductModal,
        DetailProductModal,
        Navbar,
        Sidebar,
    },
    data() {
        return {
            products: [],
            currentProduct: null,
            currentProductId: null,
            showDetailModal: false,
            isLoggedIn: true,
        };
    },
    async created() {
        await this.fetchProducts();
    },
    methods: {
        async fetchProducts() {
            try {
                const products = await getProducts();
                this.products = products.map((product) => {
                    if (!product.category) {
                        product.category = { name: "No Category" };
                    }
                    return product;
                });
                this.$nextTick(() => {
                    $("#products-table").DataTable();
                });
            } catch (error) {
                console.error("Error fetching products:", error);
            }
        },
        showCreateModal() {
            const createModal = new bootstrap.Modal(
                document.getElementById("createProductModal")
            );
            createModal.show();
        },
        async showDetail(productId) {
            try {
                this.currentProduct = await getProductById(productId);
                this.showDetailModal = true;
            } catch (error) {
                console.error("Error fetching product details:", error);
            }
        },
        showUpdateModal(product) {
            this.currentProduct = product;
            const editModal = new bootstrap.Modal(
                document.getElementById("editProductModal")
            );
            editModal.show();
        },
        onProductUpdated(updatedProduct) {
            const index = this.products.findIndex(
                (product) => product.id === updatedProduct.id
            );
            if (index !== -1) {
                this.products.splice(index, 1, updatedProduct);
            } else {
                this.products.push(updatedProduct);
            }
        },
        async confirmDelete(productId) {
            try {
                await deleteProduct(productId);
                this.products = this.products.filter(
                    (product) => product.id !== productId
                );
            } catch (error) {
                console.error("Error deleting product:", error);
            }
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

#products-table {
    width: 100%;
    margin: 0 auto;
    border-collapse: collapse;
}

#products-table th,
#products-table td {
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
