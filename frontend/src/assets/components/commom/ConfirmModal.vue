<template>
    <Teleport to="body">
        <div v-if="visible" class="modal-overlay" @click.self="close">
            <div class="modal-container" :style="{ maxWidth: width }">
                <div class="modal-header">
                    <h3 class="modal-title">{{ title }}</h3>
                    <button class="modal-close" @click="close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body"><p>{{ message }}</p></div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="close">{{ cancelText }}</button>
                    <button class="btn btn-danger" @click="confirm">{{ confirmText }}</button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref } from 'vue'

const visible = ref(false)
let resolvePromise = null

const open = (options = {}) => {
    return new Promise((resolve) => {
        resolvePromise = resolve
        visible.value = true
    })
}

const confirm = () => { resolvePromise?.(true); close() }
const close = () => { resolvePromise?.(false); visible.value = false }

defineExpose({ open })
</script>

<style scoped>
.modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; }
.modal-container { background: var(--card-bg); border-radius: 12px; width: 90%; max-width: 500px; animation: modalSlideIn 0.2s ease; }
.modal-header { padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
.modal-title { margin: 0; font-size: 1.25rem; }
.modal-close { background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--text-secondary); }
.modal-body { padding: 20px; }
.modal-footer { padding: 16px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px; }
@keyframes modalSlideIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
</style>