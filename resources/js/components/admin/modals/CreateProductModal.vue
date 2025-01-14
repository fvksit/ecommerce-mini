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
                                maxlength="255"
                            />
                            <div v-if="errors.name" class="invalid-feedback">
                                {{ errors.name }}
                            </div>
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
                                maxlength="1000"
                            ></textarea>
                            <div
                                v-if="errors.description"
                                class="invalid-feedback"
                            >
                                {{ errors.description }}
                            </div>
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
                                min="1"
                                max="1000000000000"
                            />
                            <div v-if="errors.price" class="invalid-feedback">
                                {{ errors.price }}
                            </div>
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
                                min="1"
                                max="1000000000000"
                            />
                            <div v-if="errors.stock" class="invalid-feedback">
                                {{ errors.stock }}
                            </div>
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
                            <div
                                v-if="errors.category_id"
                                class="invalid-feedback"
                            >
                                {{ errors.category_id }}
                            </div>
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
                                required
                                accept="image/jpeg, image/png, image/gif, image/webp"
                            />
                            <div v-if="errors.images" class="invalid-feedback">
                                {{ errors.images }}
                            </div>
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
                                        :src="preview.src"
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
            errors: {},
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
            const validExtensions = [
                "image/jpeg",
                "image/png",
                "image/gif",
                "image/webp",
            ];

            const invalidFiles = files.filter(
                (file) => !validExtensions.includes(file.type)
            );

            if (invalidFiles.length > 0) {
                this.errors.images =
                    "Invalid image format. Only JPEG, PNG, GIF, and WEBP are allowed.";
                this.newImages = [];
                return;
            }

            const totalSize = files.reduce((sum, file) => sum + file.size, 0);
            const existingImagesSize = this.newImages.reduce(
                (sum, file) => sum + file.size,
                0
            );
            const combinedSize = totalSize + existingImagesSize;

            if (combinedSize > 2 * 1024 * 1024) {
                this.errors.images =
                    "Total size of images must not exceed 2MB.";
                return;
            }

            this.errors.images = "";
            this.newImages.push(...files);
            this.updateImagePreviews();
        },
        updateImagePreviews() {
            this.imagePreviews = this.newImages.map((file) => {
                const sizeInKB = (file.size / 1024).toFixed(2);
                return {
                    src: URL.createObjectURL(file),
                    sizeText: sizeInKB,
                };
            });
        },
        removeNewImage(index) {
            this.newImages.splice(index, 1);
            this.updateImagePreviews();
        },
        validate() {
            this.errors = {};

            let isValid = true;

            if (!this.newProduct.name || this.newProduct.name.length > 255) {
                this.errors.name = "Name is required.";
                isValid = false;
            }

            if (
                !this.newProduct.description ||
                this.newProduct.description.length > 1000
            ) {
                this.errors.description = "Description is required";
                isValid = false;
            }

            if (!this.newProduct.price || this.newProduct.price < 0) {
                this.errors.price = "Price must be greater than 0";
                isValid = false;
            }

            if (!this.newProduct.stock || this.newProduct.stock < 0) {
                this.errors.stock = "Stock must be greater than 0";
                isValid = false;
            }

            if (!this.newProduct.category_id) {
                this.errors.category_id = "Category is required.";
                isValid = false;
            }

            if (this.newImages.length === 0) {
                this.errors.images = "At least one image is required.";
                isValid = false;
            }

            return isValid;
        },
        async storeProduct() {
            if (!this.validate()) {
                return;
            }

            try {
                const formData = new FormData();
                formData.append("name", this.newProduct.name);
                formData.append("description", this.newProduct.description);
                formData.append("price", this.newProduct.price);
                formData.append("stock", this.newProduct.stock);
                formData.append("category_id", this.newProduct.category_id);

                this.newImages.forEach((image, index) => {
                    formData.append(`images[${index}]`, image);
                    formData.append(`images_size[${index}]`, image.size);
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
            this.newImages = [];
            this.imagePreviews = [];
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

<style scoped>
.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
}
</style>
