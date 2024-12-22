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
                    <form @submit.prevent="updateCategory">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label"
                                >Category Name</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="editCategoryName"
                                v-model="currentCategory.name"
                                required
                            />
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
    props: {
        category: Object,
    },
    data() {
        return {
            currentCategory: {
                id: "",
                name: "",
            },
        };
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
        updateCategory() {
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
                    const editModal = Modal.getInstance(
                        document.getElementById("editCategoryModal")
                    );
                    editModal.hide();
                })
                .catch((error) => {
                    console.error("Error updating category:", error);
                });
        },
    },
};
</script>
