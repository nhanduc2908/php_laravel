<template>
    <div class="backup-form">
        <form @submit.prevent="submit">
            <div class="form-group"><label class="form-label">Loại backup <span class="required">*</span></label>
                <div class="type-options">
                    <label class="type-option"><input type="radio" v-model="form.type" value="database"> <i class="fas fa-database"></i> Database</label>
                    <label class="type-option"><input type="radio" v-model="form.type" value="files"> <i class="fas fa-file-alt"></i> Files</label>
                    <label class="type-option"><input type="radio" v-model="form.type" value="both"> <i class="fas fa-cloud-upload-alt"></i> Toàn bộ</label>
                </div>
            </div>
            <div class="form-group" v-if="form.type === 'database'"><label class="form-label">Tables (để trống để backup tất cả)</label><input type="text" v-model="form.tables_text" placeholder="users,servers,criteria (phân cách bằng dấu phẩy)"></div>
            <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="form.include_uploads"> Bao gồm file upload</label></div>
            <div class="form-group"><label class="form-label">Nén</label>
                <select v-model="form.compression"><option value="none">Không nén</option><option value="gzip">Gzip</option><option value="zip">Zip</option></select>
            </div>
            <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="form.encryption"> Mã hóa backup</label></div>
            <div class="form-actions"><button type="button" class="btn btn-secondary" @click="cancel">Hủy</button><button type="submit" class="btn btn-primary" :disabled="loading || creating"><i v-if="loading || creating" class="fas fa-spinner fa-spin"></i> Tạo backup</button></div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'

const emit = defineEmits(['submit', 'cancel'])
const loading = ref(false)
const creating = ref(false)

const form = reactive({ type: 'database', tables_text: '', include_uploads: true, compression: 'gzip', encryption: false })

const submit = () => { creating.value = true; const tables = form.tables_text ? form.tables_text.split(',').map(t => t.trim()) : null; emit('submit', { ...form, tables }); creating.value = false }
const cancel = () => emit('cancel')
</script>

<style scoped>
.backup-form { max-width: 500px; margin: 0 auto; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; }
.required { color: var(--danger); }
.type-options { display: flex; gap: 20px; flex-wrap: wrap; }
.type-option { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 16px; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.2s; }
.type-option:has(input:checked) { background: var(--primary-600); color: white; border-color: var(--primary-600); }
.type-option i { font-size: 18px; }
.checkbox-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
select, input[type="text"] { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color); }
</style>