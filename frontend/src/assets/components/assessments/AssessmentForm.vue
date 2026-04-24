<template>
    <div class="assessment-form">
        <div class="form-header">
            <h2>Đánh giá an ninh</h2>
            <div class="server-select">
                <label>Chọn máy chủ:</label>
                <select v-model="selectedServerId" @change="loadCriteria">
                    <option value="">-- Chọn máy chủ --</option>
                    <option v-for="server in servers" :key="server.id" :value="server.id">{{ server.name }} ({{ server.host }})</option>
                </select>
            </div>
        </div>
        <div v-if="!selectedServerId" class="empty-state"><i class="fas fa-server"></i><p>Vui lòng chọn máy chủ để bắt đầu đánh giá</p></div>
        <div v-else-if="loading" class="loading-state"><LoadingSpinner :is-loading="true" text="Đang tải tiêu chí..." /></div>
        <div v-else>
            <div class="progress-info">Tiến độ: {{ answeredCount }}/{{ totalCriteria }} ({{ progressPercent }}%)</div>
            <div class="progress-bar"><div class="progress-fill" :style="{ width: `${progressPercent}%` }"></div></div>
            <QuestionList ref="questionListRef" :criteria="criteria" :answers="answers" @update="updateAnswer" />
            <div class="form-actions"><button class="btn btn-secondary" @click="saveDraft" :disabled="saving"><i v-if="saving" class="fas fa-spinner fa-spin"></i>Lưu nháp</button><button class="btn btn-primary" @click="submitAssessment" :disabled="submitting || !isComplete"><i v-if="submitting" class="fas fa-spinner fa-spin"></i>Nộp đánh giá</button></div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import QuestionList from './QuestionList.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

const emit = defineEmits(['submitted'])

const servers = ref([])
const criteria = ref([])
const selectedServerId = ref('')
const answers = ref({})
const loading = ref(false)
const saving = ref(false)
const submitting = ref(false)
const questionListRef = ref(null)

const totalCriteria = computed(() => criteria.value.length)
const answeredCount = computed(() => Object.keys(answers.value).length)
const progressPercent = computed(() => totalCriteria.value ? Math.round((answeredCount.value / totalCriteria.value) * 100) : 0)
const isComplete = computed(() => answeredCount.value === totalCriteria.value && totalCriteria.value > 0)

const fetchServers = async () => { const response = await fetch('/api/v1/servers'); servers.value = (await response.json()).data?.data || [] }
const loadCriteria = async () => { loading.value = true; const response = await fetch(`/api/v1/criteria?per_page=100`); criteria.value = (await response.json()).data?.data || []; answers.value = {}; loading.value = false }
const updateAnswer = (criteriaId, value, evidence) => { answers.value[criteriaId] = { criteria_id: criteriaId, value, evidence } }
const saveDraft = async () => { saving.value = true; await new Promise(r => setTimeout(r, 500)); saving.value = false }
const submitAssessment = async () => { if (!isComplete.value) return; submitting.value = true; const payload = { server_id: selectedServerId.value, answers: Object.values(answers.value) }; await new Promise(r => setTimeout(r, 1000)); emit('submitted', payload); submitting.value = false }

onMounted(fetchServers)
</script>

<style scoped>
.assessment-form { max-width: 900px; margin: 0 auto; }
.form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
.server-select { display: flex; align-items: center; gap: 12px; }
.server-select select { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; min-width: 250px; }
.progress-info { font-size: 14px; color: var(--text-secondary); margin-bottom: 8px; }
.progress-bar { background: var(--bg-secondary); border-radius: 10px; height: 10px; margin-bottom: 24px; overflow: hidden; }
.progress-fill { background: linear-gradient(90deg, var(--primary-600), var(--primary-400)); height: 100%; transition: width 0.3s; }
.empty-state, .loading-state { text-align: center; padding: 60px; color: var(--text-secondary); }
.empty-state i { font-size: 48px; margin-bottom: 16px; }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border-color); }
</style>