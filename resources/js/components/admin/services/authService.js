import axios from "axios";

const API_URL = "/admin";

// Login
export const login = async (email, password) => {
    try {
        const response = await axios.post(`${API_URL}/login`, { email, password });

        if (response.data.role !== 'admin') {
            throw new Error('Unauthorized access. Admins only.');
        }

        localStorage.setItem('token', response.data.token);
        return response.data.token;
    } catch (error) {
        throw error;
    }
};

// Register
export const registerAdmin = async (name, email, password, passwordConfirmation) => {
    try {
        const response = await axios.post(`${API_URL}/register-admin`, {
            name,
            email,
            password,
            password_confirmation: passwordConfirmation,
            role: "admin",
        });

        return response.data;
    } catch (error) {
        throw error;
    }
};

// Logout
export const logout = async () => {
    try {
        await axios.post(`${API_URL}/logout`, null, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        localStorage.removeItem("token");
    } catch (error) {
        console.error("Logout failed:", error);
        throw error;
    }
};
