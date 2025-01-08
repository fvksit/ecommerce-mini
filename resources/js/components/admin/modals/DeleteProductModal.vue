<template>
    <div
        class="modal fade"
        id="deleteProductModal"
        tabindex="-1"
        aria-labelledby="deleteProductModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Product</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                    <button
                        @click="deleteProduct"
                        class="btn btn-danger w-100 mb-2"
                    >
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <button
                        type="button"
                        class="btn btn-secondary w-100"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>
                    <div
                        v-if="successMessage"
                        class="mt-3 alert alert-success alert-dismissible fade show"
                        role="alert"
                    >
                        <i class="fas fa-check-circle"></i>
                        {{ successMessage }}
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"
                        ></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { Modal } from "bootstrap";

export default {
    props: {
        productId: Number,
    },
    data() {
        return {
            isModalVisible: false,
            successMessage: "",
        };
    },
    methods: {
        showModal() {
            const modalElement = document.getElementById("deleteProductModal");
            const modal = new Modal(modalElement);
            modal.show();
        },
        hideModal() {
            const modalElement = document.getElementById("deleteProductModal");
            const modal = Modal.getInstance(modalElement);
            modal.hide();
        },
        async deleteProduct() {
            try {
                await axios.delete(`/admin/product/${this.productId}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });

                this.successMessage = "Product deleted successfully!";
                this.$emit("productDeleted");
                setTimeout(() => {
                    this.hideModal();
                    this.successMessage = "";
                }, 2000);
            } catch (error) {
                console.error("Error deleting product:", error);
            }
        },
    },
};
</script>

<style scoped>
.modal-content {
    background-color: #fff;
    border-radius: 8px;
    border: 1px solid #ddd;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.modal-header {
    background-color: #f7f7f7;
    border-bottom: 1px solid #e0e0e0;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
}

.modal-body {
    padding: 1.5rem;
}

.modal-body p {
    font-size: 1.1rem;
    color: #333;
}

.btn {
    font-size: 1rem;
    padding: 0.75rem;
    text-transform: uppercase;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #c82333;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

.alert {
    padding: 1rem;
    font-size: 1.1rem;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert i {
    margin-right: 10px;
}

.alert-dismissible .btn-close {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: transparent;
    border: none;
    font-size: 1.25rem;
    opacity: 0.7;
}

.alert-dismissible .btn-close:hover {
    opacity: 1;
}

.w-100 {
    width: 100%;
}

@media (max-width: 576px) {
    .modal-dialog {
        max-width: 100%;
        margin: 0.5rem;
    }

    .modal-content {
        border-radius: 8px;
    }
}
</style>
