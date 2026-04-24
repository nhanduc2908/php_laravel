<template>
    <div class="backup"><div class="header-actions"><h2>Sao lưu</h2><button class="btn-primary" @click="createBackup"><i class="fas fa-database"></i> Tạo backup</button></div>
        <table class="data-table"><thead><tr><th>File</th><th>Kích thước</th><th>Ngày</th><th>Thao tác</th></tr></thead>
        <tbody><tr v-for="b in backups" :key="b.id"><td>{{ b.filename }}</td><td>{{ formatSize(b.size) }}</td><td>{{ formatDate(b.created_at) }}</td><td><button @click="restoreBackup(b.id)"><i class="fas fa-undo"></i></button><button @click="downloadBackup(b.id)"><i class="fas fa-download"></i></button></td></tr></tbody></table>
    </div>
</template>