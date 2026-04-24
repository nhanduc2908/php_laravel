<template>
    <div class="audit-logs"><h2>Audit Logs</h2>
        <div class="filters"><input type="text" v-model="search" placeholder="Tìm kiếm..."><select v-model="actionFilter"><option value="">Tất cả hành động</option><option>login</option><option>logout</option><option>create</option><option>update</option><option>delete</option></select><input type="date" v-model="dateFrom"><input type="date" v-model="dateTo"><button class="btn-secondary" @click="exportLogs"><i class="fas fa-download"></i> Xuất</button></div>
        <table class="data-table"><thead><tr><th>Thời gian</th><th>Người dùng</th><th>Hành động</th><th>Tài nguyên</th><th>IP</th><th>User Agent</th></tr></thead>
        <tbody><tr v-for="log in logs" :key="log.id"><td>{{ formatDateTime(log.created_at) }}</td><td>{{ log.user_name }}</td><td>{{ log.action }}</td><td>{{ log.resource }}:{{ log.resource_id }}</td><td>{{ log.ip }}</td><td>{{ truncate(log.user_agent, 30) }}</td></tr></tbody></table>
        <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="fetchLogs" />
    </div>
</template>