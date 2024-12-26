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
                    <p :style="statusStyle">
                        <strong>Status:</strong> {{ order.status }}
                    </p>

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
                hour: "numeric",
                minute: "numeric",
                second: "numeric",
            };
            return new Date(date).toLocaleDateString("en-US", options);
        },
        formatPrice(price) {
            if (!price) return "Rp. 0";
            const val = (price / 1).toFixed(0).replace(".", ",");
            return (
                `Rp. ` + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            );
        },
    },
    computed: {
        statusStyle() {
            let color = "";
            let padding = "5px 10px";
            if (this.order.status === "canceled") {
                color = "red";
            } else if (this.order.status === "pending") {
                color = "grey";
            } else if (this.order.status === "completed") {
                color = "green";
            }
            return {
                color: color,
                padding: padding,
                borderRadius: "5px",
                fontWeight: "bold",
            };
        },
    },
};
</script>

<style scoped>
/* Modal Styling */
</style>
