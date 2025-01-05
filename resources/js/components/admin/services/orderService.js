import axios from "axios";

const API_URL = "/admin/order";

export const getOrders = async () => {
    try {
        const response = await axios.get(`${API_URL}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    } catch (error) {
        console.error("Error fetching orders:", error);
        throw error;
    }
};

export const getOrderById = async (id) => {
    try {
        const response = await axios.get(`${API_URL}/${id}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        return response.data;
    } catch (error) {
        console.error("Error fetching order details:", error);
        throw error;
    }
};

export const createOrder = async (orderData) => {
    try {
        const response = await axios.post(API_URL, orderData, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    } catch (error) {
        console.error("Error creating order:", error);
        throw error;
    }
};
