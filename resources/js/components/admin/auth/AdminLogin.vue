<template>
    <div class="login-container">
        <h1>Admin Login</h1>
        <form @submit.prevent="login" class="login-form">
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
                        @click="togglePasswordVisibility"
                    ></i>
                </div>
                <span v-if="errors.password" class="error-message">{{
                    errors.password[0]
                }}</span>
            </div>
            <button type="submit" class="btn-login" :disabled="loading">
                Login
            </button>
            <span v-if="errors.general" class="error-message">{{
                errors.general
            }}</span>
        </form>
        <br />
        <p class="register-link">
            Don't have an account?
            <router-link to="/admin/register-admin">Register here</router-link>
        </p>
    </div>
</template>

<script>
import { login } from "../services/authService";

export default {
    data() {
        return {
            email: "",
            password: "",
            showPassword: false,
            errors: {},
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
    },
    methods: {
        togglePasswordVisibility() {
            this.showPassword = !this.showPassword;
        },
        async login() {
            this.errors = {};
            this.loading = true;

            try {
                await login(this.email, this.password);

                this.$router.push({ name: "admin.dashboard" });
            } catch (error) {
                this.loading = false;

                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else if (error.message) {
                    this.errors.general = error.message;
                } else {
                    this.errors.general =
                        "An error occurred. Please try again.";
                }
                console.error("Login failed:", this.errors);
            }
        },
    },
};
</script>

<style scoped>
.login-container {
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

.login-form {
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

.btn-login {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.btn-login:hover {
    background-color: #0056b3;
}

.register-link button {
    background: none;
    color: #007bff;
    border: none;
    padding: 0;
    cursor: pointer;
}

.error-message {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
}
</style>
