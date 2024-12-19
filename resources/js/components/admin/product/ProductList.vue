<template>
    <div>
        <h1>Product List</h1>
        <ul>
            <li v-for="product in products" :key="product.id">
                {{ product.name }}
            </li>
        </ul>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            products: [],
        };
    },
    async created() {
        try {
            const response = await axios.get("/api/admin/product");
            this.products = response.data;
        } catch (error) {
            console.error(error.response.data);
        }
    },
};
</script>
