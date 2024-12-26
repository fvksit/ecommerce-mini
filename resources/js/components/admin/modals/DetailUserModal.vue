<template>
    <div
        class="modal fade show"
        tabindex="-1"
        aria-modal="true"
        style="display: block"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Detail</h5>
                    <button
                        type="button"
                        class="btn-close"
                        @click="$emit('close')"
                    ></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> {{ user.name }}</p>
                    <p><strong>Email:</strong> {{ user.email }}</p>
                    <div v-if="user.orders && user.orders.length">
                        <p><strong>Orders</strong></p>
                        <ul>
                            <li v-for="order in user.orders" :key="order.id">
                                Order ID: {{ order.id }}, Total Price:
                                {{ formatPrice(order.total_price) }}, Status:
                                {{ order.status }}
                            </li>
                        </ul>
                    </div>
                    <div v-else>
                        <p>No orders available.</p>
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
        user: Object,
    },
    methods: {
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
