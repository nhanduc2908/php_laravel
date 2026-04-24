<template>
    <div class="schedule-form">
        <form @submit.prevent="submit">
            <div class="form-group"><label class="form-label">Tên báo cáo</label><input type="text" v-model="form.name" placeholder="VD: Báo cáo tuần" required></div>
            <div class="form-row"><div class="form-group"><label class="form-label">Tần suất</label>
                <select v-model="form.frequency">
                    <option value="daily">Hàng ngày</option><option value="weekly">Hàng tuần</option><option value="monthly">Hàng tháng</option>
                </select>
            </div>
            <div class="form-group"><label class="form-label">Định dạng</label>
                <select v-model="form.format"><option value="pdf">PDF</option><option value="excel">Excel</option></select>
            </div></div>
            <div class="form-group"><label class="form-label">Email nhận báo cáo</label><input type="email" v-model="form.recipient_email" placeholder="email@example.com" required></div>
            <div class="form-group" v-if="form.frequency === 'weekly'"><label class="form-label">Ngày trong tuần</label>
                <select v-model="form.day_of_week"><option value="1">Thứ 2</option><option value="2">Thứ 3</option><option value="3">Thứ 4</option><option value="4">Thứ 5</option><option value="5">Thứ 6</option><option value="6">Thứ 7</option><option value="0">Chủ nhật</option></select>
            </div>
            <div class="form-group" v-if="form.frequency === 'monthly'"><label class="form-label">Ngày trong tháng</label><input type="number" v-model="form.day_of_month" min="1" max="28"></div>
            <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="form.is_active"> Kích hoạt lịch trình</label></div>
            <div class="form-actions"><button type="button" class="btn btn-secondary" @click="cancel">Hủy</button><button type="submit" class="btn btn-primary" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Lên lịch</button></div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

const emit = defineEmits(['submit', 'cancel'])
const loading = ref(false)

const form = reactive({ name: '', frequency: 'weekly', format: 'pdf', recipient_email: '', day_of_week: '1', day_of_month: 1, is_active: true })

const submit = () => { loading.value = true; emit('submit', { ...form }); loading.value = false }
const cancel = () => emit('cancel')
</script>

<style scoped>
.schedule-form { max-width: 500px; margin: 0 auto; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; }
.form-row { display: flex; gap: 20px; }
.form-row .form-group { flex: 1; }
.checkbox-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color); }
input, select { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); }
</style>