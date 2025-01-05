<template>
    <div v-if="isLoggedIn" class="dashboard-container">
        <Navbar />
        <div class="content">
            <Sidebar />
            <main class="main-content">
                <div class="canvas-container">
                    <div class="table-actions">
                        <button
                            @click="showCreateModal"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-plus"></i> Add Order
                        </button>
                    </div>
                    <div class="table-container">
                        <table
                            id="orders-table"
                            class="table table-striped table-bordered"
                        >
                            <thead>
                                <tr>
                                    <th class="number-column">No</th>
                                    <th>Customer Name</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="orders.length === 0">
                                    <td colspan="5" class="text-center">
                                        No orders available.
                                    </td>
                                </tr>
                                <tr
                                    v-for="(order, index) in orders"
                                    :key="order.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ order.user.name }}</td>
                                    <td>
                                        {{ formatPrice(order.total_price) }}
                                    </td>
                                    <td>
                                        <div
                                            :class="[
                                                'status-shape',
                                                `status-${order.status}`,
                                            ]"
                                            :style="{
                                                minWidth: `${
                                                    order.status.length * 10
                                                }px`,
                                            }"
                                        >
                                            {{ formatStatus(order.status) }}
                                        </div>
                                    </td>
                                    <td class="action-icons">
                                        <button
                                            @click="selectOrder(order)"
                                            class="btn btn-info btn-sm"
                                        >
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <OrderDetailModal
                    :order="selectedOrder"
                    v-if="isOrderDetailModalVisible"
                    @close="closeModal"
                />
            </main>
        </div>
    </div>
</template>

<script>
import { getOrders, getOrderById } from "../services/orderService";
import $ from "jquery";
import "datatables.net-responsive";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import OrderDetailModal from "../modals/DetailOrderModal.vue";
import Navbar from "../navbar/Navbar.vue";
import Sidebar from "../sidebar/Sidebar.vue";

export default {
    components: {
        OrderDetailModal,
        Navbar,
        Sidebar,
    },
    data() {
        return {
            orders: [],
            selectedOrder: null,
            isOrderDetailModalVisible: false,
            isLoggedIn: true,
        };
    },
    mounted() {
        if (this.isLoggedIn) {
            this.loadOrders();
        }
    },
    methods: {
        async loadOrders() {
            try {
                this.orders = await getOrders();
                this.$nextTick(() => {
                    $("#orders-table").DataTable();
                });
            } catch (error) {
                console.error("Error loading orders:", error);
            }
        },

        async selectOrder(order) {
            try {
                const response = await getOrderById(order.id);
                this.selectedOrder = response.data;
                this.isOrderDetailModalVisible = true;
            } catch (error) {
                console.error("Error loading order details:", error);
            }
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
        closeModal() {
            this.isOrderDetailModalVisible = false;
        },
        showCreateModal() {
            console.log("Show create order modal");
        },
    },
};
</script>

<style scoped>
.dashboard-container {
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow: hidden;
}

.main-content {
    flex-grow: 1;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}

.canvas-container {
    position: relative;
}

.table-actions {
    margin: 1rem 0;
    position: relative;
    z-index: 2;
}

.action-icons button {
    margin-right: 5px;
}

.table-wrapper {
    display: flex;
    justify-content: center;
    padding: 10px;
}

.table-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    background: white;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#background-canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
}

#orders-table {
    width: 100%;
    margin: 0 auto;
    border-collapse: collapse;
}

#orders-table th,
#orders-table td {
    text-align: left;
}

.content {
    display: flex;
    flex-grow: 1;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

.number-column {
    width: 5px;
    text-align: center;
}

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

.modal-backdrop {
    z-index: 1040 !important;
}
</style>
