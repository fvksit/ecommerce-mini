<template>
    <div
        class="modal fade show"
        tabindex="-1"
        aria-modal="true"
        style="display: block"
        v-if="product"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Detail</h5>
                    <button
                        type="button"
                        class="btn-close"
                        @click="$emit('close')"
                    ></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> {{ product.name }}</p>
                    <p>
                        <strong>Description:</strong> {{ product.description }}
                    </p>
                    <p>
                        <strong>Price:</strong> {{ formatPrice(product.price) }}
                    </p>
                    <p><strong>Stock:</strong> {{ product.stock }}</p>
                    <p>
                        <strong>Category:</strong>
                        {{
                            product.category
                                ? product.category.name
                                : "No Category"
                        }}
                    </p>
                    <div
                        v-if="product.images && product.images.length"
                        class="d-flex flex-wrap gap-2"
                    >
                        <p><strong>Images:</strong></p>
                        <div
                            v-for="image in product.images"
                            :key="image.id"
                            class="position-relative"
                        >
                            <img
                                :src="getImageUrl(image.image_path)"
                                :alt="`Image ${image.id}`"
                                class="img-thumbnail"
                                style="
                                    width: 100px;
                                    height: 100px;
                                    object-fit: cover;
                                "
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="$emit('close')"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        product: Object,
    },
    methods: {
        getImageUrl(imagePath) {
            const url = `${import.meta.env.VITE_API_URL}/storage/${imagePath}`;
            return url;
        },
        formatPrice(price) {
            if (!price) return "Rp. 0";
            const val = (price / 1).toFixed(0).replace(".", ",");
            return (
                `Rp. ` + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            );
        },
    },
};
</script>
