<template>
    <aside class="sidebar">
        <ul class="dashboard-menu">
            <li>
                <router-link :to="{ name: 'admin.dashboard' }"
                    >Dashboard</router-link
                >
            </li>
            <li>
                <router-link :to="{ name: 'admin.categories' }"
                    >Manage Categories</router-link
                >
            </li>
            <li>
                <router-link :to="{ name: 'admin.products' }"
                    >Manage Products</router-link
                >
            </li>
            <li>
                <router-link :to="{ name: 'admin.orders' }"
                    >Manage Orders</router-link
                >
            </li>
        </ul>
    </aside>
</template>

<script>
export default {};
</script>

<style scoped>
.sidebar {
    width: 250px;
    background-color: #333;
    color: white;
    padding: 20px;
    box-sizing: border-box;
    overflow-y: auto;
}

.sidebar .dashboard-menu {
    list-style: none;
    padding: 0;
}

.sidebar .dashboard-menu li {
    margin-bottom: 10px;
}

.sidebar .dashboard-menu a {
    color: white;
    text-decoration: none;
    font-size: 18px;
}

.sidebar .dashboard-menu a:hover {
    background-color: #575757;
    display: block;
    padding: 10px;
    border-radius: 5px;
}
</style>
