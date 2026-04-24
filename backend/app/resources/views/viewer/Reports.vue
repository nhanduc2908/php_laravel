<template>
    <div class="reports"><h2>Báo cáo an ninh</h2><div class="filters"><input type="text" v-model="search" placeholder="Tìm kiếm..."><select v-model="serverFilter"><option value="">Tất cả máy chủ</option><option v-for="s in servers" :key="s.id" :value="s.id">{{ s.name }}</option></select></div>
        <table class="data-table"><thead><tr><th>Máy chủ</th><th>Điểm</th><th>Tuân thủ</th><th>Ngày</th><th>Thao tác</th></tr></thead>
        <tbody><tr v-for="r in reports" :key="r.id"><td>{{ r.server_name }}</td><td>{{ r.total_score }}%</td><td>{{ r.compliance_percent }}%</td><td>{{ formatDate(r.created_at) }}</td><td><button @click="viewReport(r.id)"><i class="fas fa-eye"></i></button><button @click="downloadReport(r.id)"><i class="fas fa-download"></i></button></td></tr></tbody></table>
    </div>
</template>