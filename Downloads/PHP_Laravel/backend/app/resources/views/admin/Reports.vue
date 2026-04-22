<template>
    <div class="reports"><div class="header-actions"><h2>Báo cáo</h2><button class="btn-primary" @click="generateReport"><i class="fas fa-plus"></i> Tạo báo cáo</button></div>
        <table class="data-table"><thead><tr><th>Tên</th><th>Định dạng</th><th>Ngày</th><th>Thao tác</th></tr></thead>
        <tbody><tr v-for="r in reports" :key="r.id"><td>{{ r.name }}</td><td>{{ r.format }}</td><td>{{ formatDate(r.created_at) }}</td><td><button @click="downloadReport(r.id)"><i class="fas fa-download"></i></button></td></tr></tbody></table>
    </div>
</template>