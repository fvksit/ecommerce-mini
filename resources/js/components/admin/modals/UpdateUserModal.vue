<template>
    <div
        class="modal fade show"
        tabindex="-1"
        aria-modal="true"
        style="display: block"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User</h5>
                    <button
                        type="button"
                        class="btn-close"
                        @click="$emit('close')"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateUser">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="formData.name"
                                required
                            />
                            <div v-if="errors.name" class="text-danger">
                                {{ errors.name[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                v-model="formData.email"
                                :disabled="emailDisabled"
                                required
                            />
                            <div v-if="errors.email" class="text-danger">
                                {{ errors.email[0] }}
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        user: Object,
        showModal: Boolean,
    },
    data() {
        return {
            formData: { ...this.user },
            errors: {},
            emailDisabled: false, // Email disabled by default unless the user is updating it
        };
    },
    methods: {
        // Close the modal
        closeModal() {
            this.$emit("close");
        },

        // Method to handle user update
        async updateUser() {
            const token = localStorage.getItem("token");
            if (!token) {
                console.error("No token found. Unable to update user.");
                return;
            }

            try {
                // Tentukan data yang dikirimkan ke backend
                const updatedData = {
                    name: this.formData.name,
                    email:
                        this.formData.email !== this.user.email
                            ? this.formData.email
                            : this.user.email,
                };

                // Kirim permintaan PUT ke backend
                const response = await axios.put(
                    `/admin/index/${this.user.id}`,
                    updatedData,
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            Accept: "application/json",
                        },
                    }
                );

                // Emit success
                this.$emit("userUpdated");
                this.closeModal();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    // Validation errors from backend
                    this.errors = error.response.data.errors || {};
                } else {
                    console.error("Failed to update user:", error);
                }
            }
        },

        // Enable email input if email is being updated
        enableEmailEdit() {
            this.emailDisabled = false;
        },
    },
    watch: {
        user: {
            handler(newUser) {
                // Reset form data whenever a new user is selected
                this.formData = { ...newUser };
                this.errors = {};
            },
            immediate: true,
        },
    },
};
</script>

<style scoped>
.modal-dialog {
    max-width: 500px;
    margin: 1.75rem auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
}

.form-label {
    font-weight: bold;
}

.btn-close {
    padding: 0.75rem 1.25rem;
    cursor: pointer;
}
</style>
