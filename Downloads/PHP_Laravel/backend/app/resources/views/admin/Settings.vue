<template>
    <div class="settings"><h2>Cài đặt hệ thống</h2>
        <form @submit.prevent="saveSettings">
            <div class="form-group"><label>Tên hệ thống</label><input v-model="settings.app_name" type="text"></div>
            <div class="form-group"><label>JWT TTL (giây)</label><input v-model.number="settings.jwt_ttl" type="number"></div>
            <div class="form-group"><label>Rate limit</label><input v-model.number="settings.rate_limit" type="number"></div>
            <div class="form-group"><label>Bật 2FA</label><input v-model="settings.two_factor_enabled" type="checkbox"></div>
            <button type="submit" class="btn-primary">Lưu</button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useSettingStore } from '@/stores/setting'
const settingStore = useSettingStore()
const settings = ref({})
const saveSettings = async () => { await settingStore.update(settings.value); alert('Đã lưu') }
onMounted(async () => { settings.value = await settingStore.fetchAll() })
</script>