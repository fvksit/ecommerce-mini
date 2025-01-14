<template>
    <div
        class="modal fade"
        id="createCategoryModal"
        tabindex="-1"
        aria-labelledby="createCategoryModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">
                        Create Category
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="createCategory">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label"
                                >Category Name</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="categoryName"
                                v-model="newCategory.name"
                                :class="{ 'is-invalid': errors.name }"
                                required
                                maxlength="255"
                            />
                            <div v-if="errors.name" class="invalid-feedback">
                                {{ errors.name }}
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

export default {
    data() {
        return {
            newCategory: {
                name: "",
            },
            errors: {
                name: null,
            },
        };
    },
    computed: {
        hasErrors() {
            return this.errors.name !== null;
        },
    },
    methods: {
        validateCategoryName() {
            this.errors.name = null;

            if (this.newCategory.name.trim() === "") {
                this.errors.name = ["Category name is required."];
            } else if (this.newCategory.name.length > 255) {
                this.errors.name = [
                    "Category name cannot be more than 255 characters.",
                ];
            }
        },
        async createCategory() {
            this.validateCategoryName();

            if (this.hasErrors) {
                return;
            }

            try {
                const response = await axios.post(
                    "/admin/category",
                    this.newCategory,
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem(
                                "token"
                            )}`,
                        },
                    }
                );
                this.$emit("categoryCreated", response.data.data);

                const createModal = Modal.getInstance(
                    document.getElementById("createCategoryModal")
                );
                createModal.hide();
                this.newCategory.name = "";
                window.location.reload();
            } catch (error) {
                console.error("Error creating category:", error);
            }
        },
    },
    watch: {
        "newCategory.name": function (newVal) {
            if (newVal.length > 255) {
                this.errors.name = [
                    "Category name cannot be more than 255 characters.",
                ];
            } else {
                this.errors.name = null;
            }
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
