<template>
    <div
        class="modal fade show"
        tabindex="-1"
        aria-hidden="true"
        style="display: block"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button
                        type="button"
                        class="btn-close"
                        @click="$emit('close')"
                    ></button>
                </div>
                <div class="modal-body">
                    <div
                        :class="['status-shape', `status-${order.status}`]"
                        :style="{ minWidth: `${order.status.length * 10}px` }"
                    >
                        {{ formatStatus(order.status) }}
                    </div>

                    <div v-if="order.products && order.products.length">
                        <p><strong>Products:</strong></p>
                        <ul>
                            <li
                                v-for="(product, index) in order.products"
                                :key="index"
                            >
                                <strong>{{ product.name }}</strong> (Quantity
                                Ordered: {{ product.quantity }}) -
                                {{ formatPrice(product.price) }}
                            </li>
                        </ul>
                    </div>
                    <div v-else>
                        <p>No products available for this order.</p>
                    </div>

                    <p>
                        <strong>Total Price:</strong>
                        {{ formatPrice(order.total_price) }}
                    </p>

                    <p>
                        <strong>Order Date:</strong>
                        {{ formatDate(order.created_at) }}
                    </p>
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
        order: Object,
    },
    methods: {
        formatDate(date) {
            if (!date) return "-";

            const options = {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                timeZone: "Asia/Jakarta",
                timeZoneName: "short",
            };
            return new Date(date).toLocaleDateString("id-ID", options);
        },
        formatStatus(status) {
            return status.replace(/\b(\w)/g, (char) =>
                char.toUpperCase().replace(/_/g, " ")
            );
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

<style scoped>
.status-shape {
    display: inline-block;
    padding: 5px 10px;
    font-weight: bold;
    border-radius: 8px;
    color: white;
}

.status-canceled {
    background-color: red;
}

.status-pending {
    background-color: grey;
}

.status-completed {
    background-color: green;
}
</style>
