<template>
    <div class="test-runner">
        <div class="runner-header">
            <h3>Test Runner</h3>
            <div class="runner-controls">
                <select v-model="selectedSuite" class="suite-select">
                    <option value="all">Tất cả test suites</option>
                    <option value="unit">Unit Tests</option>
                    <option value="feature">Feature Tests</option>
                    <option value="integration">Integration Tests</option>
                </select>
                <button class="btn btn-primary" @click="runTests" :disabled="running">
                    <i v-if="running" class="fas fa-spinner fa-spin"></i>
                    <i v-else class="fas fa-play"></i>
                    {{ running ? 'Đang chạy...' : 'Chạy test' }}
                </button>
            </div>
        </div>
        <div class="runner-output" v-if="output">
            <div class="output-header">
                <span>Kết quả thực thi</span>
                <button class="clear-btn" @click="clearOutput"><i class="fas fa-trash"></i></button>
            </div>
            <pre class="output-content">{{ output }}</pre>
        </div>
        <div class="runner-progress" v-if="running">
            <div class="progress-bar"><div class="progress-fill" :style="{ width: `${progress}%` }"></div></div>
            <span>{{ progress }}%</span>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useToast } from 'vue-toastification'

const toast = useToast()
const running = ref(false)
const progress = ref(0)
const output = ref('')
const selectedSuite = ref('all')

const runTests = async () => {
    running.value = true
    progress.value = 0
    output.value = ''
    
    const interval = setInterval(() => {
        if (progress.value < 90) progress.value += 10
    }, 500)
    
    try {
        const res = await fetch('/api/v1/testing/run', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ suite: selectedSuite.value })
        })
        const data = await res.json()
        progress.value = 100
        output.value = data.output || 'Tests completed successfully'
        toast.success(data.success ? 'Tests passed!' : 'Some tests failed')
    } catch (error) {
        output.value = error.message
        toast.error('Test execution failed')
    } finally {
        clearInterval(interval)
        setTimeout(() => { running.value = false }, 500)
    }
}

const clearOutput = () => { output.value = '' }
</script>

<style scoped>
.test-runner { background: var(--card-bg); border-radius: 12px; padding: 20px; }
.runner-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
.runner-controls { display: flex; gap: 12px; align-items: center; }
.suite-select { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); }
.runner-output { margin-top: 20px; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; }
.output-header { display: flex; justify-content: space-between; align-items: center; padding: 10px 16px; background: var(--bg-secondary); border-bottom: 1px solid var(--border-color); }
.clear-btn { background: none; border: none; cursor: pointer; color: var(--text-secondary); }
.output-content { padding: 16px; margin: 0; font-family: monospace; font-size: 12px; background: var(--bg-primary); color: var(--text-primary); max-height: 400px; overflow-y: auto; white-space: pre-wrap; }
.runner-progress { display: flex; align-items: center; gap: 12px; margin-top: 16px; }
.progress-bar { flex: 1; background: var(--bg-secondary); border-radius: 10px; height: 8px; overflow: hidden; }
.progress-fill { background: var(--primary-600); height: 100%; transition: width 0.3s; }
</style>