<template>
    <div
        class="modal fade"
        id="createProductModal"
        tabindex="-1"
        aria-labelledby="createProductModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Product</h5>
                    <button
                        type="button"
                        class="btn-close"
                        @click="closeModal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="storeProduct">
                        <div class="mb-3">
                            <label for="productName" class="form-label"
                                >Product Name</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="productName"
                                v-model="newProduct.name"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="productDescription" class="form-label"
                                >Description</label
                            >
                            <textarea
                                class="form-control"
                                id="productDescription"
                                v-model="newProduct.description"
                                rows="3"
                                required
                            ></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label"
                                >Price</label
                            >
                            <input
                                type="number"
                                class="form-control"
                                id="productPrice"
                                v-model="newProduct.price"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="productStock" class="form-label"
                                >Stock</label
                            >
                            <input
                                type="number"
                                class="form-control"
                                id="productStock"
                                v-model="newProduct.stock"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label"
                                >Category</label
                            >
                            <v-select
                                v-model="newProduct.category_id"
                                :options="categories"
                                label="name"
                                :reduce="(category) => category.id"
                                placeholder="Search categories..."
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="productImages" class="form-label"
                                >Upload Images</label
                            >
                            <input
                                type="file"
                                class="form-control"
                                id="productImages"
                                multiple
                                @change="handleFileUpload"
                            />
                            <div
                                v-if="imagePreviews.length > 0"
                                class="d-flex flex-wrap gap-2 mt-2"
                            >
                                <div
                                    v-for="(preview, index) in imagePreviews"
                                    :key="index"
                                    class="position-relative"
                                >
                                    <img
                                        :src="preview"
                                        :alt="`New Image ${index + 1}`"
                                        class="img-thumbnail"
                                        style="
                                            width: 100px;
                                            height: 100px;
                                            object-fit: cover;
                                        "
                                    />
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                        @click="removeNewImage(index)"
                                        title="Remove Image"
                                    >
                                        &times;
                                    </button>
                                </div>
                            </div>
                            <div v-else>
                                <p>No images selected.</p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { Modal } from "bootstrap";
import VSelect from "vue-select";

export default {
    components: {
        VSelect,
    },
    data() {
        return {
            newProduct: {
                name: "",
                description: "",
                price: 0,
                stock: 0,
                category_id: null,
                images: [],
            },
            categories: [],
            searchTerm: "",
            newImages: [],
            imagePreviews: [],
            showModal: false,
        };
    },
    async created() {
        try {
            const response = await axios.get("/admin/category", {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });
            this.categories = response.data.data;
        } catch (error) {
            console.error("Error fetching categories:", error);
        }
    },
    computed: {
        filteredCategories() {
            if (!this.searchTerm) {
                return this.categories;
            }
            return this.categories.filter((category) =>
                category.name
                    .toLowerCase()
                    .includes(this.searchTerm.toLowerCase())
            );
        },
    },
    methods: {
        handleFileUpload(event) {
            const files = Array.from(event.target.files);
            this.newImages.push(...files);
            this.updateImagePreviews();
        },
        updateImagePreviews() {
            this.imagePreviews = this.newImages.map((image) =>
                URL.createObjectURL(image)
            );
        },
        removeNewImage(index) {
            this.newImages.splice(index, 1);
            this.updateImagePreviews();
        },
        async storeProduct() {
            try {
                const formData = new FormData();
                formData.append("name", this.newProduct.name);
                formData.append("description", this.newProduct.description);
                formData.append("price", this.newProduct.price);
                formData.append("stock", this.newProduct.stock);
                formData.append("category_id", this.newProduct.category_id);

                this.newImages.forEach((image, index) => {
                    formData.append(`images[${index}]`, image);
                });

                const response = await axios.post("/admin/product", formData, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                        "Content-Type": "multipart/form-data",
                    },
                });

                this.$emit("productCreated", response.data);
                this.resetForm();
                this.closeModal();
                window.location.reload();
            } catch (error) {
                console.error("Error creating product:", error);
            }
        },
        resetForm() {
            this.newProduct = {
                name: "",
                description: "",
                price: 0,
                stock: 0,
                category_id: null,
                images: [],
            };
            this.searchTerm = "";
        },
        closeModal() {
            this.showModal = false;
            const modalElement = document.getElementById("createProductModal");
            const modal = Modal.getInstance(modalElement);
            modal.hide();
        },
    },
};
</script>

<style></style>
