<template>
    <div>
        <h1>Order List</h1>
        <ul>
            <li v-for="order in orders" :key="order.id">
                Order #{{ order.id }} - {{ order.customer_name }}
            </li>
        </ul>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            orders: [],
        };
    },
    async created() {
        try {
            const response = await axios.get("/api/admin/order");
            this.orders = response.data;
        } catch (error) {
            console.error(error.response.data);
        }
    },
};
</script>
