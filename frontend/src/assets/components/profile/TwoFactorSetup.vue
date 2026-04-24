<template>
    <div class="twofactor-setup">
        <div v-if="!enabled" class="setup-step"><h4>Bước 1: Cài đặt ứng dụng xác thực</h4><p>Tải Google Authenticator hoặc Authy trên điện thoại.</p><h4>Bước 2: Quét mã QR</h4><div class="qr-code" v-html="qrCode"></div><p>Hoặc nhập mã thủ công: <code>{{ secret }}</code></p><h4>Bước 3: Xác nhận mã</h4><div class="verify-form"><input type="text" v-model="verifyCode" placeholder="Nhập mã 6 số" maxlength="6"><button @click="verify" :disabled="verifying"><i v-if="verifying" class="fas fa-spinner fa-spin"></i> Xác nhận</button></div></div>
        <div v-else class="enabled-status"><div class="status-icon"><i class="fas fa-shield-alt"></i></div><h3>2FA đã được bật</h3><p>Tài khoản của bạn đã được bảo vệ bởi xác thực hai yếu tố.</p><button class="btn btn-danger" @click="disable2FA">Tắt 2FA</button></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const authStore = useAuthStore()
const toast = useToast()
const enabled = ref(false)
const secret = ref('')
const qrCode = ref('')
const verifyCode = ref('')
const verifying = ref(false)

const load2FAStatus = async () => { const res = await fetch('/api/v1/profile/2fa/status'); const data = await res.json(); enabled.value = data.enabled || false }
const setup2FA = async () => { const res = await fetch('/api/v1/profile/2fa/setup'); const data = await res.json(); secret.value = data.secret; qrCode.value = data.qr_code }
const verify = async () => { verifying.value = true; const res = await fetch('/api/v1/profile/2fa/verify', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ code: verifyCode.value }) }); if (res.ok) { enabled.value = true; toast.success('2FA đã được bật') } else { toast.error('Mã xác thực không đúng') } verifying.value = false }
const disable2FA = async () => { await fetch('/api/v1/profile/2fa/disable', { method: 'POST' }); enabled.value = false; toast.success('2FA đã được tắt') }
onMounted(() => { load2FAStatus(); if (!enabled.value) setup2FA() })
</script>

<style scoped>
.twofactor-setup { max-width: 500px; margin: 0 auto; }
.qr-code { margin: 20px 0; display: flex; justify-content: center; }
.verify-form { display: flex; gap: 12px; margin-top: 16px; }
.verify-form input { flex: 1; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; text-align: center; font-size: 18px; letter-spacing: 4px; }
.enabled-status { text-align: center; padding: 40px; }
.status-icon { font-size: 64px; color: var(--success); margin-bottom: 20px; }
code { background: var(--bg-secondary); padding: 4px 8px; border-radius: 4px; font-family: monospace; }
</style>