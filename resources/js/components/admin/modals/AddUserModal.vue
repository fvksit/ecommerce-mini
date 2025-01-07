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
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                v-model="name"
                                :class="{ 'is-invalid': nameError }"
                                required
                                maxlength="255"
                            />
                            <div class="invalid-feedback" v-if="nameError">
                                Name is required.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                v-model="email"
                                :class="{ 'is-invalid': emailError }"
                                required
                            />
                            <div class="invalid-feedback" v-if="emailError">
                                Please enter a valid email address.
                            </div>
                        </div>

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
                                    :class="{ 'is-invalid': passwordError }"
                                    required
                                />
                                <i
                                    :class="passwordIcon"
                                    @click="
                                        togglePasswordVisibility('password')
                                    "
                                ></i>
                            </div>
                            <div class="invalid-feedback" v-if="passwordError">
                                Password must be at least 8 characters long.
                            </div>
                        </div>

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
                                    :class="{
                                        'is-invalid': passwordConfirmationError,
                                    }"
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
                            <div
                                class="invalid-feedback"
                                v-if="passwordConfirmationError"
                            >
                                Passwords do not match.
                            </div>
                        </div>

                        <div v-if="globalError" class="alert alert-danger">
                            {{ globalError }}
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="!isFormValid"
                        >
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

            nameError: false,
            emailError: false,
            passwordError: false,
            passwordConfirmationError: false,
            globalError: "",
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
        isFormValid() {
            return !this.nameError && !this.emailError;
        },
    },
    methods: {
        validateForm() {
            this.nameError = !this.name;

            const emailPattern =
                /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            this.emailError = !emailPattern.test(this.email);

            this.passwordError = this.password.length < 8;
            this.passwordConfirmationError =
                this.password !== this.passwordConfirmation;

            if (this.passwordError) {
                this.globalError =
                    "Password must be at least 8 characters long.";
            } else if (this.passwordConfirmationError) {
                this.globalError = "Passwords do not match.";
            } else {
                this.globalError = "";
            }
        },
        async submitForm() {
            this.validateForm();

            if (this.passwordError || this.passwordConfirmationError) {
                return;
            }

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

                this.$emit("userAdded");
                this.clearForm();
                const modal = Modal.getInstance(
                    document.getElementById("addUserModal")
                );
                modal.hide();
                this.removeBackdrop();
                window.location.reload();
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
            this.nameError = false;
            this.emailError = false;
            this.passwordError = false;
            this.passwordConfirmationError = false;
            this.globalError = "";
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
