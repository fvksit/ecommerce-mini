<template>
    <div>
        <h1>Category List</h1>
        <ul>
            <li v-for="category in categories" :key="category.id">
                {{ category.name }}
            </li>
        </ul>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            categories: [],
        };
    },
    async created() {
        try {
            const response = await axios.get("/admin/category");
            this.categories = response.data;
        } catch (error) {
            console.error(error.response.data);
        }
    },
};
</script>
