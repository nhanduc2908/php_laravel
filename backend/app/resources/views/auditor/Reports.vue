<template>
    <div class="reports"><h2>Báo cáo kiểm toán</h2><button class="btn-primary" @click="generateAuditReport"><i class="fas fa-plus"></i> Tạo báo cáo kiểm toán</button>
        <table class="data-table"><thead><tr><th>Tên báo cáo</th><th>Khoảng thời gian</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
        <tbody><tr v-for="r in reports" :key="r.id"><td>{{ r.name }}</td><td>{{ r.date_from }} - {{ r.date_to }}</td><td>{{ formatDate(r.created_at) }}</td><td><button @click="downloadReport(r.id)"><i class="fas fa-download"></i></button></td></tr></tbody></table>
    </div>
</template>