<template>
    <div class="question-list">
        <div v-for="(item, index) in criteria" :key="item.id" class="question-item" :class="{ answered: hasAnswer(item.id) }">
            <div class="question-header">
                <span class="question-number">{{ index + 1 }}</span>
                <span class="question-code">{{ item.code }}</span>
                <span class="question-weight">(Trọng số: {{ item.weight }})</span>
                <span class="question-status"><i class="fas fa-check-circle" v-if="hasAnswer(item.id)"></i><i class="far fa-circle" v-else></i></span>
            </div>
            <div class="question-title">{{ item.name }}</div>
            <div class="question-description" v-if="item.description">{{ item.description }}</div>
            <div class="question-answer">
                <div v-if="item.answer_type === 'yes_no'" class="answer-options">
                    <label class="radio-label"><input type="radio" :name="`q_${item.id}`" value="yes" @change="updateAnswer(item.id, 'yes')"> Có</label>
                    <label class="radio-label"><input type="radio" :name="`q_${item.id}`" value="no" @change="updateAnswer(item.id, 'no')"> Không</label>
                    <label class="radio-label"><input type="radio" :name="`q_${item.id}`" value="na" @change="updateAnswer(item.id, 'na')"> Không áp dụng</label>
                </div>
                <div v-else-if="item.answer_type === 'score'" class="answer-score"><input type="range" :min="0" :max="100" :value="getAnswerValue(item.id)" @input="updateAnswer(item.id, $event.target.value)"><span class="score-value">{{ getAnswerValue(item.id) || 0 }}%</span></div>
                <div v-else class="answer-text"><textarea :value="getAnswerValue(item.id)" @input="updateAnswer(item.id, $event.target.value)" rows="3" placeholder="Nhập câu trả lời..."></textarea></div>
                <div class="answer-evidence"><label><i class="fas fa-paperclip"></i> Bằng chứng:</label><input type="text" :value="getEvidence(item.id)" @input="updateEvidence(item.id, $event.target.value)" placeholder="URL, file path hoặc mô tả bằng chứng"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({ criteria: { type: Array, required: true }, answers: { type: Object, default: () => ({}) } })
const emit = defineEmits(['update'])

const getAnswer = (id) => props.answers[id] || {}
const getAnswerValue = (id) => getAnswer(id).value || ''
const getEvidence = (id) => getAnswer(id).evidence || ''
const hasAnswer = (id) => !!getAnswer(id).value
const updateAnswer = (id, value) => emit('update', id, value, getEvidence(id))
const updateEvidence = (id, evidence) => emit('update', id, getAnswerValue(id), evidence)
</script>

<style scoped>
.question-list { display: flex; flex-direction: column; gap: 24px; }
.question-item { background: var(--card-bg); border-radius: 12px; padding: 20px; border: 1px solid var(--border-color); transition: all 0.2s; }
.question-item.answered { border-left: 4px solid var(--success); }
.question-header { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; flex-wrap: wrap; }
.question-number { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: var(--bg-secondary); border-radius: 50%; font-size: 12px; font-weight: 600; }
.question-code { font-family: monospace; font-size: 13px; color: var(--primary-600); }
.question-weight { font-size: 12px; color: var(--text-secondary); }
.question-status { margin-left: auto; color: var(--success); }
.question-title { font-size: 16px; font-weight: 500; margin-bottom: 8px; }
.question-description { font-size: 14px; color: var(--text-secondary); margin-bottom: 16px; }
.answer-options { display: flex; gap: 24px; margin-bottom: 16px; }
.radio-label { display: flex; align-items: center; gap: 6px; cursor: pointer; }
.answer-score { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
.answer-score input { flex: 1; }
.score-value { min-width: 50px; font-weight: 600; color: var(--primary-600); }
.answer-text textarea { width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); resize: vertical; margin-bottom: 16px; }
.answer-evidence label { display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px; }
.answer-evidence input { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-primary); }
</style>