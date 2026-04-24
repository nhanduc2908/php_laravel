<template>
    <div class="settings-tabs">
        <div class="tabs-header"><button v-for="tab in tabs" :key="tab.key" class="tab-btn" :class="{ active: activeTab === tab.key }" @click="activeTab = tab.key"><i :class="tab.icon"></i> {{ tab.label }}</button></div>
        <div class="tabs-content">
            <div v-show="activeTab === 'general'" class="tab-pane"><SettingsForm @saved="$emit('saved')" /></div>
            <div v-show="activeTab === 'security'" class="tab-pane"><div class="security-settings"><div class="form-group"><label class="form-label">Session timeout (phút)</label><input type="number" class="form-control" v-model="security.session_timeout"></div>
            <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="security.force_mfa"> Bắt buộc MFA cho tất cả user</label></div>
            <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="security.ip_whitelist_enabled"> Bật IP whitelist</label></div>
            <div class="form-group" v-if="security.ip_whitelist_enabled"><label class="form-label">IP được phép (mỗi dòng một IP)</label><textarea class="form-control" v-model="security.ip_whitelist" rows="4" placeholder="192.168.1.1&#10;10.0.0.0/24"></textarea></div></div></div>
            <div v-show="activeTab === 'notification'" class="tab-pane"><div class="notification-settings"><div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="notify.email_enabled"> Gửi email thông báo</label></div>
            <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="notify.push_enabled"> Gửi push notification</label></div>
            <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="notify.sms_enabled"> Gửi SMS</label></div>
            <div class="form-group"><label class="form-label">Email nhận cảnh báo</label><input type="text" class="form-control" v-model="notify.alert_emails" placeholder="admin@example.com,security@example.com"></div></div></div>
            <div v-show="activeTab === 'integrations'" class="tab-pane"><div class="integration-settings"><div class="form-group"><label class="form-label">CVE API Key</label><input type="password" class="form-control" v-model="integrations.cve_api_key"></div>
            <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="integrations.enable_webhook"> Bật Webhook</label></div>
            <div class="form-group" v-if="integrations.enable_webhook"><label class="form-label">Webhook URL</label><input type="url" class="form-control" v-model="integrations.webhook_url"></div></div></div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import SettingsForm from './SettingsForm.vue'

const emit = defineEmits(['saved'])

const tabs = [{ key: 'general', label: 'Chung', icon: 'fas fa-cog' }, { key: 'security', label: 'Bảo mật', icon: 'fas fa-shield-alt' }, { key: 'notification', label: 'Thông báo', icon: 'fas fa-bell' }, { key: 'integrations', label: 'Tích hợp', icon: 'fas fa-plug' }]
const activeTab = ref('general')
const security = reactive({ session_timeout: 30, force_mfa: false, ip_whitelist_enabled: false, ip_whitelist: '' })
const notify = reactive({ email_enabled: true, push_enabled: true, sms_enabled: false, alert_emails: '' })
const integrations = reactive({ cve_api_key: '', enable_webhook: false, webhook_url: '' })
</script>

<style scoped>
.settings-tabs { background: var(--card-bg); border-radius: 12px; overflow: hidden; }
.tabs-header { display: flex; border-bottom: 1px solid var(--border-color); background: var(--bg-secondary); flex-wrap: wrap; }
.tab-btn { display: flex; align-items: center; gap: 8px; padding: 12px 20px; background: none; border: none; cursor: pointer; transition: all 0.2s; color: var(--text-secondary); }
.tab-btn:hover { background: var(--bg-primary); color: var(--primary-600); }
.tab-btn.active { background: var(--card-bg); color: var(--primary-600); border-bottom: 2px solid var(--primary-600); }
.tabs-content { padding: 24px; }
</style>