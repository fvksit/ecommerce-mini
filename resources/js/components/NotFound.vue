<template>
    <div class="not-found-container">
        <h1>404 - Page Not Found</h1>
        <p>
            The page you are looking for does not exist. Go back to the
            <router-link to="/admin/login">login page</router-link>.
        </p>
    </div>
</template>

<script>
export default {
    name: "NotFound",
};
</script>

<style scoped>
.not-found-container {
    text-align: center;
    padding: 50px;
    margin-top: 50px;
}

h1 {
    font-size: 3em;
    margin-bottom: 20px;
    color: #ff6b6b;
}

p {
    font-size: 1.2em;
    color: #333;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
