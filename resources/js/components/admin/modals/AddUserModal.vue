<template>
    <div
        class="modal fade"
        id="addUserModal"
        tabindex="-1"
        aria-labelledby="addUserModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="handleClose"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitForm">
                        <!-- Name Input -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                v-model="name"
                                required
                            />
                        </div>

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                v-model="email"
                                required
                            />
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label"
                                >Password</label
                            >
                            <div class="password-input">
                                <input
                                    :type="passwordFieldType"
                                    class="form-control"
                                    id="password"
                                    v-model="password"
                                    required
                                />
                                <i
                                    :class="passwordIcon"
                                    @click="
                                        togglePasswordVisibility('password')
                                    "
                                ></i>
                            </div>
                        </div>

                        <!-- Password Confirmation Input -->
                        <div class="mb-3">
                            <label for="passwordConfirmation" class="form-label"
                                >Confirm Password</label
                            >
                            <div class="password-input">
                                <input
                                    :type="passwordConfirmationFieldType"
                                    class="form-control"
                                    id="passwordConfirmation"
                                    v-model="passwordConfirmation"
                                    required
                                />
                                <i
                                    :class="passwordConfirmationIcon"
                                    @click="
                                        togglePasswordVisibility(
                                            'passwordConfirmation'
                                        )
                                    "
                                ></i>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">
                            Add User
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
            name: "",
            email: "",
            password: "",
            passwordConfirmation: "",
            showPassword: false,
            showPasswordConfirmation: false,
        };
    },
    computed: {
        passwordFieldType() {
            return this.showPassword ? "text" : "password";
        },
        passwordIcon() {
            return this.showPassword ? "fas fa-eye-slash" : "fas fa-eye";
        },
        passwordConfirmationFieldType() {
            return this.showPasswordConfirmation ? "text" : "password";
        },
        passwordConfirmationIcon() {
            return this.showPasswordConfirmation
                ? "fas fa-eye-slash"
                : "fas fa-eye";
        },
    },
    methods: {
        async submitForm() {
            const newUser = {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.passwordConfirmation,
            };
            try {
                const response = await axios.post("/admin/register", newUser, {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
                console.log("User added:", response.data);
                this.$emit("userAdded");
                this.clearForm();
                const modal = Modal.getInstance(
                    document.getElementById("addUserModal")
                );
                modal.hide();
                this.removeBackdrop();
                // window.location.reload();
            } catch (error) {
                console.error(
                    "Failed to add user:",
                    error.response?.data || error.message
                );
            }
        },
        clearForm() {
            this.name = "";
            this.email = "";
            this.password = "";
            this.passwordConfirmation = "";
        },
        handleClose() {
            const modal = Modal.getInstance(
                document.getElementById("addUserModal")
            );
            if (modal) {
                modal.hide();
            }
            this.removeBackdrop();
        },
        removeBackdrop() {
            const backdrops = document.getElementsByClassName("modal-backdrop");
            while (backdrops[0]) {
                backdrops[0].parentNode.removeChild(backdrops[0]);
            }
            document.body.classList.remove("modal-open");
            document.body.style.paddingRight = "";
        },
        togglePasswordVisibility(field) {
            if (field === "password") {
                this.showPassword = !this.showPassword;
            } else if (field === "passwordConfirmation") {
                this.showPasswordConfirmation = !this.showPasswordConfirmation;
            }
        },
    },
};
</script>

<style scoped>
.modal-body .mb-3 {
    margin-bottom: 1rem;
}

.password-input {
    position: relative;
}

.password-input i {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #007bff;
}
</style>
