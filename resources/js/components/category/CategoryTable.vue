<template>
    <div class="container mx-auto mt-4">
        <button
            @click="openModal"
            class="px-4 py-2 bg-green-500 text-white rounded mb-4"
        >
            Tambah Kategori
        </button>

        <table id="category-table" class="table table-bordered w-full">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal untuk Create dan Edit -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 bg-gray-700 bg-opacity-50 flex justify-center items-center"
        >
            <div class="bg-white p-6 rounded-md shadow-md w-full max-w-md">
                <h3 class="text-xl mb-4" v-if="isEditMode">Edit Kategori</h3>
                <h3 class="text-xl mb-4" v-else>Tambah Kategori</h3>
                <form @submit.prevent="saveCategory">
                    <input
                        v-model="category.name"
                        type="text"
                        class="block w-full mb-2"
                        placeholder="Nama Kategori"
                        required
                    />
                    <div class="flex justify-between">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 bg-gray-400 text-white rounded"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            categories: [],
            category: { id: null, name: "" },
            isModalOpen: false,
            isEditMode: false,
        };
    },
    mounted() {
        this.fetchCategories();
    },
    methods: {
        fetchCategories() {
            this.$http.get("/admin/categories").then((response) => {
                this.categories = response.data;
                this.initDataTable();
            });
        },
        initDataTable() {
            $("#category-table").DataTable({
                data: this.categories,
                responsive: true,
                columns: [
                    { data: "id" },
                    { data: "name" },
                    {
                        data: null,
                        render: (data, type, row) => {
                            return `
                                <button @click="editCategory(${row.id})" class="text-blue-500">Edit</button>
                                <button @click="deleteCategory(${row.id})" class="text-red-500">Hapus</button>
                            `;
                        },
                    },
                ],
            });
        },
        openModal() {
            this.isModalOpen = true;
            this.isEditMode = false;
            this.category = { id: null, name: "" };
        },
        closeModal() {
            this.isModalOpen = false;
        },
        saveCategory() {
            if (this.isEditMode) {
                this.$http
                    .put(`/admin/categories/${this.category.id}`, this.category)
                    .then(() => {
                        this.fetchCategories();
                        this.closeModal();
                    });
            } else {
                this.$http.post("/admin/categories", this.category).then(() => {
                    this.fetchCategories();
                    this.closeModal();
                });
            }
        },
        editCategory(id) {
            this.$http.get(`/categories/${id}`).then((response) => {
                this.category = response.data;
                this.isEditMode = true;
                this.openModal();
            });
        },
        deleteCategory(id) {
            if (confirm("Apakah Anda yakin ingin menghapus kategori ini?")) {
                this.$http.delete(`/admin/categories/${id}`).then(() => {
                    this.fetchCategories();
                });
            }
        },
    },
};
</script>
