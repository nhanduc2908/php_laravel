<template>
    <div class="language-switcher">
        <button v-for="lang in languages" :key="lang.code" class="lang-btn" :class="{ active: currentLocale === lang.code }" @click="setLanguage(lang.code)">
            <img :src="lang.flag" :alt="lang.name" class="lang-flag">
            <span>{{ lang.name }}</span>
        </button>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useLanguageStore } from '@/stores/language'

const languageStore = useLanguageStore()
const currentLocale = computed(() => languageStore.currentLocale)
const languages = computed(() => languageStore.supportedLanguages)

const setLanguage = (locale) => { languageStore.setLocale(locale) }
</script>

<style scoped>
.language-switcher { display: flex; gap: 8px; }
.lang-btn { display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: none; border: 1px solid var(--border-color); border-radius: 20px; cursor: pointer; transition: all 0.2s; }
.lang-btn:hover { background: var(--bg-secondary); }
.lang-btn.active { background: var(--primary-600); border-color: var(--primary-600); color: white; }
.lang-flag { width: 20px; height: 20px; border-radius: 2px; }
</style>