<template>
    <div class="assessments-management"><div class="header-actions"><h2>Đánh giá an ninh</h2><button class="btn-primary" @click="runNewAssessment"><i class="fas fa-play"></i> Chạy đánh giá</button></div>
        <table class="data-table"><thead><tr><th>Máy chủ</th><th>Điểm</th><th>Ngày</th><th>Thao tác</th></tr></thead>
        <tbody><tr v-for="a in assessments" :key="a.id"><td>{{ a.server_name }}</td><td>{{ a.total_score }}%</td><td>{{ formatDate(a.created_at) }}</td><td><button @click="viewDetail(a.id)"><i class="fas fa-eye"></i></button><button @click="exportReport(a.id)"><i class="fas fa-download"></i></button></td></tr></tbody></table>
    </div>
</template>