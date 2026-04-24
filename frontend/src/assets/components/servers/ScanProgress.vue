<template>
    <div class="scan-progress" v-if="visible">
        <div class="progress-header">
            <span class="title">{{ title }}</span>
            <span class="percentage">{{ progress }}%</span>
        </div>
        <div class="progress-bar"><div class="progress-fill" :style="{ width: `${progress}%` }"></div></div>
        <div class="progress-message">{{ message }}</div>
        <div class="progress-details" v-if="details.length"><div v-for="detail in details" :key="detail" class="detail-item"><i class="fas fa-check-circle"></i> {{ detail }}</div></div>
        <button v-if="progress === 100" class="btn btn-primary btn-sm" @click="close">Đóng</button>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useWebSocket } from '@/composables/useWebSocket'

const props = defineProps({ serverId: { type: Number, required: true } })
const emit = defineEmits(['complete', 'close'])

const visible = ref(true)
const progress = ref(0)
const title = ref('Đang quét máy chủ...')
const message = ref('Đang khởi tạo...')
const details = ref([])

const { onEvent, offEvent } = useWebSocket()

const updateProgress = (data) => {
    progress.value = data.progress
    message.value = data.message || `Đang quét... ${data.progress}%`
    if (data.detail) details.value.push(data.detail)
    if (data.progress === 100) { title.value = 'Quét hoàn tất!'; emit('complete', data.result) }
}

onMounted(() => { onEvent(`scan.progress.${props.serverId}`, updateProgress) })
onUnmounted(() => { offEvent(`scan.progress.${props.serverId}`, updateProgress) })
const close = () => { visible.value = false; emit('close') }
</script>

<style scoped>
.scan-progress { background: var(--card-bg); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-lg); min-width: 300px; }
.progress-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
.percentage { font-weight: 600; color: var(--primary-600); }
.progress-bar { background: var(--bg-secondary); border-radius: 10px; height: 8px; overflow: hidden; margin-bottom: 10px; }
.progress-fill { background: linear-gradient(90deg, var(--primary-600), var(--primary-400)); height: 100%; transition: width 0.3s; }
.progress-message { font-size: 14px; color: var(--text-secondary); margin-bottom: 12px; }
.progress-details { margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border-color); }
.detail-item { font-size: 12px; color: var(--success); margin-bottom: 6px; }
.detail-item i { margin-right: 6px; font-size: 10px; }
</style>