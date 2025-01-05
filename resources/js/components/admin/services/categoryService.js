import axios from "axios";

const API_URL = "/admin/category";

export const fetchCategories = async (token) => {
    try {
        const response = await axios.get(API_URL, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        return response.data.data;
    } catch (error) {
        throw new Error("Error fetching categories: " + error.message);
    }
};

export const createCategory = async (categoryData, token) => {
    try {
        const response = await axios.post(API_URL, categoryData, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        return response.data.data;
    } catch (error) {
        throw new Error("Error creating category: " + error.message);
    }
};

export const updateCategory = async (categoryId, categoryData, token) => {
    try {
        const response = await axios.put(`${API_URL}/${categoryId}`, categoryData, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        return response.data.data;
    } catch (error) {
        throw new Error("Error updating category: " + error.message);
    }
};

export const deleteCategory = async (categoryId, token) => {
    try {
        const response = await axios.delete(`${API_URL}/${categoryId}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        return response.data;
    } catch (error) {
        throw new Error("Error deleting category: " + error.message);
    }
};
