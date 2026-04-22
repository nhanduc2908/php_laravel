<template>
    <div class="alerts-management">
        <h2>Cảnh báo hệ thống</h2>
        
        <div class="alert-filters">
            <select v-model="severityFilter">
                <option value="">Tất cả</option><option value="critical">Critical</option><option value="high">High</option>
                <option value="medium">Medium</option><option value="low">Low</option>
            </select>
            <label><input type="checkbox" v-model="showUnreadOnly"> Chỉ hiển thị chưa đọc</label>
            <button class="btn-secondary" @click="markAllRead">Đánh dấu tất cả đã đọc</button>
        </div>

        <div class="alerts-list">
            <div v-for="alert in alerts" :key="alert.id" :class="['alert-item', alert.severity, { unread: !alert.is_read }]">
                <div class="alert-icon"><i :class="getAlertIcon(alert.type)"></i></div>
                <div class="alert-content">
                    <h4>{{ alert.title }}</h4>
                    <p>{{ alert.message }}</p>
                    <small>{{ formatDate(alert.created_at) }}</small>
                </div>
                <div class="alert-actions">
                    <button @click="markRead(alert.id)" v-if="!alert.is_read"><i class="fas fa-check"></i></button>
                    <button @click="resolveAlert(alert.id)" v-if="!alert.is_resolved"><i class="fas fa-check-double"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>