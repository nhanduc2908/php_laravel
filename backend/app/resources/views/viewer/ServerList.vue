<template>
    <div class="server-list"><h2>Danh sách máy chủ</h2>
        <table class="data-table"><thead><tr><th>Tên máy chủ</th><th>IP</th><th>Điểm</th><th>Lần đánh giá cuối</th></tr></thead>
        <tbody><tr v-for="s in servers" :key="s.id"><td>{{ s.name }}</td><td>{{ s.host }}</td><td>{{ s.score }}%</td><td>{{ formatDate(s.last_assessment) }}</td></tr></tbody></table>
    </div>
</template>