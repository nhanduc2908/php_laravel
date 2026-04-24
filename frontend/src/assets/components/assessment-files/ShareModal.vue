<template><div class="share-modal"><h3>Chia sẻ tệp</h3><p class="file-title">{{ file.title }}</p>
<div class="share-form"><div class="form-group"><label>Chia sẻ với</label><select v-model="selectedUserId"><option value="">-- Chọn người dùng --</option><option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }} ({{ u.email }})</option></select></div>
<div class="form-group"><label>Quyền</label><select v-model="permission"><option value="view">Chỉ xem</option><option value="edit">Chỉnh sửa</option></select></div>
<div class="form-group"><label>Hết hạn sau</label><input type="date" v-model="expiresAt"></div>
<button class="btn btn-primary" @click="share" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Chia sẻ</button></div>
<div class="shared-list" v-if="existingShares.length"><h4>Đã chia sẻ với</h4><div v-for="share in existingShares" :key="share.id" class="shared-item"><span>{{ share.user_name }}</span><span class="permission">{{ share.permission === 'view' ? 'Xem' : 'Sửa' }}</span><span class="expires">Hết hạn: {{ formatDate(share.expires_at) || 'Vĩnh viễn' }}</span><button class="revoke-btn" @click="revoke(share.id)"><i class="fas fa-trash"></i></button></div></div>
<div class="modal-actions"><button class="btn btn-secondary" @click="close">Đóng</button></div></div></template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({ file: { type: Object, required: true }, visible: Boolean })
const emit = defineEmits(['close', 'shared'])
const users = ref([]); const existingShares = ref([]); const selectedUserId = ref(''); const permission = ref('view'); const expiresAt = ref(''); const loading = ref(false)

const loadUsers = async () => { const res = await fetch('/api/v1/users'); users.value = (await res.json()).data?.data || [] }
const loadShares = async () => { const res = await fetch(`/api/v1/assessment-files/${props.file.id}/shares`); existingShares.value = (await res.json()).data || [] }
const share = async () => { if (!selectedUserId.value) return; loading.value = true; await fetch(`/api/v1/assessment-files/${props.file.id}/share/${selectedUserId.value}`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ permission: permission.value, expires_at: expiresAt.value }) }); loading.value = false; loadShares(); emit('shared') }
const revoke = async (shareId) => { await fetch(`/api/v1/assessment-files/shares/${shareId}`, { method: 'DELETE' }); loadShares() }
const close = () => emit('close')
const formatDate = (date) => date ? new Date(date).toLocaleDateString('vi-VN') : ''
onMounted(() => { loadUsers(); loadShares() })
</script>

<style scoped>
.share-modal { background: var(--card-bg); border-radius: 12px; padding: 24px; width: 500px; max-width: 90vw; }
.file-title { color: var(--text-secondary); margin-bottom: 20px; }
.share-form { display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px; }
.shared-list { border-top: 1px solid var(--border-color); padding-top: 16px; margin-top: 16px; }
.shared-item { display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid var(--border-color); }
.permission { padding: 2px 8px; background: var(--bg-secondary); border-radius: 12px; font-size: 11px; }
.expires { font-size: 11px; color: var(--text-secondary); flex: 1; }
.revoke-btn { background: none; border: none; cursor: pointer; color: var(--danger); }
.modal-actions { display: flex; justify-content: flex-end; margin-top: 20px; }
</style>