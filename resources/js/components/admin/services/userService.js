import axios from "axios";

const API_URL = "/admin/index";

export const fetchUsers = async (token) => {
    try {
        const response = await axios.get(API_URL, {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        });
        return response.data.users;
    } catch (error) {
        console.error("Error fetching users:", error);
        throw error;
    }
};

export const fetchUserDetails = async (userId, token) => {
    try {
        const response = await axios.get(`${API_URL}/${userId}`, {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        });
        return response.data.data;
    } catch (error) {
        console.error("Error fetching user details:", error);
        throw error;
    }
};
