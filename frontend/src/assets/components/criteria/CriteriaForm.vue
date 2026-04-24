<template>
    <div class="criteria-form">
        <form @submit.prevent="submit">
            <div class="form-row">
                <div class="form-group"><label class="form-label">Mã tiêu chí <span class="required">*</span></label>
                    <input type="text" class="form-control" v-model="form.code" :class="{ 'is-invalid': errors.code }" required>
                    <div class="invalid-feedback">{{ errors.code }}</div>
                </div>
                <div class="form-group"><label class="form-label">Danh mục <span class="required">*</span></label>
                    <select class="form-control" v-model="form.category_id" :class="{ 'is-invalid': errors.category_id }" required>
                        <option value="">-- Chọn danh mục --</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                    <div class="invalid-feedback">{{ errors.category_id }}</div>
                </div>
            </div>
            <div class="form-group"><label class="form-label">Tên tiêu chí <span class="required">*</span></label>
                <input type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" required>
                <div class="invalid-feedback">{{ errors.name }}</div>
            </div>
            <div class="form-group"><label class="form-label">Mô tả</label>
                <textarea class="form-control" v-model="form.description" rows="3"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Trọng số <span class="required">*</span></label>
                    <WeightSlider v-model="form.weight" :min="1" :max="10" />
                    <div class="invalid-feedback" v-if="errors.weight">{{ errors.weight }}</div>
                </div>
                <div class="form-group"><label class="form-label">Loại câu trả lời</label>
                    <select class="form-control" v-model="form.answer_type">
                        <option value="yes_no">Có/Không</option>
                        <option value="score">Điểm số</option>
                        <option value="text">Văn bản</option>
                        <option value="multiple_choice">Nhiều lựa chọn</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Trạng thái</label>
                    <select class="form-control" v-model="form.status"><option value="active">Hoạt động</option><option value="inactive">Không hoạt động</option></select>
                </div>
            </div>
            <div class="form-actions"><button type="button" class="btn btn-secondary" @click="cancel">Hủy</button><button type="submit" class="btn btn-primary" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i>{{ isEdit ? 'Cập nhật' : 'Thêm mới' }}</button></div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue'
import WeightSlider from './WeightSlider.vue'

const props = defineProps({ initialData: { type: Object, default: () => ({}) }, isEdit: { type: Boolean, default: false }, loading: { type: Boolean, default: false } })
const emit = defineEmits(['submit', 'cancel'])
const errors = ref({})
const categories = ref([])

const form = reactive({ code: '', name: '', description: '', category_id: null, weight: 5, answer_type: 'yes_no', status: 'active' })

watch(() => props.initialData, (val) => { if (val) Object.assign(form, val) }, { immediate: true, deep: true })

const fetchCategories = async () => { const response = await fetch('/api/v1/categories'); categories.value = (await response.json()).data || [] }

const validate = () => {
    const newErrors = {}
    if (!form.code) newErrors.code = 'Mã tiêu chí là bắt buộc'
    if (!form.name) newErrors.name = 'Tên tiêu chí là bắt buộc'
    if (!form.category_id) newErrors.category_id = 'Danh mục là bắt buộc'
    if (form.weight < 1 || form.weight > 10) newErrors.weight = 'Trọng số phải từ 1 đến 10'
    errors.value = newErrors
    return Object.keys(newErrors).length === 0
}

const submit = () => { if (validate()) emit('submit', { ...form }) }
const cancel = () => emit('cancel')

onMounted(fetchCategories)
</script>

<style scoped>
.criteria-form { max-width: 800px; margin: 0 auto; }
.form-row { display: flex; gap: 20px; margin-bottom: 20px; }
.form-row .form-group { flex: 1; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; }
.required { color: var(--danger); }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color); }
@media (max-width: 640px) { .form-row { flex-direction: column; gap: 16px; } }
</style>