<template>
    <div class="settings"><h2>Cài đặt hệ thống</h2><div class="settings-tabs"><button :class="{ active: activeTab === 'general' }" @click="activeTab = 'general'">Chung</button><button :class="{ active: activeTab === 'security' }" @click="activeTab = 'security'">Bảo mật</button><button :class="{ active: activeTab === 'mail' }" @click="activeTab = 'mail'">Email</button><button :class="{ active: activeTab === 'backup' }" @click="activeTab = 'backup'">Sao lưu</button></div>
    <div class="settings-content"><form @submit.prevent="saveSettings"><div v-if="activeTab === 'general'"><div class="form-group"><label>Tên hệ thống</label><input v-model="settings.app_name" type="text"></div><div class="form-group"><label>Môi trường</label><select v-model="settings.app_env"><option>local</option><option>production</option></select></div><div class="form-group"><label>Debug mode</label><input v-model="settings.app_debug" type="checkbox"></div></div>
    <div v-if="activeTab === 'security'"><div class="form-group"><label>JWT TTL (giây)</label><input v-model.number="settings.jwt_ttl" type="number"></div><div class="form-group"><label>Rate limit (requests/phút)</label><input v-model.number="settings.rate_limit" type="number"></div><div class="form-group"><label>Bật 2FA</label><input v-model="settings.two_factor_enabled" type="checkbox"></div></div>
    <div v-if="activeTab === 'mail'"><div class="form-group"><label>SMTP Host</label><input v-model="settings.mail_host" type="text"></div><div class="form-group"><label>SMTP Port</label><input v-model.number="settings.mail_port" type="number"></div><div class="form-group"><label>Username</label><input v-model="settings.mail_username" type="text"></div><div class="form-group"><label>Password</label><input v-model="settings.mail_password" type="password"></div></div>
    <div v-if="activeTab === 'backup'"><div class="form-group"><label>Đường dẫn backup</label><input v-model="settings.backup_path" type="text"></div><div class="form-group"><label>Giữ lại (ngày)</label><input v-model.number="settings.backup_keep_days" type="number"></div></div>
    <button type="submit" class="btn-primary">Lưu cài đặt</button></form></div></div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useSettingStore } from '@/stores/setting'
const settingStore = useSettingStore(); const activeTab = ref('general'); const settings = ref({})
const saveSettings = async () => { await settingStore.update(settings.value); alert('Đã lưu cài đặt') }
onMounted(async () => { settings.value = await settingStore.fetchAll() })
</script>