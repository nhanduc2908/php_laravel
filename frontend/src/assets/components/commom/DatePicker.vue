<template>
    <div class="date-picker" ref="pickerRef">
        <div class="date-input" @click="toggleCalendar">
            <i class="fas fa-calendar-alt"></i>
            <input type="text" :value="displayValue" :placeholder="placeholder" readonly>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div v-show="showCalendar" class="calendar-dropdown">
            <div class="calendar-header"><button @click="prevMonth"><i class="fas fa-chevron-left"></i></button><span>{{ currentMonthName }} {{ currentYear }}</span><button @click="nextMonth"><i class="fas fa-chevron-right"></i></button></div>
            <div class="calendar-weekdays"><span v-for="day in weekdays" :key="day">{{ day }}</span></div>
            <div class="calendar-days"><button v-for="day in days" :key="day.date" :class="{ selected: isSelected(day), otherMonth: !day.isCurrentMonth }" @click="selectDate(day)">{{ day.day }}</button></div>
            <div class="calendar-footer"><button @click="clearDate">Xóa</button><button @click="selectToday">Hôm nay</button></div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({ modelValue: { type: [String, Date], default: null }, placeholder: { type: String, default: 'Chọn ngày' }, format: { type: String, default: 'DD/MM/YYYY' } })
const emit = defineEmits(['update:modelValue'])
const showCalendar = ref(false); const currentDate = ref(new Date()); const selectedDate = ref(props.modelValue ? new Date(props.modelValue) : null)
const weekdays = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN']
const monthNames = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12']
const currentMonthName = computed(() => monthNames[currentDate.value.getMonth()])
const currentYear = computed(() => currentDate.value.getFullYear())
const displayValue = computed(() => { if (!selectedDate.value) return ''; const d = selectedDate.value; return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}` })
const days = computed(() => { const year = currentDate.value.getFullYear(), month = currentDate.value.getMonth(); const firstDay = new Date(year, month, 1); const lastDay = new Date(year, month + 1, 0); const startDay = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1; const daysArray = []; for (let i = startDay; i > 0; i--) { const date = new Date(year, month, -i + 1); daysArray.push({ date, day: date.getDate(), isCurrentMonth: false }) } for (let i = 1; i <= lastDay.getDate(); i++) { daysArray.push({ date: new Date(year, month, i), day: i, isCurrentMonth: true }) } const remaining = 42 - daysArray.length; for (let i = 1; i <= remaining; i++) { const date = new Date(year, month + 1, i); daysArray.push({ date, day: date.getDate(), isCurrentMonth: false }) } return daysArray })
const isSelected = (day) => selectedDate.value && day.date.toDateString() === selectedDate.value.toDateString()
const selectDate = (day) => { selectedDate.value = day.date; emit('update:modelValue', day.date.toISOString().split('T')[0]); showCalendar.value = false }
const clearDate = () => { selectedDate.value = null; emit('update:modelValue', null); showCalendar.value = false }
const selectToday = () => selectDate({ date: new Date(), day: new Date().getDate(), isCurrentMonth: true })
const prevMonth = () => currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1)
const nextMonth = () => currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1)
const toggleCalendar = () => showCalendar.value = !showCalendar.value
const handleClickOutside = (e) => { if (!e.target.closest('.date-picker')) showCalendar.value = false }
onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
.date-picker { position: relative; display: inline-block; }
.date-input { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; background: var(--bg-primary); }
.date-input input { border: none; background: none; cursor: pointer; width: 120px; }
.calendar-dropdown { position: absolute; top: 100%; left: 0; margin-top: 4px; background: var(--card-bg); border-radius: 8px; box-shadow: var(--shadow-lg); z-index: 100; padding: 12px; min-width: 280px; }
.calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.calendar-weekdays { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; margin-bottom: 8px; font-weight: 600; font-size: 0.75rem; }
.calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
.calendar-days button { padding: 6px; text-align: center; background: none; border: none; border-radius: 4px; cursor: pointer; }
.calendar-days button:hover { background: var(--bg-secondary); }
.calendar-days button.selected { background: var(--primary-600); color: white; }
.calendar-days button.otherMonth { color: var(--text-secondary); opacity: 0.5; }
.calendar-footer { display: flex; justify-content: space-between; margin-top: 12px; padding-top: 8px; border-top: 1px solid var(--border-color); }
.calendar-footer button { background: none; border: none; cursor: pointer; color: var(--primary-600); }
</style>