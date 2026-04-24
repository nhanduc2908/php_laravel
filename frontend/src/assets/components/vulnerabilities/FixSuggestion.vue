<template>
    <div class="fix-suggestion">
        <div class="suggestion-header">
            <i class="fas fa-tools"></i>
            <h3>Đề xuất khắc phục</h3>
        </div>
        <div class="suggestion-content">
            <div class="suggestion-item" v-for="(item, idx) in suggestions" :key="idx">
                <div class="item-step">{{ idx + 1 }}</div>
                <div class="item-content">{{ item }}</div>
            </div>
        </div>
        <div class="suggestion-actions">
            <button class="btn btn-success" @click="markFixed" v-if="!isFixed">
                <i class="fas fa-check"></i> Đánh dấu đã khắc phục
            </button>
            <button class="btn btn-secondary" @click="createTicket">
                <i class="fas fa-ticket-alt"></i> Tạo ticket xử lý
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({ vulnerability: { type: Object, required: true } })
const emit = defineEmits(['mark-fixed'])

const suggestions = ref([])
const isFixed = computed(() => props.vulnerability.status === 'fixed')

const generateSuggestions = () => {
    const genericSuggestions = [
        'Cập nhật phiên bản mới nhất của phần mềm/phần cứng',
        'Áp dụng bản vá bảo mật từ nhà cung cấp',
        'Cấu hình lại các thiết lập bảo mật theo khuyến nghị',
        'Kiểm tra và loại bỏ các tài khoản không cần thiết',
        'Hạn chế quyền truy cập theo nguyên tắc tối thiểu',
        'Kích hoạt tính năng ghi log và giám sát'
    ]
    suggestions.value = genericSuggestions
}

const markFixed = () => emit('mark-fixed')
const createTicket = () => { window.open('/tickets/create?vulnerability=' + props.vulnerability.id, '_blank') }

onMounted(generateSuggestions)
</script>

<style scoped>
.fix-suggestion { background: var(--card-bg); border-radius: 12px; padding: 20px; }
.suggestion-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.suggestion-header i { font-size: 24px; color: var(--primary-600); }
.suggestion-header h3 { margin: 0; }
.suggestion-content { margin-bottom: 24px; }
.suggestion-item { display: flex; gap: 12px; margin-bottom: 16px; }
.item-step { width: 28px; height: 28px; background: var(--primary-600); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; flex-shrink: 0; }
.item-content { flex: 1; line-height: 1.5; }
.suggestion-actions { display: flex; gap: 12px; padding-top: 20px; border-top: 1px solid var(--border-color); }
</style>