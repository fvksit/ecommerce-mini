import axios from "axios";

const API_URL = "/admin/product";

export const getProducts = async () => {
    try {
        const response = await axios.get(API_URL, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    } catch (error) {
        console.error("Error fetching products:", error);
        throw error;
    }
};

export const getProductById = async (productId) => {
    try {
        const response = await axios.get(`${API_URL}/${productId}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    } catch (error) {
        console.error("Error fetching product:", error);
        throw error;
    }
};

export const createProduct = async (productData) => {
    try {
        const response = await axios.post(API_URL, productData, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    } catch (error) {
        console.error("Error creating product:", error);
        throw error;
    }
};

export const updateProduct = async (productId, productData) => {
    try {
        const response = await axios.put(`${API_URL}/${productId}`, productData, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    } catch (error) {
        console.error("Error updating product:", error);
        throw error;
    }
};

export const deleteProduct = async (productId) => {
    try {
        const response = await axios.delete(`${API_URL}/${productId}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        return response.data.message;
    } catch (error) {
        console.error("Error deleting product:", error);
        throw error;
    }
};

export const uploadProductImage = async (productId, formData) => {
    try {
        const response = await axios.post(
            `${API_URL}/${productId}/images`,
            formData,
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                    "Content-Type": "multipart/form-data",
                },
            }
        );
        return response.data;
    } catch (error) {
        console.error("Error uploading product image:", error);
        throw error;
    }
};
