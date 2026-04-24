<template>
    <div class="settings-form">
        <form @submit.prevent="saveSettings">
            <div class="form-section"><h3>Cấu hình chung</h3>
                <div class="form-group"><label class="form-label">Tên hệ thống</label><input type="text" class="form-control" v-model="settings.app_name" placeholder="Security Assessment Platform"></div>
                <div class="form-group"><label class="form-label">Môi trường</label><select class="form-control" v-model="settings.app_env"><option value="local">Local</option><option value="staging">Staging</option><option value="production">Production</option></select></div>
                <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="settings.app_debug"> Debug mode</label></div>
                <div class="form-group"><label class="form-label">Timezone</label><select class="form-control" v-model="settings.app_timezone"><option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh</option><option value="UTC">UTC</option></select></div>
            </div>
            <div class="form-section"><h3>Bảo mật</h3>
                <div class="form-group"><label class="form-label">JWT TTL (giây)</label><input type="number" class="form-control" v-model.number="settings.jwt_ttl" min="60" max="86400"></div>
                <div class="form-group"><label class="form-label">Refresh TTL (giây)</label><input type="number" class="form-control" v-model.number="settings.jwt_refresh_ttl" min="3600" max="2592000"></div>
                <div class="form-group"><label class="form-label">Rate limit (requests/phút)</label><input type="number" class="form-control" v-model.number="settings.rate_limit" min="10" max="1000"></div>
                <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="settings.two_factor_enabled"> Bật xác thực 2FA</label></div>
            </div>
            <div class="form-section"><h3>Email</h3>
                <div class="form-group"><label class="form-label">SMTP Host</label><input type="text" class="form-control" v-model="settings.mail_host"></div>
                <div class="form-group"><label class="form-label">SMTP Port</label><input type="number" class="form-control" v-model.number="settings.mail_port"></div>
                <div class="form-group"><label class="form-label">Username</label><input type="text" class="form-control" v-model="settings.mail_username"></div>
                <div class="form-group"><label class="form-label">Password</label><input type="password" class="form-control" v-model="settings.mail_password"></div>
                <div class="form-group"><label class="form-label">Encryption</label><select class="form-control" v-model="settings.mail_encryption"><option value="tls">TLS</option><option value="ssl">SSL</option></select></div>
            </div>
            <div class="form-section"><h3>Sao lưu</h3>
                <div class="form-group"><label class="form-label">Đường dẫn backup</label><input type="text" class="form-control" v-model="settings.backup_path" placeholder="/var/backups"></div>
                <div class="form-group"><label class="form-label">Giữ lại (ngày)</label><input type="number" class="form-control" v-model.number="settings.backup_keep_days" min="1" max="365"></div>
                <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="settings.backup_compress"> Nén backup</label></div>
            </div>
            <div class="form-actions"><button type="submit" class="btn btn-primary" :disabled="saving"><i v-if="saving" class="fas fa-spinner fa-spin"></i> Lưu cài đặt</button></div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'

const emit = defineEmits(['saved'])

const settings = reactive({ app_name: '', app_env: 'local', app_debug: false, app_timezone: 'Asia/Ho_Chi_Minh', jwt_ttl: 3600, jwt_refresh_ttl: 604800, rate_limit: 100, two_factor_enabled: true, mail_host: '', mail_port: 587, mail_username: '', mail_password: '', mail_encryption: 'tls', backup_path: '/var/backups', backup_keep_days: 30, backup_compress: true })
const saving = ref(false)

const loadSettings = async () => { const res = await fetch('/api/v1/settings'); const data = await res.json(); Object.assign(settings, data.data || {}) }
const saveSettings = async () => { saving.value = true; await fetch('/api/v1/settings', { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(settings) }); saving.value = false; emit('saved') }
onMounted(loadSettings)
</script>

<style scoped>
.settings-form { max-width: 600px; margin: 0 auto; }
.form-section { margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--border-color); }
.form-section h3 { font-size: 18px; margin-bottom: 20px; color: var(--primary-600); }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; }
.checkbox-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.form-control, select { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); }
.form-actions { display: flex; justify-content: flex-end; margin-top: 24px; }
</style>