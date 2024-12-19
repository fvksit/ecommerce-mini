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
            <button type="submit" class="btn-login">Login</button>
            <span v-if="errors.general" class="error-message">{{
                errors.general
            }}</span>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            email: "",
            password: "",
            showPassword: false,
            errors: {},
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
            try {
                const response = await axios.post("/admin/login", {
                    email: this.email,
                    password: this.password,
                });

                // Assuming the response contains user role data
                if (response.data.role !== "admin") {
                    this.errors = {
                        general: "Unauthorized access. Admins only.",
                    };
                    localStorage.removeItem("token");
                    window.location.href = "/admin/login";
                    return;
                }

                // Assuming the token is in the response data
                localStorage.setItem("token", response.data.token);
                console.log("Login successful. Token saved.");
                this.$router.push({ name: "admin.dashboard" });
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    this.errors = {
                        general: "An error occurred. Please try again.",
                    };
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

.error-message {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
}
</style>
