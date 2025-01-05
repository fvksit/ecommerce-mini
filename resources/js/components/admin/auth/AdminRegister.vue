<template>
    <div class="register-container">
        <h1>Admin Register</h1>
        <div v-if="successMessage" class="success-message">
            {{ successMessage }}
        </div>
        <p class="login-link" v-if="!successMessage">
            Already have an account?
            <router-link to="/admin/login">Login here</router-link>
        </p>
        <form
            @submit.prevent="register"
            v-if="!successMessage"
            class="register-form"
        >
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" v-model="name" required />
                <span v-if="errors.name" class="error-message">{{
                    errors.name[0]
                }}</span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" v-model="email" required />
                <span v-if="errors.email" class="error-message">{{
                    errors.email[0]
                }}</span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="password-input">
                    <input
                        :type="passwordFieldType"
                        id="password"
                        v-model="password"
                        required
                    />
                    <i
                        :class="passwordIcon"
                        @click="togglePasswordVisibility('password')"
                    ></i>
                </div>
                <span v-if="errors.password" class="error-message">{{
                    errors.password[0]
                }}</span>
            </div>
            <div class="form-group">
                <label for="password_confirmation"
                    >Password Confirmation:</label
                >
                <div class="password-input">
                    <input
                        :type="passwordConfirmationFieldType"
                        id="password_confirmation"
                        v-model="passwordConfirmation"
                        required
                    />
                    <i
                        :class="passwordConfirmationIcon"
                        @click="
                            togglePasswordVisibility('passwordConfirmation')
                        "
                    ></i>
                </div>
            </div>
            <span v-if="errors.general" class="error-message">{{
                errors.general[0]
            }}</span>
            <button type="submit" class="btn-register" :disabled="loading">
                Register
            </button>
        </form>
    </div>
</template>

<script>
import { registerAdmin } from "../services/authService";

export default {
    data() {
        return {
            name: "",
            email: "",
            password: "",
            passwordConfirmation: "",
            showPassword: false,
            showPasswordConfirmation: false,
            errors: {},
            successMessage: "",
            loading: false,
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
        togglePasswordVisibility(field) {
            if (field === "password") {
                this.showPassword = !this.showPassword;
            } else if (field === "passwordConfirmation") {
                this.showPasswordConfirmation = !this.showPasswordConfirmation;
            }
        },
        async register() {
            this.errors = {};
            this.successMessage = "";
            this.loading = true;

            try {
                const response = await registerAdmin(
                    this.name,
                    this.email,
                    this.password,
                    this.passwordConfirmation
                );

                this.successMessage = "Registration successful!";
                setTimeout(() => {
                    this.$router.push({ name: "admin.login" });
                }, 2000);
            } catch (error) {
                this.loading = false;
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    this.errors.general = [
                        "An unexpected error occurred. Please try again.",
                    ];
                }
            }
        },
        navigateToLogin() {
            this.$router.push({ name: "admin.login" });
        },
    },
};
</script>

<style scoped>
.register-container {
    max-width: 400px;
    margin: 100px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

.register-form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 15px;
}

label {
    margin-bottom: 5px;
    font-weight: bold;
}

.password-input {
    display: flex;
    align-items: center;
    position: relative;
}

.password-input input {
    flex-grow: 1;
}

.password-input i {
    position: absolute;
    right: 10px;
    cursor: pointer;
    color: #007bff;
}

input[type="email"],
input[type="password"],
input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn-register {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.btn-register:hover {
    background-color: #0056b3;
}

.register-link router-link {
    color: #007bff;
    cursor: pointer;
    text-decoration: underline;
}

.error-message {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
}
</style>
