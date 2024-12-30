<template>
    <div
        class="modal fade"
        id="editCategoryModal"
        tabindex="-1"
        aria-labelledby="editCategoryModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">
                        Edit Category
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="handleSubmit">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label">
                                Category Name
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="editCategoryName"
                                v-model="currentCategory.name"
                                :class="{ 'is-invalid': nameError }"
                                @blur="validateCategoryName"
                                required
                            />
                            <div v-if="nameError" class="invalid-feedback">
                                {{ nameError }}
                            </div>
                        </div>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="isFormInvalid"
                        >
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
    props: {
        category: Object,
    },
    data() {
        return {
            currentCategory: {
                id: "",
                name: "",
            },
            nameError: null,
        };
    },
    computed: {
        isFormInvalid() {
            return (
                this.nameError ||
                !this.currentCategory.name ||
                !this.currentCategory.name.trim()
            );
        },
    },
    watch: {
        category: {
            immediate: true,
            handler(newVal) {
                this.currentCategory = { ...newVal };
            },
        },
    },
    methods: {
        validateCategoryName() {
            const nameLength = this.currentCategory.name.length;

            if (nameLength < 1) {
                this.nameError = "Category name cannot be empty.";
            } else if (nameLength > 255) {
                this.nameError = "Category name cannot exceed 255 characters.";
            } else {
                this.nameError = null;
            }
        },
        handleSubmit() {
            this.validateCategoryName();

            if (this.nameError) {
                return;
            }

            this.updateCategory();
        },
        updateCategory() {
            if (this.nameError) return;
            axios
                .put(
                    `/admin/category/${this.currentCategory.id}`,
                    this.currentCategory,
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem(
                                "token"
                            )}`,
                        },
                    }
                )
                .then((response) => {
                    this.$emit("categoryUpdated", response.data.data);
                    this.closeModal();
                })
                .catch((error) => {
                    console.error("Error updating category:", error);
                });
        },
        closeModal() {
            const modalElement = document.getElementById("editCategoryModal");
            const modalInstance = Modal.getInstance(modalElement);
            modalInstance.hide();
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
