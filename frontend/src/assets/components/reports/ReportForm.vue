<template>
    <div class="report-form">
        <form @submit.prevent="submit">
            <div class="form-group"><label class="form-label">Loại báo cáo <span class="required">*</span></label>
                <select v-model="form.type" required>
                    <option value="">-- Chọn loại báo cáo --</option>
                    <option value="full">Báo cáo đầy đủ</option>
                    <option value="summary">Báo cáo tóm tắt</option>
                    <option value="compliance">Báo cáo tuân thủ</option>
                    <option value="vulnerability">Báo cáo lỗ hổng</option>
                </select>
            </div>
            <div class="form-group"><label class="form-label">Định dạng <span class="required">*</span></label>
                <div class="format-options">
                    <label class="format-option"><input type="radio" v-model="form.format" value="pdf"> <i class="fas fa-file-pdf"></i> PDF</label>
                    <label class="format-option"><input type="radio" v-model="form.format" value="excel"> <i class="fas fa-file-excel"></i> Excel</label>
                    <label class="format-option"><input type="radio" v-model="form.format" value="csv"> <i class="fas fa-file-csv"></i> CSV</label>
                </div>
            </div>
            <div class="form-group" v-if="form.type !== 'vulnerability'"><label class="form-label">Máy chủ</label>
                <select v-model="form.server_id"><option value="">Tất cả máy chủ</option><option v-for="s in servers" :key="s.id" :value="s.id">{{ s.name }}</option></select>
            </div>
            <div class="form-row"><div class="form-group"><label class="form-label">Từ ngày</label><input type="date" v-model="form.date_from"></div>
            <div class="form-group"><label class="form-label">Đến ngày</label><input type="date" v-model="form.date_to"></div></div>
            <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="form.include_charts"> Bao gồm biểu đồ</label></div>
            <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="form.include_details"> Bao gồm chi tiết</label></div>
            <div class="form-actions"><button type="button" class="btn btn-secondary" @click="cancel">Hủy</button><button type="submit" class="btn btn-primary" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Tạo báo cáo</button></div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'

const emit = defineEmits(['submit', 'cancel'])
const loading = ref(false)
const servers = ref([])

const form = reactive({ type: '', format: 'pdf', server_id: '', date_from: '', date_to: '', include_charts: true, include_details: true })

const fetchServers = async () => { const res = await fetch('/api/v1/servers'); servers.value = (await res.json()).data?.data || [] }
const submit = () => { loading.value = true; emit('submit', { ...form }); loading.value = false }
const cancel = () => emit('cancel')

onMounted(fetchServers)
</script>

<style scoped>
.report-form { max-width: 600px; margin: 0 auto; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; }
.required { color: var(--danger); }
.format-options { display: flex; gap: 20px; }
.format-option { display: flex; align-items: center; gap: 6px; cursor: pointer; }
.form-row { display: flex; gap: 20px; margin-bottom: 20px; }
.form-row .form-group { flex: 1; margin-bottom: 0; }
.checkbox-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color); }
select, input[type="date"] { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); }
</style>