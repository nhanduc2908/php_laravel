<template>
    <div class="file-form"><form @submit.prevent="submit"><div class="form-group"><label class="form-label">Tiêu đề <span class="required">*</span></label><input type="text" class="form-control" v-model="form.title" required></div>
    <div class="form-group"><label class="form-label">Nội dung <span class="required">*</span></label><textarea class="form-control" v-model="form.content" rows="10" required></div>
    <div class="form-row"><div class="form-group"><label class="form-label">Máy chủ</label><select v-model="form.server_id"><option value="">-- Chọn máy chủ --</option><option v-for="s in servers" :key="s.id" :value="s.id">{{ s.name }}</option></select></div>
    <div class="form-group"><label class="form-label">Trạng thái</label><select v-model="form.status"><option value="draft">Nháp</option><option value="published">Xuất bản</option><option value="archived">Lưu trữ</option></select></div>
    <div class="form-group"><label class="form-label">Độ ưu tiên</label><select v-model="form.priority"><option value="low">Thấp</option><option value="medium">Trung bình</option><option value="high">Cao</option><option value="critical">Nghiêm trọng</option></select></div></div>
    <div class="form-group"><label class="form-label">Tags</label><input type="text" class="form-control" v-model="tagsInput" placeholder="Nhập tag và nhấn Enter"></div>
    <div class="form-actions"><button type="button" class="btn btn-secondary" @click="cancel">Hủy</button><button type="submit" class="btn btn-primary" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i>{{ isEdit ? 'Cập nhật' : 'Tạo mới' }}</button></div>
    </form></div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'

const props = defineProps({ initialData: { type: Object, default: () => ({}) }, isEdit: { type: Boolean, default: false }, loading: { type: Boolean, default: false } })
const emit = defineEmits(['submit', 'cancel'])
const servers = ref([])
const tagsInput = ref('')
const form = reactive({ title: '', content: '', server_id: '', status: 'draft', priority: 'medium', tags: [] })

watch(() => props.initialData, (val) => { if (val) { Object.assign(form, val); tagsInput.value = (val.tags || []).join(', ') } }, { immediate: true, deep: true })

const submit = () => { form.tags = tagsInput.value.split(',').map(t => t.trim()).filter(t => t); emit('submit', { ...form }) }
const cancel = () => emit('cancel')

fetchServers()
async function fetchServers() { const res = await fetch('/api/v1/servers'); servers.value = (await res.json()).data?.data || [] }
</script>

<style scoped>
.file-form { max-width: 800px; margin: 0 auto; }
.form-row { display: flex; gap: 20px; margin-bottom: 20px; }
.form-row .form-group { flex: 1; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; }
.required { color: var(--danger); }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color); }
@media (max-width: 640px) { .form-row { flex-direction: column; gap: 16px; } }
</style>