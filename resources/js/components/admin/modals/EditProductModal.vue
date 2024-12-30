<template>
    <div
        class="modal fade"
        id="editProductModal"
        tabindex="-1"
        aria-labelledby="editProductModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <form @submit.prevent="updateProduct">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label"
                                >Product Name</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="editProductName"
                                v-model="productData.name"
                                required
                                maxlength="255"
                            />
                            <div v-if="errors.name" class="invalid-feedback">
                                {{ errors.name[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editProductCategory" class="form-label"
                                >Category</label
                            >
                            <v-select
                                v-model="productData.category"
                                :options="categories"
                                label="name"
                                :reduce="(category) => category.id"
                                placeholder="Search categories..."
                                required
                            />
                            <div
                                v-if="errors.category"
                                class="invalid-feedback"
                            >
                                {{ errors.category }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label
                                for="editProductDescription"
                                class="form-label"
                                >Description</label
                            >
                            <textarea
                                class="form-control"
                                id="editProductDescription"
                                v-model="productData.description"
                                required
                            ></textarea>
                            <div
                                v-if="errors.description"
                                class="invalid-feedback"
                            >
                                {{ errors.description[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label"
                                >Price</label
                            >
                            <input
                                type="number"
                                class="form-control"
                                id="editProductPrice"
                                v-model.number="productData.price"
                                required
                                min="1"
                            />
                            <div v-if="errors.price" class="invalid-feedback">
                                {{ errors.price[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editProductStock" class="form-label"
                                >Stock</label
                            >
                            <input
                                type="number"
                                class="form-control"
                                id="editProductStock"
                                v-model.number="productData.stock"
                                required
                                min="1"
                            />
                            <div v-if="errors.stock" class="invalid-feedback">
                                {{ errors.stock[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Images</label>
                            <div
                                v-if="
                                    productData.images &&
                                    productData.images.length
                                "
                            >
                                <div class="d-flex flex-wrap gap-2">
                                    <div
                                        v-for="image in productData.images"
                                        :key="image.id"
                                        class="position-relative"
                                    >
                                        <img
                                            :src="getImageUrl(image.image_path)"
                                            :alt="`Image ${image.id}`"
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
                                            @click="removeImage(image)"
                                            title="Delete Image"
                                        >
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <p>No images available.</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editProductImages" class="form-label"
                                >Upload New Images</label
                            >
                            <input
                                type="file"
                                class="form-control"
                                id="editProductImages"
                                multiple
                                @change="handleFileUpload"
                                accept="image/jpeg, image/png, image/gif, image/webp"
                            />
                            <div v-if="errors.images" class="invalid-feedback">
                                {{ errors.images[0] }}
                            </div>
                            <div v-if="newImages.length" class="mt-2">
                                <label class="form-label"
                                    >New Images Preview:</label
                                >
                                <div class="d-flex flex-wrap gap-2">
                                    <div
                                        v-for="(image, index) in newImages"
                                        :key="index"
                                        class="position-relative"
                                    >
                                        <img
                                            :src="createObjectURL(image)"
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
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="isUpdating"
                        >
                            <span
                                v-if="isUpdating"
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { Modal } from "bootstrap";
import VSelect from "vue-select";

export default {
    props: {
        product: Object,
    },
    components: {
        VSelect,
    },
    data() {
        return {
            productData: {
                id: null,
                name: "",
                category: {
                    id: null,
                    name: "",
                },
                description: "",
                price: 0,
                stock: 0,
                images: [],
            },
            categories: [],
            newImages: [],
            deletedImageIds: [],
            isUpdating: false,
            errors: {},
        };
    },
    watch: {
        product: {
            immediate: true,
            handler(newVal) {
                if (newVal) {
                    this.productData = {
                        ...newVal,
                        images: newVal.images || [],
                    };
                    this.deletedImageIds = [];
                }
            },
        },
    },
    mounted() {
        this.fetchCategories();
    },
    methods: {
        fetchCategories() {
            axios
                .get("/admin/category", {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                })
                .then((response) => {
                    this.categories = response.data.data;
                })
                .catch((error) => {
                    console.error("Error fetching categories:", error);
                });
        },
        validate() {
            this.errors = {};

            let isValid = true;

            if (!this.productData.name || this.productData.name.length > 255) {
                this.errors.name = "Name is required.";
                isValid = false;
            }

            if (!this.productData.category || !this.productData.category.id) {
                this.errors.category = "Category is required.";
                isValid = false;
            }

            if (
                !this.productData.description ||
                this.productData.description.length > 1000
            ) {
                this.errors.description = "Description is required.";
                isValid = false;
            }

            if (!this.productData.price || this.productData.price < 0) {
                this.errors.price = "Price is required.";
                isValid = false;
            }

            if (!this.productData.stock || this.productData.stock < 0) {
                this.errors.stock = "Stock is required.";
                isValid = false;
            }

            if (
                this.newImages.length === 0 &&
                this.deletedImageIds.length === 0
            ) {
                this.errors.images = "At least one image is required.";
                isValid = false;
            }

            return isValid;
        },
        async updateProduct() {
            if (!this.validate()) {
                return;
            }

            this.isUpdating = true;

            try {
                await this.updateProductDetails();

                if (this.newImages.length > 0) {
                    await this.insertNewImages();
                }

                this.$emit("productUpdated", this.productData);

                const editModal = Modal.getInstance(
                    document.getElementById("editProductModal")
                );
                editModal.hide();
                window.location.reload();
            } catch (error) {
                console.error(
                    "Failed to update product:",
                    error.response || error.message
                );
            } finally {
                this.isUpdating = false;
            }
        },
        updateProductDetails() {
            return axios.put(
                `/admin/product/${this.productData.id}`,
                {
                    id: this.productData.id,
                    name: this.productData.name,
                    category_id: this.productData.category.id,
                    description: this.productData.description,
                    price: this.productData.price,
                    stock: this.productData.stock,
                },
                {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                }
            );
        },
        insertNewImages() {
            const formData = new FormData();
            formData.append("product_id", this.productData.id);

            this.newImages.forEach((image, index) => {
                formData.append(`images[${index}]`, image);
            });

            return axios.post(
                `/admin/product/${this.productData.id}/images`,
                formData,
                {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
        },
        getImageUrl(imagePath) {
            const url = `${import.meta.env.VITE_API_URL}/storage/${imagePath}`;
            return url;
        },
        handleFileUpload(event) {
            this.newImages = Array.from(event.target.files);
        },
        removeNewImage(index) {
            this.newImages.splice(index, 1);
        },
        removeImage(image) {
            this.deletedImageIds.push(image.id);
            this.productData.images = this.productData.images.filter(
                (img) => img.id !== image.id
            );
        },
        createObjectURL(image) {
            return URL.createObjectURL(image);
        },
    },
};
</script>

<style scoped>
.invalid-feedback {
    display: block;
    color: red;
}
</style>
