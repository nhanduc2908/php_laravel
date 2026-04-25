<template>
    <div class="reports-management"><div class="header-actions"><h2>Báo cáo</h2><button class="btn-primary" @click="generateReport"><i class="fas fa-plus"></i> Tạo báo cáo mới</button></div>
    <div class="reports-list"><table class="data-table"><thead><tr><th>Tên báo cáo</th><th>Máy chủ</th><th>Định dạng</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
    <tbody><tr v-for="report in reports" :key="report.id"><td>{{ report.name }}</td><td>{{ report.server_name }}</td><td>{{ report.format }}</td><td>{{ formatDate(report.created_at) }}</td>
    <td class="actions"><button @click="downloadReport(report.id)"><i class="fas fa-download"></i></button><button @click="deleteReport(report.id)"><i class="fas fa-trash"></i></button></td></tr></tbody></table></div></div>
</template>