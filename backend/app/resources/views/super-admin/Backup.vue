<template>
    <div class="backup-management">
        <div class="header-actions">
            <h2>Sao lưu & Phục hồi</h2>
            <button class="btn-primary" @click="createBackup">
                <i class="fas fa-database"></i> Tạo backup ngay
            </button>
        </div>

        <div class="backup-list">
            <table class="data-table">
                <thead><tr><th>File backup</th><th>Loại</th><th>Kích thước</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
                <tbody>
                    <tr v-for="backup in backups" :key="backup.id">
                        <td>{{ backup.filename }}</td><td>{{ backup.type }}</td><td>{{ formatSize(backup.size) }}</td>
                        <td>{{ formatDate(backup.created_at) }}</td>
                        <td class="actions">
                            <button @click="restoreBackup(backup.id)" title="Phục hồi"><i class="fas fa-undo"></i></button>
                            <button @click="downloadBackup(backup.id)" title="Tải xuống"><i class="fas fa-download"></i></button>
                            <button @click="deleteBackup(backup.id)" title="Xóa"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>