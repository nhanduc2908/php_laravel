<template>
    <div class="avatar-upload">
        <div class="avatar-preview"><img :src="avatarUrl" alt="Avatar"><div class="avatar-overlay" @click="triggerFileInput"><i class="fas fa-camera"></i><span>Đổi ảnh</span></div></div>
        <input type="file" ref="fileInput" accept="image/jpeg,image/png,image/gif" style="display: none" @change="uploadAvatar">
        <div class="avatar-info"><p>Định dạng: JPG, PNG, GIF</p><p>Kích thước tối đa: 2MB</p><p>Khuyến nghị: 200x200 pixels</p></div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const authStore = useAuthStore()
const toast = useToast()
const fileInput = ref(null)

const avatarUrl = computed(() => authStore.user?.avatar || '/assets/images/default-avatar.png')

const triggerFileInput = () => fileInput.value?.click()
const uploadAvatar = async (e) => { const file = e.target.files[0]; if (!file) return; const formData = new FormData(); formData.append('avatar', file); const res = await fetch('/api/v1/profile/avatar', { method: 'POST', body: formData }); if (res.ok) { authStore.fetchCurrentUser(); toast.success('Cập nhật avatar thành công') } else { toast.error('Upload avatar thất bại') } }
</script>

<style scoped>
.avatar-upload { display: flex; align-items: center; gap: 32px; flex-wrap: wrap; }
.avatar-preview { position: relative; width: 120px; height: 120px; border-radius: 50%; overflow: hidden; cursor: pointer; }
.avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
.avatar-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; opacity: 0; transition: opacity 0.2s; border-radius: 50%; }
.avatar-preview:hover .avatar-overlay { opacity: 1; }
.avatar-overlay i { font-size: 24px; margin-bottom: 4px; }
.avatar-overlay span { font-size: 11px; }
.avatar-info p { margin: 4px 0; font-size: 12px; color: var(--text-secondary); }
</style>