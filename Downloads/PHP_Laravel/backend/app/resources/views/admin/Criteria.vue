<template>
    <div class="criteria-management"><div class="header-actions"><h2>Quản lý tiêu chí (280)</h2><button class="btn-primary" @click="openCreateModal"><i class="fas fa-plus"></i> Thêm</button><button class="btn-secondary" @click="importCriteria"><i class="fas fa-upload"></i> Import</button></div>
        <div class="filters"><select v-model="categoryFilter"><option value="">Tất cả</option><option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option></select><input type="text" v-model="search" placeholder="Tìm kiếm..."></div>
        <table class="data-table"><thead><tr><th>Mã</th><th>Tên</th><th>Trọng số</th><th>Thao tác</th></tr></thead>
        <tbody><tr v-for="c in criteria" :key="c.id"><td>{{ c.code }}</td><td>{{ c.name }}</td><td>{{ c.weight }}</td><td><button @click="editCriterion(c)"><i class="fas fa-edit"></i></button><button @click="deleteCriterion(c.id)"><i class="fas fa-trash"></i></button></td></tr></tbody></table>
    </div>
</template>